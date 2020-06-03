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

namespace sveil\console\command\make;

use sveil\console\command\Make;
use sveil\console\input\Argument;
use sveil\facade\App;

class Command extends Make
{
    protected $type = "Command";

    protected function configure()
    {
        parent::configure();
        $this->setName('make:command')
            ->addArgument('commandName', Argument::OPTIONAL, "The name of the command")
            ->setDescription('Create a new command class');
    }

    protected function buildClass($name)
    {
        $commandName = $this->input->getArgument('commandName') ?: strtolower(basename($name));
        $namespace   = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');

        $class = str_replace($namespace . '\\', '', $name);
        $stub  = file_get_contents($this->getStub());

        return str_replace(['{%commandName%}', '{%className%}', '{%namespace%}', '{%apps_namespace%}'], [
            $commandName,
            $class,
            $namespace,
            App::getNamespace(),
        ], $stub);
    }

    protected function getStub()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'command.stub';
    }

    protected function getNamespace($appNamespace, $module)
    {
        return $appNamespace . '\\command';
    }

}
