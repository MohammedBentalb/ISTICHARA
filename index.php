<?php

use App\Core\Container\Container;
use App\Core\Router\Router;
use Dotenv\Dotenv;

require __DIR__ . "/vendor/autoload.php";

(Dotenv::createImmutable(__DIR__))->load();


$route = Container::getInstance(Router::class);
$route->CollectRoutes();
$route->dispatch();

