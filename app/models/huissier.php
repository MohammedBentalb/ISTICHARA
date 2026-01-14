<?php

namespace App\Models;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity]
class Huissier extends Person{
    
#[ORM\Table(name: "huissiers")]
    private bool $typesSctes;

    public function __construct(string $typesSctes, string $name, string $email, int $villeId, int $yearsOfExperience, ?DateTimeImmutable $createdAt, ?int $id = null) {
        parent::__construct($name, $email, $villeId, $yearsOfExperience, $createdAt, $id);
        $this->typesSctes = $typesSctes;
    }

    public function getTypesSctes(){
        return $this->typesSctes;
    }

    public function setTypesSctes(bool $value){
        $this->typesSctes = $value;
    }
}