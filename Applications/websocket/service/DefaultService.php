<?php

namespace WebSocket\Service;

use Lib\Attribute\Service;

#[Service]
class DefaultService{
    public function hello(){
        print_r("helloService\n");
    }
}