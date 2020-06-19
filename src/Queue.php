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

namespace sveil;

use sveil\facade\Config;
use sveil\helper\Str;
use sveil\queue\Connector;

/**
 * Class Queue
 * @author Richard <richard@sveil.com>
 * @package sveil
 * @method static push($job, $data = '', $queue = null)
 * @method static later($delay, $job, $data = '', $queue = null)
 * @method static pop($queue = null)
 * @method static marshal()
 */
class Queue
{
    /** @var Connector */
    protected static $connector;

    private static function buildConnector()
    {
        $options = Config::pull('queue');
        $type    = !empty($options['connector']) ? $options['connector'] : 'Sync';

        if (!isset(self::$connector)) {
            $class           = false !== strpos($type, '\\') ? $type : '\\sveil\\queue\\connector\\' . Str::studly($type);
            self::$connector = new $class($options);
        }

        return self::$connector;
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([self::buildConnector(), $name], $arguments);
    }
}
