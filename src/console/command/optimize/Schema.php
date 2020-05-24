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
use sveil\console\input\Option;
use sveil\console\Output;
use sveil\Db;
use sveil\facade\App;

/**
 * Class Schema
 * @author Richard <richard@sveil.com>
 * @package sveil\console\command\optimize
 */
class Schema extends Command
{
    protected function configure()
    {
        $this->setName('optimize:schema')
            ->addOption('db', null, Option::VALUE_REQUIRED, 'db name .')
            ->addOption('table', null, Option::VALUE_REQUIRED, 'table name .')
            ->addOption('module', null, Option::VALUE_REQUIRED, 'module name .')
            ->setDescription('Build database schema cache.');
    }

    protected function execute(Input $input, Output $output)
    {
        if (!is_dir(App::getRuntimePath() . 'schema')) {
            @mkdir(App::getRuntimePath() . 'schema', 0755, true);
        }

        if ($input->hasOption('module')) {
            $module = $input->getOption('module');
            // 读取模型
            $path      = App::getAppPath() . $module . DIRECTORY_SEPARATOR . 'model';
            $list      = is_dir($path) ? scandir($path) : [];
            $namespace = App::getNamespace();

            foreach ($list as $file) {
                if (0 === strpos($file, '.')) {
                    continue;
                }
                $class = '\\' . $namespace . '\\' . $module . '\\model\\' . pathinfo($file, PATHINFO_FILENAME);
                $this->buildModelSchema($class);
            }

            $output->writeln('<info>Succeed!</info>');
            return;
        } elseif ($input->hasOption('table')) {
            $table = $input->getOption('table');

            if (false === strpos($table, '.')) {
                $dbName = Db::getConfig('database');
            }

            $tables[] = $table;
        } elseif ($input->hasOption('db')) {
            $dbName = $input->getOption('db');
            $tables = Db::getConnection()->getTables($dbName);
        } elseif (!\sveil\facade\Config::get('app_multi_module')) {
            $namespace = App::getNamespace();
            $path      = App::getAppPath() . 'model';
            $list      = is_dir($path) ? scandir($path) : [];

            foreach ($list as $file) {
                if (0 === strpos($file, '.')) {
                    continue;
                }
                $class = '\\' . $namespace . '\\model\\' . pathinfo($file, PATHINFO_FILENAME);
                $this->buildModelSchema($class);
            }

            $output->writeln('<info>Succeed!</info>');

            return;
        } else {
            $tables = Db::getConnection()->getTables();
        }

        $db = isset($dbName) ? $dbName . '.' : '';
        $this->buildDataBaseSchema($tables, $db);
        $output->writeln('<info>Succeed!</info>');
    }

    protected function buildModelSchema($class)
    {
        $reflect = new \ReflectionClass($class);

        if (!$reflect->isAbstract() && $reflect->isSubclassOf('\sveil\Model')) {
            $table   = $class::getTable();
            $dbName  = $class::getConfig('database');
            $content = '<?php ' . PHP_EOL . 'return ';
            $info    = $class::getConnection()->getFields($table);
            $content .= var_export($info, true) . ';';

            file_put_contents(App::getRuntimePath() . 'schema' . DIRECTORY_SEPARATOR . $dbName . '.' . $table . '.php', $content);
        }
    }

    protected function buildDataBaseSchema($tables, $db)
    {
        if ('' == $db) {
            $dbName = Db::getConfig('database') . '.';
        } else {
            $dbName = $db;
        }

        foreach ($tables as $table) {
            $content = '<?php ' . PHP_EOL . 'return ';
            $info    = Db::getConnection()->getFields($db . $table);
            $content .= var_export($info, true) . ';';
            file_put_contents(App::getRuntimePath() . 'schema' . DIRECTORY_SEPARATOR . $dbName . $table . '.php', $content);
        }
    }
}
