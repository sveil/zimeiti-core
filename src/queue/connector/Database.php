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

use sveil\Db;
use sveil\queue\Connector;
use sveil\queue\job\Database as DatabaseJob;

class Database extends Connector
{
    protected $options = [
        'expire'  => 60,
        'default' => 'default',
        'table'   => 'jobs',
    ];

    public function __construct(array $options)
    {
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
    }

    public function push($job, $data = '', $queue = null)
    {
        return $this->pushToDatabase(0, $queue, $this->createPayload($job, $data));
    }

    public function later($delay, $job, $data = '', $queue = null)
    {
        return $this->pushToDatabase($delay, $queue, $this->createPayload($job, $data));
    }

    public function pop($queue = null)
    {
        $queue = $this->getQueue($queue);

        if (!is_null($this->options['expire'])) {
            $this->releaseJobsThatHaveBeenReservedTooLong($queue);
        }

        if ($job = $this->getNextAvailableJob($queue)) {
            $this->markJobAsReserved($job->id);
            Db::commit();

            return new DatabaseJob($this, $job, $queue);
        }

        Db::commit();
    }

    /**
     * 重新发布任务
     * @param  string    $queue
     * @param  \StdClass $job
     * @param  int       $delay
     * @return mixed
     */
    public function release($queue, $job, $delay)
    {
        return $this->pushToDatabase($delay, $queue, $job->payload, $job->attempts);
    }

    /**
     * Push a raw payload to the database with a given delay.
     * @param  \DateTime|int $delay
     * @param  string|null   $queue
     * @param  string        $payload
     * @param  int           $attempts
     * @return mixed
     */
    protected function pushToDatabase($delay, $queue, $payload, $attempts = 0)
    {
        return Db::name($this->options['table'])->insert([
            'queue'        => $this->getQueue($queue),
            'payload'      => $payload,
            'attempts'     => $attempts,
            'reserved'     => 0,
            'reserved_at'  => null,
            'available_at' => time() + $delay,
            'created_at'   => time(),
        ]);
    }

    /**
     * 获取下个有效任务
     * @param  string|null $queue
     * @return \StdClass|null
     */
    protected function getNextAvailableJob($queue)
    {
        Db::startTrans();
        $job = Db::name($this->options['table'])
            ->lock(true)
            ->where('queue', $this->getQueue($queue))
            ->where('reserved', 0)
            ->where('available_at', '<=', time())
            ->order('id', 'asc')
            ->find();

        return $job ? (object) $job : null;
    }

    /**
     * 标记任务正在执行
     * @param  string $id
     * @return void
     */
    protected function markJobAsReserved($id)
    {
        Db::name($this->options['table'])->where('id', $id)->update([
            'reserved'    => 1,
            'reserved_at' => time(),
        ]);
    }

    /**
     * 重新发布超时的任务
     * @param  string $queue
     * @return void
     */
    protected function releaseJobsThatHaveBeenReservedTooLong($queue)
    {
        $expired = time() - $this->options['expire'];
        Db::name($this->options['table'])
            ->where('queue', $this->getQueue($queue))
            ->where('reserved', 1)
            ->where('reserved_at', '<=', $expired)
            ->update([
                'reserved'    => 0,
                'reserved_at' => null,
                'attempts'    => ['inc', 1],
            ]);
    }

    /**
     * 删除任务
     * @param  string $id
     * @return void
     */
    public function deleteReserved($id)
    {
        Db::name($this->options['table'])->delete($id);
    }

    protected function getQueue($queue)
    {
        return $queue ?: $this->options['default'];
    }
}
