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

/**
 * Class Response
 * @author Richard <richard@sveil.com>
 * @package sveil\route\dispatch
 */
class Response extends Dispatch
{
    public function exec()
    {
        return $this->dispatch;
    }
}
