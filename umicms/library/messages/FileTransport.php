<?php

namespace umicms\messages;

use Swift_Events_EventListener;
use Swift_Mime_Message;
use Swift_Transport;

class FileTransport implements Swift_Transport {

    public function send(Swift_Mime_Message $message, &$failedRecipients = null)
    {
        file_put_contents('messages.txt', $message->toString(), FILE_APPEND | LOCK_EX);
        return 1;
    }

    /**
     * Test if this Transport mechanism has started.
     *
     * @return bool
     */
    public function isStarted()
    {
        // TODO: Implement isStarted() method.
    }

    /**
     * Start this Transport mechanism.
     */
    public function start()
    {
        // TODO: Implement start() method.
    }

    /**
     * Stop this Transport mechanism.
     */
    public function stop()
    {
        // TODO: Implement stop() method.
    }

    /**
     * Register a plugin in the Transport.
     *
     * @param Swift_Events_EventListener $plugin
     */
    public function registerPlugin(Swift_Events_EventListener $plugin)
    {
        // TODO: Implement registerPlugin() method.
    }


}