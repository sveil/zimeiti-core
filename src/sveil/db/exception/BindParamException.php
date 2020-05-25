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

namespace sveil\db\exception;

use sveil\exception\DbException;

/**
 * PDO参数绑定异常
 * @author Richard <richard@sveil.com>
 * @package sveil\db\exception
 */
class BindParamException extends DbException
{
    /**
     * BindParamException constructor.
     * @access public
     * @param  string $message
     * @param  array  $config
     * @param  string $sql
     * @param  array    $bind
     * @param  int    $code
     */
    public function __construct($message, $config, $sql, $bind, $code = 10502)
    {
        $this->setData('Bind Param', $bind);
        parent::__construct($message, $config, $sql, $code);
    }
}
