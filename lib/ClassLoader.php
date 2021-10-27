<?php

namespace Lib;

use Exception;
use Lib\Attribute\Component;
use ReflectionClass;

class ClassLoader{
    public function __construct(){
        $this->config = AppContext::getInstance(Config::class);
    }
    // 解析排除的文件规则列表，得到排除的文件列表
    private function parseExcludePattern(){
        $config = $this->config;
        $config->exclude = [];
        foreach($config->excludePattern as $pattern){
            $config->exclude = array_merge($config->exclude, glob($pattern));
        }
    }

    // 递归检查注解是否有Component注解
    private function isExtendComponent(array $attributes) : bool{
        foreach($attributes as $attribute){
            $class = $attribute->getName();
            if($class == Component::class){
                return true;
            }
            $reflection = new ReflectionClass($class);
            return $this->isExtendComponent($reflection->getAttributes());
        }
        return false;
    }

    private function isComponent(string $class) : bool{
        try{
            $reflection = new ReflectionClass($class);
            $attributes = $reflection->getAttributes();
            if(!$reflection->isInstantiable() || !count($attributes)){
                return false;
            }
            return $this->isExtendComponent($attributes);
        } catch (Exception $e){
            return false;
        }
    }

    // 递归获取文件列表
    private function getFiles(string $filePattern) : array{
        $files = [];
        foreach(glob($filePattern) as $file){
            if(is_file($file)){
                $files[] = $file;
            }else{
                $files = array_merge($files, $this->getFiles($file . "/*"));
            }
        }
        return $files;
    }

    private function createComponent($dir){
        $config = $this->config;
        foreach($this->getFiles($dir . "/*") as $file){
            if(in_array($file, $config->exclude)){
                continue;
            }
            $class = ucwords(str_replace([ROOT_DIR . "/", "Applications/", ".php"], "", $file), "/");
            $class = str_replace("/", "\\", $class);
            if($this->isComponent($class)){
                AppContext::getInstance($class);
            }
        }
    }

    private function doLoadComponent($dir){
        $config = $this->config;
        foreach($this->getFiles($dir . "/*") as $file){
            if(in_array($file, $config->exclude)){
                continue;
            }
            require_once $file;
        }
        $this->createComponent($dir);
    }

    public function loadComponent(){
        $config = $this->config;
        $this->parseExcludePattern($config);
        foreach($config->classpath as $dir){
            $this->doLoadComponent($dir);
        }
    }
}