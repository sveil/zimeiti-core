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

namespace sveil\think\process;

class Utils
{

    /**
     * 转义字符串
     * @param string $argument
     * @return string
     */
    public static function escapeArgument($argument)
    {

        if ('' === $argument) {
            return escapeshellarg($argument);
        }
        $escapedArgument = '';
        $quote           = false;
        foreach (preg_split('/(")/i', $argument, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE) as $part) {
            if ('"' === $part) {
                $escapedArgument .= '\\"';
            } elseif (self::isSurroundedBy($part, '%')) {
                // Avoid environment variable expansion
                $escapedArgument .= '^%"' . substr($part, 1, -1) . '"^%';
            } else {
                // escape trailing backslash
                if ('\\' === substr($part, -1)) {
                    $part .= '\\';
                }
                $quote = true;
                $escapedArgument .= $part;
            }
        }
        if ($quote) {
            $escapedArgument = '"' . $escapedArgument . '"';
        }
        return $escapedArgument;
    }

    /**
     * 验证并进行规范化Process输入。
     * @param string $caller
     * @param mixed  $input
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function validateInput($caller, $input)
    {
        if (null !== $input) {
            if (is_resource($input)) {
                return $input;
            }
            if (is_scalar($input)) {
                return (string) $input;
            }
            throw new \InvalidArgumentException(sprintf('%s only accepts strings or stream resources.', $caller));
        }
        return $input;
    }

    private static function isSurroundedBy($arg, $char)
    {
        return 2 < strlen($arg) && $char === $arg[0] && $char === $arg[strlen($arg) - 1];
    }

}
