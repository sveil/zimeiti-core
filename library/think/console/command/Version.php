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

namespace sveil\think\console\command;

use sveil\think\console\Command;
use sveil\think\console\Input;
use sveil\think\console\Output;
use sveil\think\facade\App;

class Version extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('version')
            ->setDescription('show thinkphp framework version');
    }

    protected function execute(Input $input, Output $output)
    {
        $output->writeln('v' . App::version());
    }
}
