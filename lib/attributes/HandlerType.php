<?php

namespace Lib\Attribute;

use Attribute;

#[Component]
#[Attribute(Attribute::TARGET_METHOD)]
class HandlerType{
    public $value;
    public $desc;
    public function __construct(string $value = null, string $desc = null){
        $this->value = $value;
        $this->desc = $desc;
    }
}