<?php

namespace umitest;

class MockMessageBox {

    private $messages = [];

    public function push($recipient, $subject, $content)
    {
        if (is_array($recipient)) {
            foreach ($recipient as $email => $name) {
                $this->doPush($email, $subject, $content);
            }

        } else {
            $this->doPush($recipient, $subject, $content);
        }
    }

    private function doPush($email, $subject, $content)
    {
        $this->messages[$email][$subject] = $content;
    }

    public function read($email, $subject)
    {
        if (isset($this->messages[$email][$subject])) {
            $result = $this->messages[$email][$subject];
            if (false === stripos($result, '<html>')) {
                return '<html>' . $result .'</html>';
            }
            return $result;
        }
        return null;
    }

    public function clean()
    {
        $this->messages = [];
    }

    public function count($email)
    {
        return count($this->messages[$email]);
    }

}