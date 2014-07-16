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
class Range extends BaseValidator
{

    const NAME = 'rangeInt';

    /**
     * {@inheritdoc}
     */
    protected $defaultErrorLabel = 'Value is not in range [{min}..{max}].';

    /**
     * {@inheritdoc}
     */
    public function isValid($value)
    {
        $this->message = null;

        $error = false;

        if (!is_numeric($value)) {
            $error = true;
        }

        if (isset($this->options['min']) && (int) $this->options['min'] > $value) {
            $error = true;
        }

        if (isset($this->options['max']) && (int) $this->options['max'] < $value) {
            $error = true;
        }

        if ($error) {
            $this->message = $this->translate(
                $this->getErrorLabel(),
                [
                    'min' => !isset($this->options['min']) ? '': (int) $this->options['min'],
                    'max' => !isset($this->options['max']) ? '' : (int) $this->options['max']
                ]
            );
            return false;
        }

        return true;
    }
}
 