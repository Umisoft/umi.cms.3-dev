<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\site\profile\password\model;

use umi\validation\BaseValidator;
use umicms\exception\RuntimeException;

/**
 * Валидатор текущего пароля пользователя
 */
class PasswordValidator extends BaseValidator
{
    /**
     * {@inheritdoc}
     */
    public function isValid($value)
    {
        if (empty($this->options['salt'])) {
            throw new RuntimeException($this->translate(
                'Option "{option}" is required.', ['option' => 'salt']
            ));
        }

        if (empty($this->options['hash'])) {
            throw new RuntimeException($this->translate(
                'Option "{option}" is required.', ['option' => 'hash']
            ));
        }

        $this->message = null;

        $valid = (crypt($value, $this->options['salt']) === $this->options['hash']);
        if (!$valid) {
            $this->message = !empty($this->options['message']) ?
                $this->options['message'] : $this->translate($this->getErrorLabel());
        }

        return $valid;
    }
}
 