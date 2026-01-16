<?php

namespace App\Controllers\service;

use App\Core\Attributes\Preserve;
use App\Core\Attributes\Table;
use DateTimeImmutable;
use Exception;
use ReflectionClass;

class ToArray {
    private array $result = [];

    private function strapProperties(object $entity){
        $ref  = new ReflectionClass($entity);
        $values = [];
                
        foreach($ref->getProperties() as $property){
            if(!$property->getAttributes(Preserve::class)) continue;
            $type = $property->getType();
            
            if ($property->getValue($entity) instanceof DateTimeImmutable) {
                $values[$property->getName()] = ($property->getValue($entity))?->format('d-m-y');
                continue;
            }
            
            if($type && !$type->isBuiltin()){
                $objectValue = $this->objectToArray($property->getValue($entity));
                $values[$property->getName()] = $objectValue;
                continue;
            }

            if($type && $type->isBuiltin()){
                $values[$property->getName()] = $property->getValue($entity);
            }
            continue;
        }
        return $values;
}

    public function objectToArray(object | array $entity) {
        $result = [];
        if(!is_array($entity)){
            return $this->strapProperties($entity);
        }

        foreach($entity as $e){
            $result[] = $this->strapProperties($e);
        }
        return $result;
    }
}