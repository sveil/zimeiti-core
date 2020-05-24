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

namespace sveil\console\output\question;

use sveil\console\output\Question;

/**
 * Class Confirmation
 * @author Richard <richard@sveil.com>
 * @package sveil\console\output\question
 */
class Confirmation extends Question
{
    private $trueAnswerRegex;

    /**
     * 构造方法
     * @param string $question        问题
     * @param bool   $default         默认答案
     * @param string $trueAnswerRegex 验证正则
     */
    public function __construct($question, $default = true, $trueAnswerRegex = '/^y/i')
    {
        parent::__construct($question, (bool) $default);
        $this->trueAnswerRegex = $trueAnswerRegex;
        $this->setNormalizer($this->getDefaultNormalizer());
    }

    /**
     * 获取默认的答案回调
     * @return callable
     */
    private function getDefaultNormalizer()
    {
        $default = $this->getDefault();
        $regex   = $this->trueAnswerRegex;

        return function ($answer) use ($default, $regex) {
            if (is_bool($answer)) {
                return $answer;
            }

            $answerIsTrue = (bool) preg_match($regex, $answer);

            if (false === $default) {
                return $answer && $answerIsTrue;
            }

            return !$answer || $answerIsTrue;
        };
    }
}
