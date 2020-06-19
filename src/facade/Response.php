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

namespace sveil\facade;

use sveil\Facade;

/**
 * Class Response
 * @author Richard <richard@sveil.com>
 * @package sveil\facade
 * @see \sveil\Response
 * @mixin \sveil\Response
 * @method \sveil\response create(mixed $data = '', string $type = '', int $code = 200, array $header = [], array $options = []) static 创建Response对象
 * @method void send() static 发送数据到客户端
 * @method \sveil\Response options(mixed $options = []) static 输出的参数
 * @method \sveil\Response data(mixed $data) static 输出数据设置
 * @method \sveil\Response header(mixed $name, string $value = null) static 设置响应头
 * @method \sveil\Response content(mixed $content) static 设置页面输出内容
 * @method \sveil\Response code(int $code) static 发送HTTP状态
 * @method \sveil\Response lastModified(string $time) static LastModified
 * @method \sveil\Response expires(string $time) static expires
 * @method \sveil\Response eTag(string $eTag) static eTag
 * @method \sveil\Response cacheControl(string $cache) static 页面缓存控制
 * @method \sveil\Response contentType(string $contentType, string $charset = 'utf-8') static 页面输出类型
 * @method mixed getHeader(string $name) static 获取头部信息
 * @method mixed getData() static 获取原始数据
 * @method mixed getContent() static 获取输出数据
 * @method int getCode() static 获取状态码
 */
class Response extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'response';
    }
}
