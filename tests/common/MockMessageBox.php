<?php

namespace umitest;

class MockMessageBox {

    private $messages = [];

    public function push($recipient, $subject, $content)
    {
        $this->messages[$recipient][$subject] = $content;
    }

    public function read($recipient, $subject)
    {
        if (isset($this->messages[$recipient][$subject])) {
            $result = $this->messages[$recipient][$subject];
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

}