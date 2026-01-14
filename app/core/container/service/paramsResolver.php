<?php

namespace App\Core\Container\Service;

use App\Core\Container\Container;
use Exception;
use ReflectionClass;

class ParamsResolver{
    private array $arguments = [];
    public function resolve($class){
        $ref = new ReflectionClass($class);

        if(!$ref->isUserDefined()){
            throw new Exception("$class is not a user defined");
        }

        if($ref->isInterface()){
            if(!isset($GLOBALS['config'][$class])) throw new Exception("$class is not a defined interface");
            $ref = new ReflectionClass($GLOBALS['config'][$class]);
        }

        $construct = $ref->getConstructor();
        if(!$construct) return $ref->newInstance();

        foreach($construct->getParameters() as $parameter){
           $type = $parameter->getType();
            if($type && !$type->isBuiltin()){
                // check if it's and interface and the get default value from IDB 
                $this->arguments[] = Container::getInstance($type->getName());
                continue;
            }
            
            if($parameter->isDefaultValueAvailable()){
                $this->arguments[] = $parameter->getDefaultValue();
                continue;
            }
            throw new Exception("{$parameter->getName()} is a built in , and does not have a default values");
        }
        return $ref->newInstanceArgs($this->arguments);
    }
}