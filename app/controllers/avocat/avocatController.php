<?php

namespace App\Controllers\Avocat;

use App\Core\Attributes\Route;
use App\Core\Attributes\RouteController;

#[RouteController]
class AvocatController {
   #[Route("/", ["GET"])]
 public function index(){
    echo "hello";
 }
}