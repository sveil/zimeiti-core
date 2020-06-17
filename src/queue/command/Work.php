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

namespace sveil\queue\command;

use Exception;
use sveil\console\Command;
use sveil\console\Input;
use sveil\console\input\Option;
use sveil\console\Output;
use sveil\exception\Handle;
use sveil\exception\ThrowableError;
use sveil\facade\Cache;
use sveil\facade\Config;
use sveil\facade\Hook;
use sveil\queue\Job;
use sveil\queue\Worker;
use Throwable;

class Work extends Command
{
    /**
     * The queue worker instance.
     * @var \sveil\queue\Worker
     */
    protected $worker;

    protected function initialize(Input $input, Output $output)
    {
        $this->worker = new Worker();
    }

    protected function configure()
    {
        $this->setName('queue:work')
            ->addOption('queue', null, Option::VALUE_OPTIONAL, 'The queue to listen on')
            ->addOption('daemon', null, Option::VALUE_NONE, 'Run the worker in daemon mode')
            ->addOption('delay', null, Option::VALUE_OPTIONAL, 'Amount of time to delay failed jobs', 0)
            ->addOption('force', null, Option::VALUE_NONE, 'Force the worker to run even in maintenance mode')
            ->addOption('memory', null, Option::VALUE_OPTIONAL, 'The memory limit in megabytes', 128)
            ->addOption('sleep', null, Option::VALUE_OPTIONAL, 'Number of seconds to sleep when no job is available', 3)
            ->addOption('tries', null, Option::VALUE_OPTIONAL, 'Number of times to attempt a job before logging it failed', 0)
            ->setDescription('Process the next job on a queue');
    }

    /**
     * Execute the console command.
     * @param Input  $input
     * @param Output $output
     * @return int|null|void
     */
    public function execute(Input $input, Output $output)
    {
        $queue  = $input->getOption('queue');
        $delay  = $input->getOption('delay');
        $memory = $input->getOption('memory');

        if ($input->getOption('daemon')) {
            Hook::listen('worker_daemon_start', $queue);
            $this->daemon(
                $queue, $delay, $memory,
                $input->getOption('sleep'), $input->getOption('tries')
            );
        } else {
            $response = $this->worker->pop($queue, $delay, $input->getOption('sleep'), $input->getOption('tries'));
            $this->output($response);
        }
    }

    protected function output($response)
    {
        if (!is_null($response['job'])) {
            /** @var Job $job */
            $job = $response['job'];
            if ($response['failed']) {
                $this->output->writeln('<error>Failed:</error> ' . $job->getName());
            } else {
                $this->output->writeln('<info>Processed:</info> ' . $job->getName());
            }
        }
    }

    /**
     * 启动一个守护进程执行任务.
     * @param  string $queue
     * @param  int    $delay
     * @param  int    $memory
     * @param  int    $sleep
     * @param  int    $maxTries
     * @return array
     */
    protected function daemon($queue = null, $delay = 0, $memory = 128, $sleep = 3, $maxTries = 0)
    {
        $lastRestart = $this->getTimestampOfLastQueueRestart();

        while (true) {
            $this->runNextJobForDaemon(
                $queue, $delay, $sleep, $maxTries
            );

            if ($this->memoryExceeded($memory)) {
                Hook::listen('worker_memory_exceeded', $queue);
                $this->stop();
            }

            if ($this->queueShouldRestart($lastRestart)) {
                Hook::listen('worker_queue_restart', $queue);
                $this->stop();
            }
        }
    }

    /**
     * 以守护进程的方式执行下个任务.
     * @param  string $queue
     * @param  int    $delay
     * @param  int    $sleep
     * @param  int    $maxTries
     * @return void
     */
    protected function runNextJobForDaemon($queue, $delay, $sleep, $maxTries)
    {
        try {
            $response = $this->worker->pop($queue, $delay, $sleep, $maxTries);
            $this->output($response);
        } catch (Exception $e) {
            $this->getExceptionHandler()->report($e);
        } catch (Throwable $e) {
            $this->getExceptionHandler()->report(new ThrowableError($e));
        }
    }

    /**
     * 获取上次重启守护进程的时间
     * @return int|null
     */
    protected function getTimestampOfLastQueueRestart()
    {
        return Cache::get('sveil:queue:restart');
    }

    /**
     * 检查是否要重启守护进程
     * @param  int|null $lastRestart
     * @return bool
     */
    protected function queueShouldRestart($lastRestart)
    {
        return $this->getTimestampOfLastQueueRestart() != $lastRestart;
    }

    /**
     * 检查内存是否超出
     * @param  int $memoryLimit
     * @return bool
     */
    protected function memoryExceeded($memoryLimit)
    {
        return (memory_get_usage() / 1024 / 1024) >= $memoryLimit;
    }

    /**
     * 获取异常处理实例
     * @return \sveil\exception\Handle
     */
    protected function getExceptionHandler()
    {
        static $handle;

        if (!$handle) {
            if ($class = Config::get('exception_handle')) {
                if (class_exists($class) && is_subclass_of($class, "\\sveil\\exception\\Handle")) {
                    $handle = new $class;
                }
            }

            if (!$handle) {
                $handle = new Handle();
            }
        }

        return $handle;
    }

    /**
     * 停止执行任务的守护进程.
     * @return void
     */
    public function stop()
    {
        die;
    }
}
