<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\captcha;

/**
 * Интерфейс генератора каптчи.
 */
interface ICaptchaGenerator
{
    /**
     * Генерирует фразу для captcha
     * @return string
     */
    public function generatePhrase();

    /**
     * Генерирует captcha.
     * @param string $phrase
     * @param array $options Some captcha options
     * @return string
     */
    public function generate($phrase, array $options = []);

    /**
     * Проверяет фразу на соответсвие оригинальной, с учетом возможных пользоватьских ошибок.
     * @param string $originalPhrase оригинальная фраза
     * @param string $testPhrase проверяемая фраза
     * @return bool
     */
    public function test($originalPhrase, $testPhrase);
}
 