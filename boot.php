<?php

// 项目根目录

use Lib\AppContext;

define("ROOT_DIR", __DIR__);
// 配置文件目录
define('CONFIG_DIR', __DIR__ . '/config');
// 核心文件目录
define("LIB_DIR", __DIR__ . '/lib');
// 应用文件目录
define("APP_DIR", __DIR__ . '/Applications');

require_once LIB_DIR . '/AppContext.php';
AppContext::getInstance(AppContext::class)->init();
