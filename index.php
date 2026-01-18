<?php

use App\Core\Container\Container;
use App\Core\Error\ErrorHandler;
use App\Core\Router\Router;
use Dotenv\Dotenv;

require __DIR__ . "/vendor/autoload.php";
(Dotenv::createImmutable(__DIR__))->load();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

set_exception_handler(Container::getInstance(ErrorHandler::class));

$route = Container::getInstance(Router::class);
$route->CollectRoutes();
$route->dispatch();

