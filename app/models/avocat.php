<?php

namespace App\Models;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "avocats")]
class Avocat extends Person{

    #[ORM\Column(type: 'boolean')]
    private bool $consultationEnLigne;
    
    #[ORM\Column(length: 255)] // ineed to make it enum
    private string $specialite;

    public function __construct(bool $consultationEnLigne, string $specialite, string $name, string $email, int $villeId, int $yearsOfExperience, ?DateTimeImmutable $createdAt, ?int $id = null) {
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