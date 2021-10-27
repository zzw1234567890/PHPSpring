<?php

use Lib\AppContext;
use WebSocket\Handler\DefaultHandler;
use \Workerman\Worker;

// 自动加载类
require_once __DIR__ . '/../../vendor/autoload.php';

// Worker 进程
$http_worker = new Worker("http://0.0.0.0:8000");
// worker名称
$http_worker->name = 'http';
// Worker进程数量
$http_worker->count = 2;

$http_worker->onMessage = function($conn, $request){
    print_r("ok\n");
    $conn->send("hello");
};

// 如果不是在根目录启动，则运行runAll方法
if(!defined('GLOBAL_START')){
    require_once(__DIR__ . '/../../boot.php');
    Worker::runAll();
}

