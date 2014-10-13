<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\filter;

use umi\filter\IFilter;
use umicms\purifier\IPurifierAware;
use umicms\purifier\TPurifierAware;

/**
 * Фильтр очищает контент от XSS.
 */
class HtmlPurifier implements IFilter, IPurifierAware
{
    use TPurifierAware;

    /**
     * Имя типа фильтра
     */
    const TYPE = 'htmlPurifier';

    /**
     * @var array $options опции фильтра
     */
    protected $options = [];

    /**
     * Конструктор.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function filter($var)
    {
        return $this->purifyHtml($var, $this->options);
    }
}
 