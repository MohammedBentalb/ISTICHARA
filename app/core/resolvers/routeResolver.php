<?php 

namespace App\Core\Resolvers;

use App\Core\Container\Container;
use App\Core\Logger\LogsSystem;

class RouteResolver{
    public function __construct(private MethodResolver $methodResolver, private LogsSystem $logsSystem) {}

    public function resolveRoute(array $routeInfo, array $params){
        $this->logsSystem->logRequest();
        $controller = $routeInfo["controller"];
        $method = $routeInfo["method"];

    
        $args = $this->methodResolver->resolveParams($controller, $method);
        $params = [...$params, ...$args];
        call_user_func([Container::getInstance($controller), $method], ...$params);
    }
}