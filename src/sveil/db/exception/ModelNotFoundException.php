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
class ModelNotFoundException extends DbException
{
    protected $model;

    /**
     * 构造方法
     * @access public
     * @param  string $message
     * @param  string $model
     * @param  array  $config
     */
    public function __construct($message, $model = '', array $config = [])
    {
        $this->message = $message;
        $this->model   = $model;
        $this->setData('Database Config', $config);
    }

    /**
     * 获取模型类名
     * @access public
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }
}
