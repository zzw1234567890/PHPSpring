<?php

namespace Lib\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Autowried{
    public $value;
    public function __construct(string $value = null){
        $this->value = $value;
    }
}