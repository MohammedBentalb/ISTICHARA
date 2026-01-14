<?php

namespace App\Models;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "villes")]
class Ville {
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private string $name;

    public function getName(){
        return $this->name;
    }

    public function setName(string $value){
        $this->name = $value;
    }

    public function getId(){
        return $this->id;
    }

    public function setId(int $value){
        $this->id = $value;
    }
} 