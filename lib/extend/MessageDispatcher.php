<?php

namespace Lib\Extend;

use Exception;
use Lib\AppContext;
use Lib\Attribute\Autowried;
use Lib\Attribute\Component;
use Lib\IDispatcher;
use ReflectionMethod;

#[Component]
class MessageDispatcher implements IDispatcher{

    private const MESSAGE_TYPE_KEY = "type";
    private const MESSAGE_CONTENT_KEY = "content";
    private const CLIENT_ID_KEY = "clientId";
    private const MESSAGE_KEY = "message";

    #[Autowried(AppContext::class)]
    private $appContext;

    public function dispatch($clientId, $message) : void{
        try{
            $this->doDispatch($clientId, $message);
        }catch(Exception $e){
            print_r($e->getMessage());
        }
    }

    public function doDispatch(string $clientId, string $message) : void{
        $message = json_decode($message, true);
        if(!$this->doInterceptor($clientId, $message)){
            return;
        };
        $handlerTypeEntity = $this->getHandlerType($message);
        $method = $handlerTypeEntity->method;
        $instance = $handlerTypeEntity->instance;
        $this->paramsInjection($instance, $clientId, $message);
        $params = $this->getParams($method, $clientId, $message);
        $method->invoke($instance, ...$params);
    }

    public function doInterceptor(string $clientId, array $message) : bool{
        foreach($this->appContext->interceptors as $interceptor){
            if(!$interceptor->intercept($clientId, $message)){
                return false;
            }
        }
        return true;
    }

    public function getHandlerType(array $message) : HandlerTypeEntity{
        return $this->appContext->handlerTypes[$message[self::MESSAGE_TYPE_KEY]];
    }

    public function paramsInjection(object $instance, string $clientId, array $message) : void{
        $instance->clientId = $clientId;
        $instance->message = $message;
    }

    public function getParams(ReflectionMethod $method, string $clientId, array $message) : array{
        $params = $method->getParameters();
        $res = [];
        foreach($params as $param){
            $paramName = $param->getName();
            switch($paramName){
                case self::CLIENT_ID_KEY: $res[$paramName] = $clientId;break;
                case self::MESSAGE_KEY: $res[$paramName] = $message;break;
                case self::MESSAGE_CONTENT_KEY: $res[$paramName] = $message[$paramName];break;
                default: $res[$paramName] = $message[self::MESSAGE_CONTENT_KEY][$paramName];
            }
        }
        return $res;
    }
}