<?php

namespace Lib\Extend;

use Lib\Attribute\Component;
use Lib\Attribute\Handler;
use Lib\Attribute\HandlerType;
use Lib\Aware\IComponentAware;
use Lib\Utils\AttributeUtil;

/**
 * 解析Handler
 */
#[Component]
class HandlerComponentAware implements IComponentAware{

    function parseComponent($appContext, $instance, $reflection) : void{
        if(!AttributeUtil::hasAttribute($reflection, Handler::class)){
            return;
        }
        $appContext->handlers[] = $instance;
        $methods = $reflection->getMethods();
        foreach($methods as $method){
            $method->setAccessible(true);
            $attribute = AttributeUtil::getAttributeOne($method, HandlerType::class);
            if(!$attribute){
                continue;
            }
            $handlerType = $attribute->value ?? $method->getName();
            $appContext->handlerTypes[$handlerType] = new HandlerTypeEntity($method, $instance);
        }
    }
}