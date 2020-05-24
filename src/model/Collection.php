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

use sveil\Collection as BaseCollection;
use sveil\Model;

/**
 * Class Collection
 * @author Richard <richard@sveil.com>
 * @package sveil\model
 */
class Collection extends BaseCollection
{
    /**
     * 延迟预载入关联查询
     * @access public
     * @param  mixed $relation 关联
     * @return $this
     */
    public function load($relation)
    {
        if (!$this->isEmpty()) {
            $item = current($this->items);
            $item->eagerlyResultSet($this->items, $relation);
        }

        return $this;
    }

    /**
     * 设置需要隐藏的输出属性
     * @access public
     * @param  array $hidden   属性列表
     * @param  bool  $override 是否覆盖
     * @return $this
     */
    public function hidden($hidden = [], $override = false)
    {
        $this->each(function ($model) use ($hidden, $override) {
            /** @var Model $model */
            $model->hidden($hidden, $override);
        });

        return $this;
    }

    /**
     * 设置需要输出的属性
     * @access public
     * @param  array $visible
     * @param  bool  $override 是否覆盖
     * @return $this
     */
    public function visible($visible = [], $override = false)
    {
        $this->each(function ($model) use ($visible, $override) {
            /** @var Model $model */
            $model->visible($visible, $override);
        });

        return $this;
    }

    /**
     * 设置需要追加的输出属性
     * @access public
     * @param  array $append   属性列表
     * @param  bool  $override 是否覆盖
     * @return $this
     */
    public function append($append = [], $override = false)
    {
        $this->each(function ($model) use ($append, $override) {
            /** @var Model $model */
            $model && $model->append($append, $override);
        });

        return $this;
    }

    /**
     * 设置数据字段获取器
     * @access public
     * @param  string|array $name       字段名
     * @param  callable     $callback   闭包获取器
     * @return $this
     */
    public function withAttr($name, $callback = null)
    {
        $this->each(function ($model) use ($name, $callback) {
            /** @var Model $model */
            $model && $model->withAttribute($name, $callback);
        });

        return $this;
    }
}
