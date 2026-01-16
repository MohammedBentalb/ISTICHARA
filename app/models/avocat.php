<?php

namespace App\Models;

use App\Core\Attributes\Preserve;
use App\Core\Attributes\Table as LocalTable;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "avocats")]
#[LocalTable("avocats")]
class Avocat extends Person{

    #[Preserve]
    #[ORM\Column(type: 'boolean', name: "consultation_en_ligne")]
    private bool $consultationEnLigne;
    
    #[ORM\Column(length: 255)] // ineed to make it enum
    #[Preserve]
    private string $specialite;

    public function __construct(bool $consultationEnLigne, string $specialite, string $name, string $email, ?int $villeId, int $yearsOfExperience, ?int $id = null) {
        parent::__construct($name, $email, $yearsOfExperience, $villeId,  $id);
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