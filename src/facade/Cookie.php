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
 * Class Cookie
 * @author Richard <richard@sveil.com>
 * @package sveil\facade
 * @see \sveil\Cookie
 * @mixin \sveil\Cookie
 * @method void init(array $config = []) static 初始化
 * @method bool has(string $name,string $prefix = null) static 判断Cookie数据
 * @method mixed prefix(string $prefix = '') static 设置或者获取cookie作用域（前缀）
 * @method mixed get(string $name,string $prefix = null) static Cookie获取
 * @method mixed set(string $name, mixed $value = null, mixed $option = null) static 设置Cookie
 * @method void forever(string $name, mixed $value = null, mixed $option = null) static 永久保存Cookie数据
 * @method void delete(string $name, string $prefix = null) static Cookie删除
 * @method void clear($prefix = null) static Cookie清空
 */
class Cookie extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'cookie';
    }
}
