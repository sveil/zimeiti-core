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

use sveil\queue\Job;

class Sync extends Job
{
    /**
     * The queue message data.
     * @var string
     */
    protected $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Fire the job.
     * @return void
     */
    public function fire()
    {
        $this->resolveAndFire(json_decode($this->payload, true));
    }

    /**
     * Get the number of times the job has been attempted.
     * @return int
     */
    public function attempts()
    {
        return 1;
    }

    /**
     * Get the raw body string for the job.
     * @return string
     */
    public function getRawBody()
    {
        return $this->payload;
    }
}
