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

namespace sveil\facade;

use sveil\Facade;

/**
 * Class Debug
 * @author Richard <richard@sveil.com>
 * @package sveil\facade
 * @see \sveil\Debug
 * @mixin \sveil\Debug
 * @method void remark(string $name, mixed $value = '') static 记录时间（微秒）和内存使用情况
 * @method int getRangeTime(string $start, string $end, mixed $dec = 6) static 统计某个区间的时间（微秒）使用情况
 * @method int getUseTime(int $dec = 6) static 统计从开始到统计时的时间（微秒）使用情况
 * @method string getThroughputRate(string $start, string $end, mixed $dec = 6) static 获取当前访问的吞吐率情况
 * @method string getRangeMem(string $start, string $end, mixed $dec = 2) static 记录区间的内存使用情况
 * @method int getUseMem(int $dec = 2) static 统计从开始到统计时的内存使用情况
 * @method string getMemPeak(string $start, string $end, mixed $dec = 2) static 统计区间的内存峰值情况
 * @method mixed getFile(bool $detail = false) static 获取文件加载信息
 * @method mixed dump(mixed $var, bool $echo = true, string $label = null, int $flags = ENT_SUBSTITUTE) static 浏览器友好的变量输出
 */
class Debug extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'debug';
    }
}
