<?php

namespace Lib\Extend;

use Lib\Attribute\Component;
use Lib\Aware\IComponentAware;
use Lib\IInterceptor;

/**
 * 解析Interceptor
 */
#[Component]
class InterceptorComponentAware implements IComponentAware{
    
    function parseComponent($appContext, $instance, $reflection){
        if($instance instanceof IInterceptor){
            $appContext->interceptors[] = $instance;
        }
    }
}