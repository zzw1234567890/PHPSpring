<?php

namespace Lib;

use Lib\Attribute\Autowried;
use Lib\Attribute\Handler;
use Lib\Aware\IComponentAware;
use Lib\Aware\IInitAfterAware;
use Lib\Aware\IInitBeforeAware;
use Lib\Config;
use Lib\Utils\AttributeUtil;
use ReflectionClass;

/**
 * 单例容器
 * 用来管理单例对象实例
 */
class AppContext{
    static $singleObjects = [];
    static $componentAwares = [];

    public static function createInstance(string $class) : object{
        $reflection = new ReflectionClass($class);
        $instance = new $class;
        self::$singleObjects[$class] = $instance;
        // 处理aware
        if($instance instanceof IInitBeforeAware){
            $instance->initBefore($instance, $reflection);
        }
        // 初始化属性，自动注入
        self::paramInjection($instance, $reflection);
        // 处理aware
        if($instance instanceof IInitAfterAware){
            $instance->initAfter($instance, $reflection);
        }
        if($instance instanceof IComponentAware){
            self::$componentAwares[] = $instance;
        }
        return $instance;
    }

    public static function getInstance(string $class) : object{
        return self::$singleObjects[$class] ?? self::createInstance($class);
    }

    private static function paramInjection(object $instance, ReflectionClass $reflection) : void{
        foreach($reflection->getProperties() as $property){
            $attribute = $property->getAttributes(Autowried::class);
            if(!count($attribute)){
                continue;
            }
            $attribute = $attribute[0]->newInstance();
            $propertyName = $property->getName();
            $class = $attribute->value ?? ucfirst($propertyName);
            $property->setAccessible(true);
            $property->setValue($instance, self::getInstance($class));
        }
    }

    private function loadConfig() : void{
        require_once LIB_DIR . "/Config.php";
        $this->config = self::getInstance(Config::class);
        foreach(glob(CONFIG_DIR . '/*.json') as $file){
            $param = str_replace([CONFIG_DIR . "/", ".json"], "", $file);
            $this->config->$param = json_decode(file_get_contents($file), true);            
        }
    }

    private function loadComponent() : void{
        require_once LIB_DIR . "/ClassLoader.php";
        self::getInstance(ClassLoader::class)->loadComponent();
    }

    private function parseComponent() : void{
        foreach(self::$singleObjects as $instance){
            $reflection = new ReflectionClass(get_class($instance));
            foreach(self::$componentAwares as $componentAware){
                $componentAware->parseComponent($this, $instance, $reflection);
            }
        }
    }

    public function init() : void{
        $this->loadConfig();
        $this->loadComponent();
        $this->parseComponent();
    }
}