<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\slugify;

/**
 * Интерфейс для генерации slug'ов.
 */
interface ISlugGeneratorAware
{
    /**
     * Устанавливает генератор slug'ов.
     * @param ISlugGenerator $slugGenerator
     */
    public function setSlugGenerator(ISlugGenerator $slugGenerator);
}
 