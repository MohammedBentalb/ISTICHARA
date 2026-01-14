<?php

namespace App\Models;

class Huissier extends Person{
    private bool $typesSctes;

    public function __construct(string $typesSctes, string $name, string $email, int $villeId, int $yearsOfExperience,string $createdAt, ?int $id = null) {
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