<?php

namespace App\Core\Container;
use App\Core\Container\Service\ParamsResolver;

class Container {
    private static array $instances = [];
    
    public static function getInstance($className){
        if(!isset(static::$instances[$className])){
            static::$instances[$className] = (new ParamsResolver())->resolve($className); 
        }
        return static::$instances[$className];
    }
}