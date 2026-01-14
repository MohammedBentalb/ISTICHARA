<?php

namespace App\Models;

class Person {
    protected ?int $id = null;
    protected string $name;
    protected string $email;
    protected int $villeId;
    protected int $yearsOfExperience;
    protected string $createdAt;

    public function __construct(string $name, string $email, int $villeId, int $yearsOfExperience,string $createdAt, ?int $id = null) {
        $this->name = $name;
        $this->email = $email;
        $this->villeId = $villeId;
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

    public function getVilleId(){
        return $this->villeId;
    }
    
    public function setVilleId(int $value){
        $this->villeId = $value;
    }

    public function getYearsOfExperience(){
        return $this->yearsOfExperience;
    }

    public function setYearsOdExperience(int $value){
        $this->yearsOfExperience = $value;
    }

    public function getCreatedAt(){
        return $this->createdAt;
    }

    public function setCreatedAt(string $value){
        $this->createdAt = $value;
    }
}