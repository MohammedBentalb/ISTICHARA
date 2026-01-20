<?php

namespace App\controllers\Auth;

use App\Controllers\Service\ToArray;
use App\Controllers\Service\Validator;
use App\Core\Attributes\Route;
use App\Core\Attributes\RouteController;
use App\Core\Http\Exception\LocalErrorException;
use App\Core\Http\Request;
use App\Core\Jwt\JwtManager;
use App\Models\User;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Doctrine\DBAL\Types\VarDateTimeImmutableType;
use Random\Engine\Secure;

#[RouteController]
class AuthController {
    public function __construct(private UserRepository $userRepo, private JwtManager $JWTManager, private Validator $validator, private Request $request, private ToArray $toArray) {}
    
    #[Route("auth/register", ["POST"])]
    public function register(){
        $email = $this->request->getParam("email");
        $password = $this->request->getParam("password");
        $name = $this->request->getParam("name");
        
        $this->validator->isValidEmail($email);
        $this->validator->isString($password);
        $this->validator->isString($name);
        
        $foundUser = $this->userRepo->findOneBy(["email" => trim($email)]);
        if($foundUser) throw new LocalErrorException("duplicated email", "auth", "auth.log", 400, "email taken", ["message" => "email taken"]);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $payload = ["email" => trim($email), "name" => trim($name)];

        $jwtToken = $this->JWTManager->generate($payload);
        $refreshToken = $this->JWTManager->generateRefresh($payload);

        $user = new User(trim($name), trim($email), $hashedPassword, "GUEST");
        $this->userRepo->save($user);
        $user->setRefreshToken($refreshToken);
        setcookie("access_token", $jwtToken, ["expires" => (Carbon::now())->addMinutes(15)->timestamp, "httponly" => false]);
        setcookie( "refresh_token", $refreshToken, ["expires" => (Carbon::now())->addDays(7)->timestamp, "httponly" => false]);

        $userInfo = $this->toArray->objectToArray($user);
        unset($userInfo['password'], $userInfo['refresh_token']);
        echo json_encode(["status" => "succeess", "message" => "$name hassben registered", "data" => $userInfo]);
    }


    #[Route("auth/login", ["POST"])]
    public function login(){
        $email = $this->request->getParam("email");
        $password = $this->request->getParam("password");
        $this->validator->isValidEmail($email);
        $this->validator->isString($password);

        $user = $this->userRepo->findOneBy(["email" => trim($email)]);
        if(!$user) throw new LocalErrorException( "user not found", "auth", "auth.log", 404, "invalid credentials", ["message" => "Invalid email or password"]);
        if(!password_verify($password, $user->getPassword())) throw new LocalErrorException("wrong password", "auth", "auth.log", 401, "invalid credentials", ["message" => "Invalid email or password"]);
        
        $payload = [ "email" => $user->getEmail(), "name" => $user->getName()];

        $jwtToken = $this->JWTManager->generate($payload);
        $refreshToken = $this->JWTManager->generateRefresh($payload);

        $user->setRefreshToken($refreshToken);
        $this->userRepo->save($user);

        setcookie("access_token", $jwtToken, ["expires" => (Carbon::now())->addMinutes(15)->timestamp, "httponly" => false, "path" => "/", "samesite" => "lax","secure" => false ]);
        setcookie( "refresh_token", $refreshToken, ["expires" => (Carbon::now())->addDays(7)->timestamp, "httponly" => false, "path" => "/", "samesite" => "lax","secure" => false ]);

        $userInfo = $this->toArray->objectToArray($user);
        unset($userInfo['password'], $userInfo['refreshToken']);
        echo json_encode(["status" => "success", "message" => "Welcome Back " . $user->getName(), "data" => $userInfo]);
    }

}