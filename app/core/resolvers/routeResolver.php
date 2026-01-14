<?php 

namespace App\Core\Resolvers;

use App\Core\Container\Container;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Services\AuthentificationService;

class RouteResolver{
    public function __construct(private MethodResolver $methodResolver) {}

    public function resolveRoute(array $routeInfo, array $params){
        $controller = $routeInfo["controller"];
        $method = $routeInfo["method"];
    
        $args = $this->methodResolver->resolveParams($controller, $method);
        $params = [...$params, ...$args];
        call_user_func([Container::getInstance($controller), $method], ...$params);
    }
}