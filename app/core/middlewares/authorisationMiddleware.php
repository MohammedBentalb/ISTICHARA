<?php 

namespace App\Core\Middlewares;

use App\Core\Http\Exception\LocalErrorException;
use App\Models\User;

class AuthorisationMiddleware {

    public function handle(?User $user, array $roles){
        if(!$user) return;
        if(!in_array($user->getRole(), $roles)){
            throw new LocalErrorException('unAuthorized user', "auth", "auth.log", 401, "unauthorized to access that route", ["message" => "unauthorized to access that route"]);
        }
    }
}