<?php 

namespace App\Core\Resolvers;

use App\Core\Container\Container;
use App\Core\Logger\LogsSystem;
use App\Core\Middlewares\AuthentificationMiddleware;
use App\Core\Middlewares\AuthorisationMiddleware;

class RouteResolver{
    public function __construct(private MethodResolver $methodResolver, private LogsSystem $logsSystem) {}

    public function resolveRoute(array $routeInfo, array $params){
        $this->logsSystem->logRequest();
        $controller = $routeInfo["controller"];
        $method = $routeInfo["method"];
        $roles = $routeInfo["role"];
        $requireAuth = $routeInfo["auth"];

        $user = Container::getInstance(AuthentificationMiddleware::class)->handle($requireAuth);
        Container::getInstance(AuthorisationMiddleware::class)->handle($user, $roles);


        $args = $this->methodResolver->resolveParams($controller, $method);
        $params = [...$params, ...$args];
        call_user_func([Container::getInstance($controller), $method], ...$params);
    }
}