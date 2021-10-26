<?php

namespace Lib\Extend;

use ReflectionMethod;

class HandlerTypeEntity{
    public $method;
    public $instance;

    public function __construct(ReflectionMethod $method, object $instance){
        $this->method = $method;
        $this->instance = $instance;
    }
}