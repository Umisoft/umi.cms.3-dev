<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\validation;

use umi\validation\BaseValidator;

/**
 * Валидатор диапазона числовых значений.
 */
class IsWritableFile extends BaseValidator
{

    const NAME = 'isWritableFile';

    /**
     * {@inheritdoc}
     */
    protected $defaultErrorLabel = 'File {file} is not writable';

    /**
     * {@inheritdoc}
     */
    public function isValid($value)
    {
        if (!is_writable($this->options['tpl_path'])) {
            $this->message = $this->translate(
                $this->getErrorLabel(),
                [ 'file' => $this->options['tpl_path'] ]
            );
            return false;
        }

        return true;
    }
}
 