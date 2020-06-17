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

class CallQueuedHandler
{
    public function call(Job $job, array $data)
    {
        $command = unserialize($data['command']);
        call_user_func([$command, 'handle']);

        if (!$job->isDeletedOrReleased()) {
            $job->delete();
        }
    }

    public function failed(array $data)
    {
        $command = unserialize($data['command']);

        if (method_exists($command, 'failed')) {
            $command->failed();
        }
    }
}
