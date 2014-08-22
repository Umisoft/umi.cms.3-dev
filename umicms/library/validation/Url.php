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
 * Валидатор URL.
 */
class Url extends BaseValidator
{
    /**
     * Наименование валидатора.
     */
    const NAME = 'url';

    /**
     * {@inheritdoc}
     */
    protected $defaultErrorLabel = 'Url is not valid.';

    /**
     * {@inheritdoc}
     */
    public function isValid($value)
    {
        $this->message = null;

        if ($value !== null && filter_var($value, FILTER_VALIDATE_URL) === false) {
            $this->message = $this->translate($this->getErrorLabel());

            return false;
        }

        return true;
    }
}
 