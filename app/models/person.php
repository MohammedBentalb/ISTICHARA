<?php

namespace App\Models;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
class Person {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected ?int $id = null;

    #[ORM\Column(length: 100)]
    protected string $name;

    #[ORM\Column(length: 150, unique: true)]
    protected string $email;

    #[ORM\ManyToOne(targetEntity: Ville::class)]
    #[ORM\JoinColumn(name: "ville_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    protected ?Ville $ville = null;

    #[ORM\Column(type: "integer")]
    protected int $yearsOfExperience;

    #[ORM\Column(type: "datetime_immutable")]
    protected DateTimeImmutable $createdAt;


    public function __construct(string $name, string $email, int $yearsOfExperience, DateTimeImmutable $createdAt, ?Ville $ville, ?int $id = null) {
        $this->name = $name;
        $this->email = $email;
        $this->ville = $ville;
        $this->yearsOfExperience = $yearsOfExperience;
        $this->createdAt = $createdAt;
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setId(int | null $value){
        $this->id = $value;
    }

    public function setName(string $value){
        $this->name = $value;
    }

    public function getName(){
        return $this->name;
    }

    public function setEmail(string $value){
        $this->email = $value;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getVille(){
        return $this->ville;
    }
    
    public function setVille(Ville $value){
        $this->ville = $value;
    }

    public function getYearsOfExperience(){
        return $this->yearsOfExperience;
    }

    public function setYearsOfExperience(int $value){
        $this->yearsOfExperience = $value;
    }

    public function getCreatedAt(){
        return $this->createdAt;
    }

    public function setCreatedAt( DateTimeImmutable $value){
        $this->createdAt = $value;
    }
}