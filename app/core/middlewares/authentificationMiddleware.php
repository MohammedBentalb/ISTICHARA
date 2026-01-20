<?php

namespace App\Core\Middlewares;

use App\Core\Http\Exception\LocalErrorException;
use App\Repository\UserRepository;
use App\Core\Jwt\JwtManager;
use App\Models\User;

class AuthentificationMiddleware {
    public function __construct(private JwtManager $JWTManager, private UserRepository $userRepo){}
    public function handle(bool $requiredAuth): ?User {
        if(!$requiredAuth) return null;

        $token = $_COOKIE['access_token'] ?? null;
        $refreshToken = $_COOKIE['refresh_token'] ?? null;
        if(!$token || !$refreshToken) throw new LocalErrorException('Bad request', 'auth', "auth.log", 400, "Bad, no cookies sent", ["message" => "Bad"]);
        $verify = $this->JWTManager->Verify($token, $refreshToken, $this->userRepo);
        $user = $this->userRepo->findOneBy(['email' => $verify['payload']['email']]);
        return $user;
    }
}