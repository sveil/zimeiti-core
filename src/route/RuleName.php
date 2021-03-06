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

namespace sveil\route;

/**
 * Class RuleName
 * @author Richard <richard@sveil.com>
 * @package sveil\route
 */
class RuleName
{
    protected $item = [];
    protected $rule = [];

    /**
     * 注册路由标识
     * @access public
     * @param  string   $name      路由标识
     * @param  array    $value     路由规则
     * @param  bool     $first     是否置顶
     * @return void
     */
    public function set($name, $value, $first = false)
    {
        if ($first && isset($this->item[$name])) {
            array_unshift($this->item[$name], $value);
        } else {
            $this->item[$name][] = $value;
        }
    }

    /**
     * 注册路由规则
     * @access public
     * @param  string   $rule      路由规则
     * @param  RuleItem $route     路由
     * @return void
     */
    public function setRule($rule, $route)
    {
        $this->rule[$route->getDomain()][$rule][$route->getMethod()] = $route;
    }

    /**
     * 根据路由规则获取路由对象（列表）
     * @access public
     * @param  string   $name      路由标识
     * @param  string   $domain   域名
     * @return array
     */
    public function getRule($rule, $domain = null)
    {
        return isset($this->rule[$domain][$rule]) ? $this->rule[$domain][$rule] : [];
    }

    /**
     * 获取全部路由列表
     * @access public
     * @param  string   $domain   域名
     * @return array
     */
    public function getRuleList($domain = null)
    {
        $list = [];

        foreach ($this->rule as $ruleDomain => $rules) {
            foreach ($rules as $rule => $items) {
                foreach ($items as $item) {
                    $val['domain'] = $ruleDomain;

                    foreach (['method', 'rule', 'name', 'route', 'pattern', 'option'] as $param) {
                        $call        = 'get' . $param;
                        $val[$param] = $item->$call();
                    }

                    $list[$ruleDomain][] = $val;
                }
            }
        }

        if ($domain) {
            return isset($list[$domain]) ? $list[$domain] : [];
        }

        return $list;
    }

    /**
     * 导入路由标识
     * @access public
     * @param  array   $name      路由标识
     * @return void
     */
    public function import($item)
    {
        $this->item = $item;
    }

    /**
     * 根据路由标识获取路由信息（用于URL生成）
     * @access public
     * @param  string   $name      路由标识
     * @param  string   $domain   域名
     * @return array|null
     */
    public function get($name = null, $domain = null, $method = '*')
    {
        if (is_null($name)) {
            return $this->item;
        }

        $name   = strtolower($name);
        $method = strtolower($method);

        if (isset($this->item[$name])) {
            if (is_null($domain)) {
                $result = $this->item[$name];
            } else {
                $result = [];
                foreach ($this->item[$name] as $item) {
                    if ($item[2] == $domain && ('*' == $item[4] || $method == $item[4])) {
                        $result[] = $item;
                    }
                }
            }
        } else {
            $result = null;
        }

        return $result;
    }

    /**
     * 清空路由规则
     * @access public
     * @return void
     */
    public function clear()
    {
        $this->item = [];
        $this->rule = [];
    }
}
