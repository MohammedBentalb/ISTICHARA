<?php


namespace App\Core\Jwt;

use App\Core\Http\Exception\LocalErrorException;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtManager {
    public function generate(array $payload){
        $createdAt = Carbon::now();
        $expiredAt = $createdAt->copy()->addMinutes(20);
        // $expiredAt = $createdAt->copy()->addMinutes(10);
        $payload['iat'] = $createdAt->timestamp;
        $payload['exp'] = $expiredAt->timestamp;
        return JWT::encode($payload, $_ENV['JWT_KEY'], "HS256");
    }

    public function generateRefresh(array $payload){
        $createdAt = Carbon::now();
        // $expiredAt = $createdAt->copy()->addMinutes(10);
        $expiredAt = $createdAt->copy()->addDays(3);
        $payload['iat'] = $createdAt->timestamp;
        $payload['exp'] = $expiredAt->timestamp;
        return JWT::encode($payload, $_ENV['JWT_REFRESH_KEY'], "HS256");
    }

    public function Verify(string $token, string $refreshToken, UserRepository $userRepo){
        try {
            $payload = (array) JWT::decode($token, new Key($_ENV['JWT_KEY'], "HS256"));
            return ["valid" => true, "payload" => $payload];
        } catch(ExpiredException $e){
            $refreshPayload = $this->verifyRefresh($refreshToken);
            $user = $userRepo->findOneBy(['email' => $refreshPayload['email']]);
            if(!$user) throw new LocalErrorException("user not found", "auth", "errors.log", 404, "user Not Found", ["message" => "user not found"]);
            return ["valid" => true, "payload" => (array) $refreshPayload];
        } catch(Exception $e){
            throw new LocalErrorException("invalid token", "auth", "errors.log", 400, "invalid token", ["message" => "invalid token"]);
        }
    }

    public function verifyRefresh(string $token){
        try {
            return (array) JWT::decode($token, new Key($_ENV['JWT_REFRESH_KEY'], "HS256"));
        } catch (\Throwable $th) {
            throw new LocalErrorException("unAuthorized", "auth", "auth.log", 401, "user Not Found", ["message" => "user not found"]);
        }
    }
}