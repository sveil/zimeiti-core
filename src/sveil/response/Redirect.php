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
 * Class Redirect
 * @author Richard <richard@sveil.com>
 * @package sveil\response
 */
class Redirect extends Response
{
    protected $options = [];
    // URL参数
    protected $params = [];

    public function __construct($data = '', $code = 302, array $header = [], array $options = [])
    {
        parent::__construct($data, $code, $header, $options);
        $this->cacheControl('no-cache,must-revalidate');
    }

    /**
     * 处理数据
     * @access protected
     * @param  mixed $data 要处理的数据
     * @return mixed
     */
    protected function output($data)
    {
        $this->header['Location'] = $this->getTargetUrl();

        return;
    }

    /**
     * 重定向传值（通过Session）
     * @access protected
     * @param  string|array  $name 变量名或者数组
     * @param  mixed         $value 值
     * @return $this
     */
    public function with($name, $value = null)
    {
        $session = $this->app['session'];

        if (is_array($name)) {
            foreach ($name as $key => $val) {
                $session->flash($key, $val);
            }
        } else {
            $session->flash($name, $value);
        }

        return $this;
    }

    /**
     * 获取跳转地址
     * @access public
     * @return string
     */
    public function getTargetUrl()
    {
        if (strpos($this->data, '://') || (0 === strpos($this->data, '/') && empty($this->params))) {
            return $this->data;
        } else {
            return $this->app['url']->build($this->data, $this->params);
        }
    }

    public function params($params = [])
    {
        $this->params = $params;

        return $this;
    }

    /**
     * 记住当前url后跳转
     * @access public
     * @param string $url 指定记住的url
     * @return $this
     */
    public function remember($url = null)
    {
        $this->app['session']->set('redirect_url', $url ?: $this->app['request']->url());

        return $this;
    }

    /**
     * 跳转到上次记住的url
     * @access public
     * @param  string  $url 闪存数据不存在时的跳转地址
     * @return $this
     */
    public function restore($url = null)
    {
        $session = $this->app['session'];

        if ($session->has('redirect_url')) {
            $this->data = $session->get('redirect_url');
            $session->delete('redirect_url');
        } elseif ($url) {
            $this->data = $url;
        }

        return $this;
    }
}