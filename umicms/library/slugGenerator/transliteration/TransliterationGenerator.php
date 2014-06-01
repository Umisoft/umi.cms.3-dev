<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\slugGenerator\transliteration;

use umicms\slugGenerator\ISlugGenerator;
use URLify;

/**
 * Генератор slug методом транслитерации.
 */
class TransliterationGenerator implements ISlugGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generate($text, array $options = [])
    {
        return URLify::filter($text);
    }
}
 