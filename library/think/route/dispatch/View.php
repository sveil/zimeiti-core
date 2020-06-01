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

namespace sveil\route\dispatch;

use sveil\Response;
use sveil\route\Dispatch;

class View extends Dispatch
{
    public function exec()
    {
        // 渲染模板输出
        $vars = array_merge($this->request->param(), $this->param);

        return Response::create($this->dispatch, 'view')->assign($vars);
    }
}
