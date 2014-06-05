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
 