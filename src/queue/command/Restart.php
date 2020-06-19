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
use sveil\console\Output;
use sveil\facade\Cache;

/**
 * Class Restart
 * @author Richard <richard@sveil.com>
 * @package sveil\queue\command
 */
class Restart extends Command
{
    public function configure()
    {
        $this->setName('queue:restart')->setDescription('Restart queue worker daemons after their current job');
    }

    public function execute(Input $input, Output $output)
    {
        Cache::set('sveil:queue:restart', time());
        $output->writeln("<info>Broadcasting queue restart signal.</info>");
    }
}
