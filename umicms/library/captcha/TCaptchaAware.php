<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
 