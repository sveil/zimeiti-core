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

namespace sveil;

// 载入Loader类
require __DIR__ . 'sveil/Loader.php';

// 注册自动加载
Loader::register();

// 注册错误和异常处理机制
Error::register();

// 注册类库别名
Loader::addClassAlias([
    'App'      => facade\App::class,
    'Build'    => facade\Build::class,
    'Cache'    => facade\Cache::class,
    'Config'   => facade\Config::class,
    'Cookie'   => facade\Cookie::class,
    'Db'       => Db::class,
    'Debug'    => facade\Debug::class,
    'Env'      => facade\Env::class,
    'Facade'   => Facade::class,
    'Hook'     => facade\Hook::class,
    'Lang'     => facade\Lang::class,
    'Log'      => facade\Log::class,
    'Request'  => facade\Request::class,
    'Response' => facade\Response::class,
    'Route'    => facade\Route::class,
    'Session'  => facade\Session::class,
    'Url'      => facade\Url::class,
    'Validate' => facade\Validate::class,
    'View'     => facade\View::class,
]);
