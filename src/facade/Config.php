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
 * Class Config
 * @author Richard <richard@sveil.com>
 * @package sveil\facade
 * @see \sveil\Config
 * @mixin \sveil\Config
 * @method array load(string $file, string $name = '') static 加载配置文件
 * @method bool has(string $name) static 检测配置是否存在
 * @method array pull(string $name) static 获取一级配置参数
 * @method mixed get(string $name,mixed $default = null) static 获取配置参数
 * @method array set(mixed $name, mixed $value = null) static 设置配置参数
 * @method array reset(string $name ='') static 重置配置参数
 * @method void remove(string $name = '') static 移除配置
 * @method void setYaconf(mixed $yaconf) static 设置开启Yaconf 或者指定配置文件名
 */
class Config extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'config';
    }
}
