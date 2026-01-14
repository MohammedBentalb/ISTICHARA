<?php

namespace App\Models;

class Avocat extends Person{
    private bool $consultationEnLigne;
    private string $specialite;

    public function __construct(bool $consultationEnLigne, string $specialite, string $name, string $email, int $villeId, int $yearsOfExperience,string $createdAt, ?int $id = null) {
        parent::__construct($name, $email, $villeId, $yearsOfExperience, $createdAt, $id);
        $this->consultationEnLigne = $consultationEnLigne;
        $this->specialite = $specialite;
    }

    public function getConsultationEnLigne(){
        return $this->consultationEnLigne;
    }

    public function setConsultationEnLigne(bool $value){
        $this->consultationEnLigne = $value;
    }

    public function getSpecialite(){
        return $this->specialite;
    }
    public function setSpecialite(string $value){
        $this->specialite = $value;
    }
} 