<?php

namespace Lib\Extend;

class BaseHandler{

    public $clientId;
    public $message;

    public function getClientId() : string{
        return $this->clientId;
    }
    public function getMessage() : array{
        return $this->message;
    }
}