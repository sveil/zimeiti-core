<?php
// +----------------------------------------------------------------------
// | Core for sveil/zimeiti-cms
// +----------------------------------------------------------------------
// | Copyright (c) 2019-2020 http://sveil.com All rights reserved.
// +----------------------------------------------------------------------
// | License ( http://www.gnu.org/licenses )
// +----------------------------------------------------------------------
// | gitee：https://gitee.com/sveil/zimeiti-core
// | github：https://github.com/sveil/zimeiti-core
// +----------------------------------------------------------------------

namespace sveil\console\command;

use sveil\console\Command;
use sveil\console\Input;
use sveil\console\input\Argument;
use sveil\console\input\Option;
use sveil\console\Output;
use sveil\console\Table;
use sveil\Container;

/**
 * Class RouteList
 * @author Richard <richard@sveil.com>
 * @package sveil\console\command
 */
class RouteList extends Command
{
    protected $sortBy = [
        'rule'   => 0,
        'route'  => 1,
        'method' => 2,
        'name'   => 3,
        'domain' => 4,
    ];

    protected function configure()
    {
        $this->setName('route:list')
            ->addArgument('style', Argument::OPTIONAL, "the style of the table.", 'default')
            ->addOption('sort', 's', Option::VALUE_OPTIONAL, 'order by rule name.', 0)
            ->addOption('more', 'm', Option::VALUE_NONE, 'show route options.')
            ->setDescription('show route list.');
    }

    protected function execute(Input $input, Output $output)
    {
        $filename = Container::get()->getRuntimePath() . 'route_list.php';

        if (is_file($filename)) {
            unlink($filename);
        }

        $content = $this->getRouteList();
        file_put_contents($filename, 'Route List' . PHP_EOL . $content);
    }

    protected function getRouteList()
    {
        Container::get('route')->setTestMode(true);
        // 路由检测
        $path  = Container::get()->getRoutePath();
        $files = is_dir($path) ? scandir($path) : [];

        foreach ($files as $file) {
            if (strpos($file, '.php')) {
                $filename = $path . DIRECTORY_SEPARATOR . $file;
                // 导入路由配置
                $rules = include $filename;

                if (is_array($rules)) {
                    Container::get('route')->import($rules);
                }
            }
        }

        if (Container::get('config')->get('route_annotation')) {
            $suffix = Container::get('config')->get('controller_suffix') || Container::get('config')->get('class_suffix');

            include Container::get('build')->buildRoute($suffix);
        }

        $table = new Table();

        if ($this->input->hasOption('more')) {
            $header = ['Rule', 'Route', 'Method', 'Name', 'Domain', 'Option', 'Pattern'];
        } else {
            $header = ['Rule', 'Route', 'Method', 'Name', 'Domain'];
        }

        $table->setHeader($header);
        $routeList = Container::get('route')->getRuleList();
        $rows      = [];

        foreach ($routeList as $domain => $items) {
            foreach ($items as $item) {
                $item['route'] = $item['route'] instanceof \Closure ? '<Closure>' : $item['route'];

                if ($this->input->hasOption('more')) {
                    $item = [$item['rule'], $item['route'], $item['method'], $item['name'], $domain, json_encode($item['option']), json_encode($item['pattern'])];
                } else {
                    $item = [$item['rule'], $item['route'], $item['method'], $item['name'], $domain];
                }

                $rows[] = $item;
            }
        }

        if ($this->input->getOption('sort')) {
            $sort = $this->input->getOption('sort');

            if (isset($this->sortBy[$sort])) {
                $sort = $this->sortBy[$sort];
            }

            uasort($rows, function ($a, $b) use ($sort) {
                $itemA = isset($a[$sort]) ? $a[$sort] : null;
                $itemB = isset($b[$sort]) ? $b[$sort] : null;

                return strcasecmp($itemA, $itemB);
            });
        }

        $table->setRows($rows);

        if ($this->input->getArgument('style')) {
            $style = $this->input->getArgument('style');
            $table->setStyle($style);
        }

        return $this->table($table);
    }
}
