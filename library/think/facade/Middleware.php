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

namespace sveil\think\facade;

use sveil\think\Facade;

/**
 * @see \think\Middleware
 * @mixin \think\Middleware
 * @method void import(array $middlewares = []) static 批量设置中间件
 * @method void add(mixed $middleware) static 添加中间件到队列
 * @method void unshift(mixed $middleware) static 添加中间件到队列开头
 * @method array all() static 获取中间件队列
 * @method \think\Response dispatch(\think\Request $request) static 执行中间件调度
 */
class Middleware extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'middleware';
    }
}
