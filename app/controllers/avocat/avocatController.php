<?php

namespace App\Controllers\Avocat;

use App\Core\Attributes\Route;

#[Route("/", ["GET"])]
class AvocatController {
 public function index(){
    echo "hello";
 }
}