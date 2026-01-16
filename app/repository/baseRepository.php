<?php

namespace App\Repository;

use App\Core\ORM\EntityFactory;
use App\Models\Ville;
use Doctrine\ORM\EntityManager;

abstract class BaseRepository {
    protected static $entityName;
    protected EntityManager $em;

    public function __construct(EntityFactory $entityFactory) {
        return $this->em = $entityFactory->create();
    }

    public function save(object $entity, $villeId){
        $entity->setVille($this->em->getReference(Ville::class, $villeId));
        $this->em->persist($entity);
        return $this->em->flush();
    }

    public function update(object $entity){
        return $this->em->flush();
    }

    public function find(int $id){
        return $this->em->find(static::$entityName, $id);
    }
    
   public function findBy(array $criteria){
    return $this->em->getRepository(static::$entityName)->findBy($criteria);
   }

    public function findAll(array $criteria = [], ?int $limit = null, ?int $offset = null){
        return $this->em->getRepository(static::$entityName)->findBy($criteria, null, $limit, $offset);
    }
    
    public function Delete(object $entity){
        $this->em->remove($entity);
        return $this->em->flush();
    }
}