<?php

namespace App\Models;

use App\Core\Attributes\Preserve;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use App\Core\Attributes\Table as LocalTable;


#[ORM\Entity]
#[ORM\Table(name: "huissiers")]
#[LocalTable("huissiers")]
class Huissier extends Person{
    
    #[ORM\Column(name: "types_actes")]
    #[Preserve]
    private string $typesActes;

    public function __construct(string $typesActes, string $name, string $email,  int $yearsOfExperience, ?int $id = null) {
        parent::__construct($name, $email, $yearsOfExperience, $id);
        $this->typesActes = $typesActes;
    }

    public function getTypesActes(){
        return $this->typesActes;
    }

    public function setTypesSctes(string $value){
        $this->typesActes = $value;
    }
}