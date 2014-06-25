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
use umicms\slugify\ISlugGeneratorAware;
use umicms\slugify\TSlugGeneratorAware;

/**
 * Фильтр преводит строку к валидному slug'у.
 */
class Slug implements IFilter, ISlugGeneratorAware
{
    use TSlugGeneratorAware;

    /**
     * Имя типа фильтра
     */
    const TYPE = 'slug';

    /**
     * @var array $options опции фильтра
     */
    protected $options = [];

    /**
     * Конструктор
     * @param array $options опции фильтра
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
        return $this->getSlugGenerator()->generate($var, $this->options);
    }
}
 