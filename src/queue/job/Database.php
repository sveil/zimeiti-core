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

use sveil\queue\connector\Database as DbQueue;
use sveil\queue\Job;

/**
 * Class Database
 * @author Richard <richard@sveil.com>
 * @package sveil\queue\job
 */
class Database extends Job
{
    /**
     * The database queue instance.
     * @var DbQueue
     */
    protected $database;

    /**
     * The database job payload.
     * @var Object
     */
    protected $job;

    public function __construct(DbQueue $database, $job, $queue)
    {
        $this->job           = $job;
        $this->queue         = $queue;
        $this->database      = $database;
        $this->job->attempts = $this->job->attempts + 1;
    }

    /**
     * 执行任务
     * @return void
     */
    public function fire()
    {
        $this->resolveAndFire(json_decode($this->job->payload, true));
    }

    /**
     * 删除任务
     * @return void
     */
    public function delete()
    {
        parent::delete();
        $this->database->deleteReserved($this->job->id);
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
        $this->database->release($this->queue, $this->job, $delay);
    }

    /**
     * 获取当前任务尝试次数
     * @return int
     */
    public function attempts()
    {
        return (int) $this->job->attempts;
    }

    /**
     * Get the raw body string for the job.
     * @return string
     */
    public function getRawBody()
    {
        return $this->job->payload;
    }
}
