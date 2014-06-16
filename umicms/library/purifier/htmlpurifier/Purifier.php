<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\purifier\htmlpurifier;

use HTMLPurifier;
use HTMLPurifier_Config;
use umicms\purifier\IPurifier;

/**
 * Очиститель контента ezyang/htmlpurifier.
 */
class Purifier implements IPurifier
{
    /**
     * @var array $defaultOptions
     */
    protected $defaultOptions;

    public function __construct(array $defaultOptions = [])
    {
        $this->defaultOptions = $defaultOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function purify($string, array $options = [])
    {
        return $this->buildPurifier()->purify($string);
    }

    /**
     * Создаёт и возвращает HtmlPurifier.
     * @return HTMLPurifier
     */
    protected function buildPurifier()
    {
        return new HTMLPurifier(
            HTMLPurifier_Config::createDefault()
        );
    }
}
 