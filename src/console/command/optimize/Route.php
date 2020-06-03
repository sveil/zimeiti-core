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

namespace sveil\console\command\optimize;

use sveil\console\Command;
use sveil\console\Input;
use sveil\console\Output;
use sveil\Container;

class Route extends Command
{
    protected function configure()
    {
        $this->setName('optimize:route')
            ->setDescription('Build route cache.');
    }

    protected function execute(Input $input, Output $output)
    {
        $filename = Container::get()->getRuntimePath() . 'route.php';
        if (is_file($filename)) {
            unlink($filename);
        }
        file_put_contents($filename, $this->buildRouteCache());
        $output->writeln('<info>Succeed!</info>');
    }

    protected function buildRouteCache()
    {
        Container::get('route')->setName([]);
        Container::get('route')->setTestMode(true);
        // 路由检测
        $path = Container::get()->getRoutePath();

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

        $content = '<?php ' . PHP_EOL . 'return ';
        $content .= var_export(Container::get('route')->getName(), true) . ';';
        return $content;
    }

}
