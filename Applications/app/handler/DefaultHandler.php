<?php

namespace App\Handler;

use Lib\Attribute\Autowried;
use Lib\Attribute\Handler;
use Lib\Attribute\HandlerType;
use Lib\Config;
use Lib\Extend\BaseHandler;

#[Handler]
class DefaultHandler extends BaseHandler{

    #[Autowried(Config::class)]
    private $config;

    #[HandlerType("111")]
    private function hello($name, $sex){
        print_r($name . " " . $sex . "\n");
        print_r($this->getClientId() . "\n");
        // print_r($clientId."\n");
        // print_r($message);
    }
}