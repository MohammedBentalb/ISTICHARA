<?php

namespace App\Core\Router;

use App\Core\Attributes\Route;
use App\Core\Attributes\RouteController;
use App\Core\Http\Exception\LocalErrorException;
use App\Core\Http\Request;
use App\Core\Resolvers\RouteResolver;
use Exception;
use ReflectionClass;

class Router {
    public function __construct(private RouteResolver $routeResolver, private Request $request) {}
    private array $routes = [];

    public function CollectRoutes(){
        $filesPath = glob("./app/controllers/**/*.php");
        foreach($filesPath as $filePath){
            $className = ucwords(str_replace(["./app", ".php", "/"], [ "App", "", "\\"], $filePath), "\\");
            $ref = new ReflectionClass($className);
            if(!$ref->isInstantiable()) throw new Exception("$className is not an actual class");
            
            $isRouteController = $ref->getAttributes(RouteController::class) ? true : false;
            
            if(!$isRouteController) continue;
            foreach($ref->getMethods() as $method){
                if($method->isConstructor()) continue;
                $routeAttribute = $method->getAttributes(Route::class)[0] ?? null;
                if(!$routeAttribute) throw new Exception("{$method->getName()} is not an actual class");
                $requestMethods =  $routeAttribute->newInstance()->method;
                $routePath = $routeAttribute->newInstance()->path;
                $auth = $routeAttribute->newInstance()->auth;
                $role = $routeAttribute->newInstance()->role;
                $this->routes[] = ["method" => $method->getName(), "controller" => $className, "requestMethods" => $requestMethods, "routePath" => $routePath, "auth" => $auth, "role" => $role];
            }
        }
    }

    public function dispatch(){
        $url = $this->request->getClearPath() ;
        $params = [];
        foreach($this->routes as $route){
            if($route["routePath"] === "/" && $url === "") $url = "/";
            $pathregex = "#^" . str_replace("{id}", "(\d+)", trim($route["routePath"], "/")) . "$#";
            if(preg_match($pathregex, $url, $params)){
                array_shift($params);
                $this->routeResolver->resolveRoute($route, $params);
                exit();
            }
        }
        throw new LocalErrorException("undefined Route", 'router', "router.log", 404, "undefined route", ["message" => "undefined route"]);
    }
}