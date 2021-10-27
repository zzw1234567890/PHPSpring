<?php

namespace WebSocket\Interceptor;

use Lib\Attribute\Component;
use Lib\IInterceptor;

#[Component]
class ParamVerfityInterceptor implements IInterceptor{
    public function intercept($clientId, $message) : bool{
        return true;
    }
}