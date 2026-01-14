<?php

namespace App\Core\Resolvers;

use App\Core\Container\Container;
use ReflectionMethod;

class MethodResolver {
    public function resolveParams($controller, $method){
        $args = [];
        $ref = new ReflectionMethod($controller, $method);
        foreach($ref->getParameters() as $parameter){
            $type = $parameter->getType();
            if($type && !$type->isBuiltin()){
                $args[] = Container::getInstance($type->getName());
            }
        }
        return $args;
    }
}