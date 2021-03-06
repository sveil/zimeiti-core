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
 * Class Route
 * @author Richard <richard@sveil.com>
 * @package sveil\facade
 * @see \sveil\Route
 * @mixin \sveil\Route
 * @method \sveil\route\Domain domain(mixed $name, mixed $rule = '', array $option = [], array $pattern = []) static 注册域名路由
 * @method \sveil\Route pattern(mixed $name, string $rule = '') static 注册变量规则
 * @method \sveil\Route option(mixed $name, mixed $value = '') static 注册路由参数
 * @method \sveil\Route bind(string $bind) static 设置路由绑定
 * @method mixed getBind(string $bind) static 读取路由绑定
 * @method \sveil\Route name(string $name) static 设置当前路由标识
 * @method mixed getName(string $name) static 读取路由标识
 * @method void setName(string $name) static 批量导入路由标识
 * @method void import(array $rules, string $type = '*') static 导入配置文件的路由规则
 * @method \sveil\route\RuleItem rule(string $rule, mixed $route, string $method = '*', array $option = [], array $pattern = []) static 注册路由规则
 * @method void rules(array $rules, string $method = '*', array $option = [], array $pattern = []) static 批量注册路由规则
 * @method \sveil\route\RuleGroup group(string|array $name, mixed $route, string $method = '*', array $option = [], array $pattern = []) static 注册路由分组
 * @method \sveil\route\RuleItem any(string $rule, mixed $route, array $option = [], array $pattern = []) static 注册路由
 * @method \sveil\route\RuleItem get(string $rule, mixed $route, array $option = [], array $pattern = []) static 注册路由
 * @method \sveil\route\RuleItem post(string $rule, mixed $route, array $option = [], array $pattern = []) static 注册路由
 * @method \sveil\route\RuleItem put(string $rule, mixed $route, array $option = [], array $pattern = []) static 注册路由
 * @method \sveil\route\RuleItem delete(string $rule, mixed $route, array $option = [], array $pattern = []) static 注册路由
 * @method \sveil\route\RuleItem patch(string $rule, mixed $route, array $option = [], array $pattern = []) static 注册路由
 * @method \sveil\route\Resource resource(string $rule, mixed $route, array $option = [], array $pattern = []) static 注册资源路由
 * @method \sveil\Route controller(string $rule, mixed $route, array $option = [], array $pattern = []) static 注册控制器路由
 * @method \sveil\Route alias(string $rule, mixed $route, array $option = [], array $pattern = []) static 注册别名路由
 * @method \sveil\Route setMethodPrefix(mixed $method, string $prefix = '') static 设置不同请求类型下面的方法前缀
 * @method \sveil\Route rest(string $name, array $resource = []) static rest方法定义和修改
 * @method \sveil\Route\RuleItem miss(string $route, string $method = '*', array $option = []) static 注册未匹配路由规则后的处理
 * @method \sveil\Route\RuleItem auto(string $route) static 注册一个自动解析的URL路由
 * @method \sveil\Route\Dispatch check(string $url, string $depr = '/', bool $must = false, bool $completeMatch = false) static 检测URL路由
 */
class Route extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'route';
    }
}
