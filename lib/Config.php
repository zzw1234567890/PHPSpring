<?php

namespace Lib;

use Lib\Attribute\Component;

/**
 * 配置类
 * 加载的配置文件都通过该类来引用
 */
#[Component]
class Config{

    public function __construct(){
        $this->classpath = [
            LIB_DIR, 
            APP_DIR,
        ];
        // 排除的文件列表，这些文件交给 gateway-worker 处理
        $this->excludePattern = [
            APP_DIR . "/*/Events.php",
            APP_DIR . "/*/start_businessworker.php",
            APP_DIR . "/*/start_gateway.php",
            APP_DIR . "/*/start_register.php",
            APP_DIR . "/*/start_worker.php",
        ];
    }
}