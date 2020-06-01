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

namespace sveil\model;

use sveil\Model;

class Pivot extends Model
{

    /** @var Model */
    public $parent;

    protected $autoWriteTimestamp = false;

    /**
     * 架构函数
     * @access public
     * @param  array|object  $data 数据
     * @param  Model         $parent 上级模型
     * @param  string        $table 中间数据表名
     */
    public function __construct($data = [], Model $parent = null, $table = '')
    {
        $this->parent = $parent;

        if (is_null($this->name)) {
            $this->name = $table;
        }

        parent::__construct($data);
    }

}
