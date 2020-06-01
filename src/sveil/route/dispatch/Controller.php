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

use sveil\route\Dispatch;

class Controller extends Dispatch
{
    public function exec()
    {
        // 执行控制器的操作方法
        $vars = array_merge($this->request->param(), $this->param);

        return $this->app->action(
            $this->dispatch, $vars,
            $this->rule->getConfig('url_controller_layer'),
            $this->rule->getConfig('controller_suffix')
        );
    }

}
