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
 * @see \sveil\View
 * @mixin \sveil\View
 * @method \sveil\View init(mixed $engine = [], array $replace = []) static 初始化
 * @method \sveil\View share(mixed $name, mixed $value = '') static 模板变量静态赋值
 * @method \sveil\View assign(mixed $name, mixed $value = '') static 模板变量赋值
 * @method \sveil\View config(mixed $name, mixed $value = '') static 配置模板引擎
 * @method \sveil\View exists(mixed $name) static 检查模板是否存在
 * @method \sveil\View filter(Callable $filter) static 视图内容过滤
 * @method \sveil\View engine(mixed $engine = []) static 设置当前模板解析的引擎
 * @method string fetch(string $template = '', array $vars = [], array $config = [], bool $renderContent = false) static 解析和获取模板内容
 * @method string display(string $content = '', array $vars = [], array $config = []) static 渲染内容输出
 */
class View extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'view';
    }
}
