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

namespace sveil\response;

use sveil\Response;

/**
 * Class View
 * @author Richard <richard@sveil.com>
 * @package sveil\response
 */
class View extends Response
{
    // 输出参数
    protected $options = [];
    protected $vars    = [];
    protected $config  = [];
    protected $filter;
    protected $contentType = 'text/html';

    /**
     * 是否内容渲染
     * @var bool
     */
    protected $isContent = false;

    /**
     * 处理数据
     * @access protected
     * @param  mixed $data 要处理的数据
     * @return mixed
     */
    protected function output($data)
    {
        // 渲染模板输出
        return $this->app['view']
            ->filter($this->filter)
            ->fetch($data, $this->vars, $this->config, $this->isContent);
    }

    /**
     * 设置是否为内容渲染
     * @access public
     * @param  bool $content
     * @return $this
     */
    public function isContent($content = true)
    {
        $this->isContent = $content;
        return $this;
    }

    /**
     * 获取视图变量
     * @access public
     * @param  string $name 模板变量
     * @return mixed
     */
    public function getVars($name = null)
    {
        if (is_null($name)) {
            return $this->vars;
        } else {
            return isset($this->vars[$name]) ? $this->vars[$name] : null;
        }
    }

    /**
     * 模板变量赋值
     * @access public
     * @param  mixed $name  变量名
     * @param  mixed $value 变量值
     * @return $this
     */
    public function assign($name, $value = '')
    {
        if (is_array($name)) {
            $this->vars = array_merge($this->vars, $name);
        } else {
            $this->vars[$name] = $value;
        }

        return $this;
    }

    public function config($config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * 视图内容过滤
     * @access public
     * @param callable $filter
     * @return $this
     */
    public function filter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * 检查模板是否存在
     * @access private
     * @param  string|array  $name 参数名
     * @return bool
     */
    public function exists($name)
    {
        return $this->app['view']->exists($name);
    }
}
