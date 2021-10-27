<?php

namespace WebSocket\Handler;

use Lib\Attribute\Autowried;
use Lib\Attribute\Handler;
use Lib\Attribute\HandlerType;
use Lib\Config;
use Lib\Extend\BaseHandler;
use WebSocket\Service\DefaultService;

#[Handler]
class DefaultHandler extends BaseHandler{

    #[Autowried(Config::class)]
    private $config;

    #[Autowried(DefaultService::class)]
    private $defaultService;

    #[HandlerType("111")]
    private function hello($name, $sex){
        $this->defaultService->hello();
        print_r($name . " " . $sex . "\n");
        print_r($this->getClientId() . "\n");
        // print_r($clientId."\n");
        // print_r($message);
    }
}