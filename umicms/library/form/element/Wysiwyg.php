<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\form\element;

use umi\form\element\Textarea;

/**
 * Элемент формы с отображением wysiwyg
 */
class Wysiwyg extends Textarea
{
    /**
     * Тип элемента.
     */
    const TYPE_NAME = 'wysiwyg';
    /**
     * {@inheritdoc}
     */
    protected $type = 'wysiwyg';
}
 