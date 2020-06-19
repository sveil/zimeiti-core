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
use sveil\console\input\Argument;
use sveil\console\input\Option;
use sveil\console\Output;
use sveil\facade\Url;
use sveil\Queue;

/**
 * Class Subscribe
 * @author Richard <richard@sveil.com>
 * @package sveil\queue\command
 */
class Subscribe extends Command
{
    public function configure()
    {
        $this->setName('queue:subscribe')
            ->setDescription('Subscribe a URL to an push queue')
            ->addArgument('name', Argument::REQUIRED, 'name')
            ->addArgument('url', Argument::REQUIRED, 'The URL to be subscribed.')
            ->addArgument('queue', Argument::OPTIONAL, 'The URL to be subscribed.')
            ->addOption('option', null, Option::VALUE_IS_ARRAY | Option::VALUE_OPTIONAL, 'the options');
    }

    public function execute(Input $input, Output $output)
    {
        $url = $input->getArgument('url');

        if (!preg_match('/^https?:\/\//', $url)) {
            $url = Url::build($url);
        }

        Queue::subscribe($input->getArgument('name'), $url, $input->getArgument('queue'), $input->getOption('option'));
        $output->write('<info>Queue subscriber added:</info> <comment>' . $input->getArgument('url') . '</comment>');
    }
}
