<?php

namespace App\Controllers\service;

use App\Core\Attributes\Preserve;
use App\Core\Attributes\Table;
use DateTimeImmutable;
use Doctrine\DBAL\Types\VarDateTimeImmutableType;
use Exception;
use ReflectionClass;
use Symfony\Component\VarExporter\Internal\Reference;

class ToArray {
    private array $result = [];

    private function strapProperties(object $entity){
        $ref  = new ReflectionClass($entity);
            $tableAttribute = $ref->getAttributes(Table::class);
            if(!$tableAttribute) throw new Exception("no table attribute got defined");
            $tableName = ($tableAttribute[0]->newInstance())->tableName;
            
            foreach($ref->getProperties() as $property){
                if(!$property->getAttributes(Preserve::class)) continue;
                $type = $property->getType();
                
                if ($property->getValue($entity) instanceof DateTimeImmutable) {
                    $this->result[$tableName . '_' . $property->getName()] = ($property->getValue($entity))?->format('d-m-y');
                    continue;
                }

                if($type && !$type->isBuiltin()){
                    $objectValue = $this->objectToArray($property-> getValue($entity));
                    $this->result = [...$this->result, ...$objectValue];
                    continue;
                }
                $this->result[$tableName . "_" . $property->getName()] = $property->getValue($entity);
            }
            return $this->result;
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