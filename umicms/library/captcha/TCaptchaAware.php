<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\captcha;

use umicms\exception\RequiredDependencyException;

/**
 * Трейт для поддержки проверки и генерации captcha
 */
trait TCaptchaAware
{
    /**
     * @var ICaptchaGenerator $traitCaptchaGenerator
     */
    private $traitCaptchaGenerator;

    /**
     * @see ICaptchaAware::setCaptchaGenerator()
     */
    public function setCaptchaGenerator(ICaptchaGenerator $captchaGenerator)
    {
        $this->traitCaptchaGenerator = $captchaGenerator;
    }

    /**
     * Возвращает генератор captcha.
     * @throws RequiredDependencyException если генератор не был внедрен
     * @return ICaptchaGenerator
     */
    protected function getCaptchaGenerator()
    {
        if (!$this->traitCaptchaGenerator) {
            throw new RequiredDependencyException(sprintf(
                'Captcha generator is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->traitCaptchaGenerator;
    }
}
 