<?php
/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use Lib\AppContext;
use Lib\Extend\MessageDispatcher;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events{
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 
     * @param int $clientId 连接id
     */
    public static function onConnect($clientId){
        echo getmypid() . " onConnect: $clientId\n";
    }
    
    /**
    * 当客户端发来消息时触发
    * @param int $clientId 连接id
    * @param mixed $message 具体消息
    */
    public static function onMessage($clientId, $message){
        AppContext::getInstance(MessageDispatcher::class)->dispatch($clientId, $message);
    }
   
    /**
    * 当用户断开连接时触发
    * @param int $clientId 连接id
    */
    public static function onClose($clientId){
        
    }
   
    /**
     * 进程启动时调用，执行初始化操作
     */
    public static function onWorkerStart(){

    }

}
