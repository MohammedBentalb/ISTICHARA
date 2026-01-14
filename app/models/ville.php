<?php

namespace App\Models;

class Ville {
    
private ?int $id = null;
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