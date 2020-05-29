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

namespace sveil\think;

use InvalidArgumentException;
use LogicException;
use sveil\think\exception\HttpResponseException;

class Middleware
{
    protected $queue = [];
    protected $app;
    protected $config = [
        'default_namespace' => 'app\\http\\middleware\\',
    ];

    public function __construct(App $app, array $config = [])
    {
        $this->app    = $app;
        $this->config = array_merge($this->config, $config);
    }

    public static function __make(App $app, Config $config)
    {
        return new static($app, $config->pull('middleware'));
    }

    public function setConfig(array $config)
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * 导入中间件
     * @access public
     * @param  array  $middlewares
     * @param  string $type  中间件类型
     */
    public function import(array $middlewares = [], $type = 'route')
    {
        foreach ($middlewares as $middleware) {
            $this->add($middleware, $type);
        }
    }

    /**
     * 注册中间件
     * @access public
     * @param  mixed  $middleware
     * @param  string $type  中间件类型
     */
    public function add($middleware, $type = 'route')
    {
        if (is_null($middleware)) {
            return;
        }

        $middleware = $this->buildMiddleware($middleware, $type);

        if ($middleware) {
            $this->queue[$type][] = $middleware;
        }
    }

    /**
     * 注册控制器中间件
     * @access public
     * @param  mixed  $middleware
     */
    public function controller($middleware)
    {
        return $this->add($middleware, 'controller');
    }

    /**
     * 移除中间件
     * @access public
     * @param  mixed  $middleware
     * @param  string $type  中间件类型
     */
    public function unshift($middleware, $type = 'route')
    {
        if (is_null($middleware)) {
            return;
        }

        $middleware = $this->buildMiddleware($middleware, $type);

        if ($middleware) {
            array_unshift($this->queue[$type], $middleware);
        }
    }

    /**
     * 获取注册的中间件
     * @access public
     * @param  string $type  中间件类型
     */
    public function all($type = 'route')
    {
        return $this->queue[$type] ?: [];
    }

    /**
     * 清除中间件
     * @access public
     */
    public function clear()
    {
        $this->queue = [];
    }

    /**
     * 中间件调度
     * @access public
     * @param  Request  $request
     * @param  string   $type  中间件类型
     */
    public function dispatch(Request $request, $type = 'route')
    {
        return call_user_func($this->resolve($type), $request);
    }

    /**
     * 解析中间件
     * @access protected
     * @param  mixed  $middleware
     * @param  string $type  中间件类型
     */
    protected function buildMiddleware($middleware, $type = 'route')
    {
        if (is_array($middleware)) {
            list($middleware, $param) = $middleware;
        }

        if ($middleware instanceof \Closure) {
            return [$middleware, isset($param) ? $param : null];
        }

        if (!is_string($middleware)) {
            throw new InvalidArgumentException('The middleware is invalid');
        }

        if (false === strpos($middleware, '\\')) {
            if (isset($this->config[$middleware])) {
                $middleware = $this->config[$middleware];
            } else {
                $middleware = $this->config['default_namespace'] . $middleware;
            }
        }

        if (is_array($middleware)) {
            return $this->import($middleware, $type);
        }

        if (strpos($middleware, ':')) {
            list($middleware, $param) = explode(':', $middleware, 2);
        }

        return [[$this->app->make($middleware), 'handle'], isset($param) ? $param : null];
    }

    protected function resolve($type = 'route')
    {
        return function (Request $request) use ($type) {

            $middleware = array_shift($this->queue[$type]);

            if (null === $middleware) {
                throw new InvalidArgumentException('The queue was exhausted, with no response returned');
            }

            list($call, $param) = $middleware;

            try {
                $response = call_user_func_array($call, [$request, $this->resolve($type), $param]);
            } catch (HttpResponseException $exception) {
                $response = $exception->getResponse();
            }

            if (!$response instanceof Response) {
                throw new LogicException('The middleware must return Response instance');
            }

            return $response;
        };
    }

    public function __debugInfo()
    {
        $data = get_object_vars($this);
        unset($data['app']);

        return $data;
    }
}
