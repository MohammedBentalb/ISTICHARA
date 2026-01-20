<?php

namespace App\Controllers\ville;

use App\Controllers\Service\ToArray;
use App\Controllers\Service\Validator;
use App\Core\Attributes\Route;
use App\Core\Attributes\RouteController;
use App\Core\Http\Request;
use App\Core\Logger\LogsSystem;
use App\Repository\AvocatRepository;
use App\Repository\VilleRepository;

#[RouteController]
class VilleController {
public function __construct(private LogsSystem $logsSystem, private Validator $validator, private VilleRepository $villeRepo,  private Request $request, private AvocatRepository $avocaRepo, private ToArray $toArray) {}

   #[Route("/villes", ["GET"])]
   public function allVilles(){

      $ville = $this->villeRepo->findAll();
      $context = ["receiver" => $this->request->getOrigin() ,"location" => VilleController::class, "classMethod" => "allVilles"];
      $this->logsSystem->logResponse($context);
      echo json_encode($this->toArray->objectToArray($ville));
   }
}