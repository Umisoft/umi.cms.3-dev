<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
 