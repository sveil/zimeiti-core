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

namespace sveil\queue\connector;

use Exception;
use sveil\queue\Connector;
use sveil\queue\job\Sync as SyncJob;
use Throwable;

/**
 * Class Sync
 * @author Richard <richard@sveil.com>
 * @package sveil\queue\connector
 */
class Sync extends Connector
{
    public function push($job, $data = '', $queue = null)
    {
        $queueJob = $this->resolveJob($this->createPayload($job, $data, $queue));

        try {
            set_time_limit(0);
            $queueJob->fire();
        } catch (Exception $e) {
            $queueJob->failed();
            throw $e;
        } catch (Throwable $e) {
            $queueJob->failed();
            throw $e;
        }

        return 0;
    }

    public function later($delay, $job, $data = '', $queue = null)
    {
        return $this->push($job, $data, $queue);
    }

    public function pop($queue = null)
    {}

    protected function resolveJob($payload)
    {
        return new SyncJob($payload);
    }
}
