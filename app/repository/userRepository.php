<?php

namespace App\Repository;

use App\Models\User;

class UserRepository extends BaseRepository{
    protected static $entityName = User::class;   

    public function save(object $entity, $villeId = null){
        $this->em->persist($entity);
        return $this->em->flush();
    }
}