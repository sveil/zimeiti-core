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

namespace sveil\queue;

use Closure;
use sveil\Process;

/**
 * Class Listener
 * @author Richard <richard@sveil.com>
 * @package sveil\queue
 */
class Listener
{
    /**
     * @var string
     */
    protected $commandPath;

    /**
     * @var int
     */
    protected $sleep = 3;

    /**
     * @var int
     */
    protected $maxTries = 0;

    /**
     * @var string
     */
    protected $workerCommand;

    /**
     * @var \Closure|null
     */
    protected $outputHandler;

    /**
     * @param  string $commandPath
     */
    public function __construct($commandPath)
    {
        $this->commandPath   = $commandPath;
        $this->workerCommand = '"' . PHP_BINARY . '" sveil queue:work --queue="%s" --delay=%s --memory=%s --sleep=%s --tries=%s';
    }

    /**
     * @param  string $queue
     * @param  string $delay
     * @param  string $memory
     * @param  int    $timeout
     * @return void
     */
    public function listen($queue, $delay, $memory, $timeout = 60)
    {
        $process = $this->makeProcess($queue, $delay, $memory, $timeout);

        while (true) {
            $this->runProcess($process, $memory);
        }
    }

    /**
     * @param \sveil\Process $process
     * @param  int           $memory
     */
    public function runProcess(Process $process, $memory)
    {
        $process->run(function ($type, $line) {
            $this->handleWorkerOutput($type, $line);
        });

        if ($this->memoryExceeded($memory)) {
            $this->stop();
        }
    }

    /**
     * @param  string $queue
     * @param  int    $delay
     * @param  int    $memory
     * @param  int    $timeout
     * @return \sveil\Process
     */
    public function makeProcess($queue, $delay, $memory, $timeout)
    {
        $string  = $this->workerCommand;
        $command = sprintf($string, $queue, $delay, $memory, $this->sleep, $this->maxTries);

        return new Process($command, $this->commandPath, null, null, $timeout);
    }

    /**
     * @param  int    $type
     * @param  string $line
     * @return void
     */
    protected function handleWorkerOutput($type, $line)
    {
        if (isset($this->outputHandler)) {
            call_user_func($this->outputHandler, $type, $line);
        }
    }

    /**
     * @param  int $memoryLimit
     * @return bool
     */
    public function memoryExceeded($memoryLimit)
    {
        return (memory_get_usage() / 1024 / 1024) >= $memoryLimit;
    }

    /**
     * @return void
     */
    public function stop()
    {
        die;
    }

    /**
     * @param  \Closure $outputHandler
     * @return void
     */
    public function setOutputHandler(Closure $outputHandler)
    {
        $this->outputHandler = $outputHandler;
    }

    /**
     * @return int
     */
    public function getSleep()
    {
        return $this->sleep;
    }

    /**
     * @param  int $sleep
     * @return void
     */
    public function setSleep($sleep)
    {
        $this->sleep = $sleep;
    }

    /**
     * @param  int $tries
     * @return void
     */
    public function setMaxTries($tries)
    {
        $this->maxTries = $tries;
    }
}
