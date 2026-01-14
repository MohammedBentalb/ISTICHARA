<?php

namespace App\Repository;

use App\Core\ORM\EntityFactory;
use Doctrine\ORM\EntityManager;

abstract class BaseRepository {
    protected EntityManager $em;

    public function __construct(EntityFactory $entityFactory) {
        return $this->em = $entityFactory->create();
    }

    public function save(object $entity){
        $this->em->persist($entity);
        return $this->em->flush();
    }

    public function update(object $entity){
        return $this->em->flush();
    }

    public function find(string $className, int $id){
        return $this->em->find($className, $id);
    }

    public function findAll(string $className, array $criteria = [], ?int $limit = null, ?int $offset = null){
        return $this->em->getRepository($className)->findBy($criteria, null, $limit, $offset);
    }
    
    public function Delete(){}
}