<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umitest;

/**
 * Тестовый почтовый ящик
 */
class MockMessageBox {
    /**
     * @var array $messages сообщения
     */
    private $messages = [];

    /**
     * Положить сообщение в ящик
     * @param $recipient
     * @param $subject
     * @param $content
     */
    public function push($recipient, $subject, $content)
    {
        $recipients = array($recipient);

        foreach ($recipients as $email => $name) {
            if (!isset($this->messages[$email])) {
                $this->messages[$email] = [];
            }
            $this->messages[$email][$subject] = $content;
        }
    }

    /**
     * Возвращает контент письма с указанным email и subject
     * @param string $email
     * @param string $subject
     * @throws \OutOfBoundsException если письма с указанной темой и email
     * @return string
     */
    public function read($email, $subject)
    {
        if (!$this->has($email, $subject)) {
            throw new \OutOfBoundsException("Cannot read message from {$email} with subject {$subject}.");
        }

        return $this->messages[$email][$subject];
    }

    /**
     * Проверяет, есть ли письмо в ящике с указанным email и subject
     * @param string $email
     * @param string $subject
     * @return bool
     */
    public function has($email, $subject)
    {
        return isset($this->messages[$email][$subject]);
    }

}