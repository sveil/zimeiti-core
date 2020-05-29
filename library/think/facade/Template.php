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
 * @see \think\Template
 * @mixin \think\Template
 * @method void assign(mixed $name, mixed $value = '') static 模板变量赋值
 * @method mixed get(string $name = '') static 获取模板变量
 * @method void fetch(string $template, array $vars = [], array $config = []) static 渲染模板文件
 * @method void display(string $content, array $vars = [], array $config = []) static 渲染模板内容
 * @method mixed layout(string $name, string $replace = '') static 设置模板布局
 */
class Template extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'template';
    }
}
