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
 * Class Build
 * @author Richard <richard@sveil.com>
 * @package sveil\facade
 * @see \sveil\Build
 * @mixin \sveil\Build
 * @method void run(array $build = [], string $namespace = 'apps', bool $suffix = false) static 根据传入的build资料创建目录和文件
 * @method void module(string $module = '', array $list = [], string $namespace = 'apps', bool $suffix = false) static 创建模块
 */
class Build extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'build';
    }
}
