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

namespace sveil\queue\job;

use sveil\queue\connector\Redis as RedisQueue;
use sveil\queue\Job;

class Redis extends Job
{
    /**
     * The redis queue instance.
     * @var RedisQueue
     */
    protected $redis;

    /**
     * The database job payload.
     * @var Object
     */
    protected $job;

    public function __construct(RedisQueue $redis, $job, $queue)
    {
        $this->job   = $job;
        $this->queue = $queue;
        $this->redis = $redis;
    }

    /**
     * Fire the job.
     * @return void
     */
    public function fire()
    {
        $this->resolveAndFire(json_decode($this->getRawBody(), true));
    }

    /**
     * Get the number of times the job has been attempted.
     * @return int
     */
    public function attempts()
    {
        return json_decode($this->job, true)['attempts'];
    }

    /**
     * Get the raw body string for the job.
     * @return string
     */
    public function getRawBody()
    {
        return $this->job;
    }

    /**
     * 删除任务
     * @return void
     */
    public function delete()
    {
        parent::delete();
        $this->redis->deleteReserved($this->queue, $this->job);
    }

    /**
     * 重新发布任务
     * @param  int $delay
     * @return void
     */
    public function release($delay = 0)
    {
        parent::release($delay);
        $this->delete();
        $this->redis->release($this->queue, $this->job, $delay, $this->attempts() + 1);
    }
}
