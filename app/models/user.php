<?php

namespace App\Models;

use App\Core\Attributes\Preserve;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "users")]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", unique: true)]
    #[Preserve]
    private ?int $id = null;
    
    #[Preserve]
    #[ORM\Column(type: "string", length: 150)]
    private string $name;
    
    #[Preserve]
    #[ORM\Column(type: "string", length: 180, unique: true)]
    private string $email;
    
    #[Preserve]
    #[ORM\Column(type: "string")]
    private string $password;
    
    #[Preserve]
    #[ORM\Column(type: "string", length: 50)]
    private string $role;
    
    #[Preserve]
    #[ORM\Column(type: "string", nullable: true, name: "refresh_token")]
    private ?string $refreshToken = null;

    public function __construct(string $name, string $email, string $password, string $role) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRole() {
        return $this->role;
    }

    public function getRefreshToken() {
        return $this->refreshToken;
    }

    public function setName(string $name) {
        $this->name = $name;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }

    public function setRole(string $role) {
        $this->role = $role;
    }

    public function setRefreshToken(?string $refreshToken) {
        $this->refreshToken = $refreshToken;
    }
}
