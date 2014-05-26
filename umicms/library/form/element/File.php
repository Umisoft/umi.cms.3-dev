<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\form\element;

use umi\form\element\BaseFormInput;

/**
 * Элемент формы файл.
 */
class File extends BaseFormInput
{
    /**
     * Тип элемента.
     */
    const TYPE_NAME = 'file';

    /**
     * {@inheritdoc}
     */
    protected $type = 'file';
    /**
     * {@inheritdoc}
     */
    protected $inputType = 'file';
}
 