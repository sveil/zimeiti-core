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

trait Queueable
{

    /** @var string 队列名称 */
    public $queue;

    /** @var integer 延迟时间 */
    public $delay;

    /**
     * 设置队列名
     * @param $queue
     * @return $this
     */
    public function queue($queue)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * 设置延迟时间
     * @param $delay
     * @return $this
     */
    public function delay($delay)
    {
        $this->delay = $delay;

        return $this;
    }
}
