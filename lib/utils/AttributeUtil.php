<?php

namespace Lib\Utils;

use ReflectionClass;

class AttributeUtil{
    public static function hasAttribute(ReflectionClass $reflection, string $class) : bool{
        return count($reflection->getAttributes($class));
    }

    public static function getAttributeOne(object $reflection, string $class){
        $attribute = $reflection->getAttributes($class);
        if(!count($attribute)){
            return null;
        }
        return $attribute[0]->newInstance();;
    }
}