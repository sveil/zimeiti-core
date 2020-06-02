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

namespace sveil\console\output\descriptor;

use sveil\Console as SveilConsole;
use sveil\console\Command;

class Console
{

    const GLOBAL_NAMESPACE = '_global';

    /**
     * @var SveilConsole
     */
    private $console;

    /**
     * @var null|string
     */
    private $namespace;

    /**
     * @var array
     */
    private $namespaces;

    /**
     * @var Command[]
     */
    private $commands;

    /**
     * @var Command[]
     */
    private $aliases;

    /**
     * 构造方法
     * @param SveilConsole $console
     * @param string|null  $namespace
     */
    public function __construct(SveilConsole $console, $namespace = null)
    {
        $this->console   = $console;
        $this->namespace = $namespace;
    }

    /**
     * @return array
     */
    public function getNamespaces()
    {
        if (null === $this->namespaces) {
            $this->inspectConsole();
        }

        return $this->namespaces;
    }

    /**
     * @return Command[]
     */
    public function getCommands()
    {
        if (null === $this->commands) {
            $this->inspectConsole();
        }

        return $this->commands;
    }

    /**
     * @param string $name
     * @return Command
     * @throws \InvalidArgumentException
     */
    public function getCommand($name)
    {
        if (!isset($this->commands[$name]) && !isset($this->aliases[$name])) {
            throw new \InvalidArgumentException(sprintf('Command %s does not exist.', $name));
        }

        return isset($this->commands[$name]) ? $this->commands[$name] : $this->aliases[$name];
    }

    private function inspectConsole()
    {
        $this->commands   = [];
        $this->namespaces = [];

        $all = $this->console->all($this->namespace ? $this->console->findNamespace($this->namespace) : null);
        foreach ($this->sortCommands($all) as $namespace => $commands) {
            $names = [];

            /** @var Command $command */
            foreach ($commands as $name => $command) {
                if (is_string($command)) {
                    $command = new $command();
                }

                if (!$command->getName()) {
                    continue;
                }

                if ($command->getName() === $name) {
                    $this->commands[$name] = $command;
                } else {
                    $this->aliases[$name] = $command;
                }

                $names[] = $name;
            }

            $this->namespaces[$namespace] = ['id' => $namespace, 'commands' => $names];
        }
    }

    /**
     * @param array $commands
     * @return array
     */
    private function sortCommands(array $commands)
    {
        $namespacedCommands = [];
        foreach ($commands as $name => $command) {
            $key = $this->console->extractNamespace($name, 1);
            if (!$key) {
                $key = self::GLOBAL_NAMESPACE;
            }

            $namespacedCommands[$key][$name] = $command;
        }
        ksort($namespacedCommands);

        foreach ($namespacedCommands as &$commandsSet) {
            ksort($commandsSet);
        }
        // unset reference to keep scope clear
        unset($commandsSet);

        return $namespacedCommands;
    }
}
