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

use sveil\console\Command;
use sveil\console\Input;
use sveil\console\input\Option;
use sveil\console\Output;
use sveil\queue\Listener;

/**
 * Class Listen
 * @author Richard <richard@sveil.com>
 * @package sveil\queue\command
 */
class Listen extends Command
{
    /** @var  Listener */
    protected $listener;

    public function configure()
    {
        $this->setName('queue:listen')
            ->addOption('queue', null, Option::VALUE_OPTIONAL, 'The queue to listen on', null)
            ->addOption('delay', null, Option::VALUE_OPTIONAL, 'Amount of time to delay failed jobs', 0)
            ->addOption('memory', null, Option::VALUE_OPTIONAL, 'The memory limit in megabytes', 128)
            ->addOption('timeout', null, Option::VALUE_OPTIONAL, 'Seconds a job may run before timing out', 60)
            ->addOption('sleep', null, Option::VALUE_OPTIONAL, 'Seconds to wait before checking queue for jobs', 3)
            ->addOption('tries', null, Option::VALUE_OPTIONAL, 'Number of times to attempt a job before logging it failed', 0)
            ->setDescription('Listen to a given queue');
    }

    public function initialize(Input $input, Output $output)
    {
        $this->listener = new Listener(getcwd());
        $this->listener->setSleep($input->getOption('sleep'));
        $this->listener->setMaxTries($input->getOption('tries'));
        $this->listener->setOutputHandler(function ($type, $line) use ($output) {
            $output->write($line);
        });
    }

    public function execute(Input $input, Output $output)
    {
        $delay   = $input->getOption('delay');
        $memory  = $input->getOption('memory');
        $timeout = $input->getOption('timeout');
        $queue   = $input->getOption('queue') ?: 'default';
        $this->listener->listen($queue, $delay, $memory, $timeout);
    }
}
