<?php

namespace umicms\messages;

use Swift_Events_EventListener;
use Swift_Mime_Message;
use Swift_Transport;

class FileTransport implements Swift_Transport {

    /**
     * @var string путь к файлу, в который будет сохраняться отправляемое сообщение
     */
    private $filePath;

    /**
     * @param $filePath string путь к файлу, в который будет сохраняться отправляемое сообщение
     */
    public function __construct($filePath)
    {
        if (empty($filePath)) {
            throw new \LogicException('Путь к файлу не может быть пустым');
        }
        $this->filePath = $filePath;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $res = file_put_contents($this->filePath, \imap_utf8($message->getBody()), LOCK_EX);
        return (bool)$res;
    }

    /**
     * {@inheritdoc}
     */
    public function isStarted()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function start()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function stop()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function registerPlugin(Swift_Events_EventListener $plugin)
    {
    }
}