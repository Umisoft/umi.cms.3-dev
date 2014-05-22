<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\form\element;

use umi\form\element\Password;

/**
 * Пароль с подтверждением.
 */
class PasswordWithConfirmation extends Password
{
    /**
     * Тип элемента.
     */
    const TYPE_NAME = 'passwordWithConfirmation';
    /**
     * {@inheritdoc}
     */
    protected $inputType = Password::TYPE_NAME;
    /**
     * @var array $password пароль
     */
    private $password;

    /**
     * {@inheritdoc}
     */
    public function getElementName()
    {
        $name = parent::getElementName();

        return $name . '[]';
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        $value = $this->filter($value);
        $this->password = $value;
        $this->getDataAdapter()->setData($this, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value)
    {
        $valid = parent::validate($value);

        if (!is_array($this->password) || !isset($this->password['0']) || !isset($this->password[1])) {
            $valid = false;
            $this->messages = array_merge(
                $this->messages,
                [$this->translate('Incorrect value type.')]
            );
        } elseif ($this->password['0'] !== $this->password[1]) {
            $valid = false;
            $this->messages = array_merge(
                $this->messages,
                [$this->translate('Passwords are not equal.')]
            );
        }

        return $valid;
    }
}
 