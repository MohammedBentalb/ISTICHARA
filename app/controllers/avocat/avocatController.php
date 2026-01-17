<?php

namespace App\Controllers\Avocat;

use App\Controllers\Service\ToArray;
use App\Controllers\Service\Validator;
use App\Core\Attributes\Route;
use App\Core\Attributes\RouteController;
use App\Core\Http\Exception\LocalErrorException;
use App\Core\Http\Request;
use App\Core\Logger\LogsSystem;
use App\Models\Avocat;
use App\Repository\AvocatRepository;
use App\Repository\VilleRepository;

#[RouteController]
class AvocatController {
public function __construct(private LogsSystem $logsSystem, private Validator $validator, private VilleRepository $villeRepo,  private Request $request, private AvocatRepository $avocaRepo, private ToArray $toArray) {}

   #[Route("/avocats", ["GET"])]
   public function showAvocats(){
      $searchQuery = $this->request->getQuery("search");
      $filterQuery = $this->request->getQuery("filter");
      $limit = $this->request->getQuery("limit");
      $offset = $this->request->getQuery("offset");
      $avocats = $this->avocaRepo->search($searchQuery, $filterQuery, $limit, $offset);    

      $context = ["receiver" => $this->request->getOrigin() ,"location" => AvocatController::class, "classMethod" => "showOneAvocat"];
      $this->logsSystem->logResponse($context);
      echo json_encode($avocats);
   }

   #[Route("/avocats/get/{id}", ["GET"])]
   public function showOneAvocat(int $id){
      $avocat = $this->avocaRepo->find($id); 
      if(!$avocat) throw new LocalErrorException("error", "avocat", "avocat.log", 404, "avocat $id not found");

      $result = ($this->toArray->objectToArray($avocat));
      $send = ["status" => "success", "data" => $result];

      $context = ["sent" => $send, "receiver" => $this->request->getOrigin() ,"location" => AvocatController::class, "classMethod" => "showOneAvocat"];
      $this->logsSystem->logResponse($context);
      
      echo json_encode($send);
   }

   #[Route("/avocats/create", ["POST"])]
   public function createAvocat(){
      if($this->request->getRequestType() === "POST"){
         $name = $this->request->getParam("name");
         $email = $this->request->getParam("email");
         $villeId = $this->request->getParam("ville");
         $yearsOfExperience = $this->request->getParam("experience");
         $specialty = $this->request->getParam("specialty");
         $consulting = $this->request->getParam("consulting");

         $this->validator->isString($name);
         $this->validator->isValidEmail($email);
         $this->validator->isNumber($villeId);
         $this->validator->isNumber($yearsOfExperience);
         $this->validator->isString($specialty);

         $avocat = new Avocat($consulting === "true", $specialty, $name, $email, null, (int) $yearsOfExperience, null);
         $this->avocaRepo->save($avocat, $villeId);
         $send = ["state" => "success", "message" => "avocat got inserted"];
      
         $context = ["sent" => $send, "receiver" => $this->request->getOrigin(), "location" => AvocatController::class, "classMethod" => "createAvocat"];
         $this->logsSystem->logResponse($context);      
         echo json_encode($send);
      }
   }

   #[Route("/avocats/update/{id}", ["POST"])]
   public function updateAvocat($id){
      if($this->request->getRequestType() === "POST"){
         
         $name = $this->request->getParam("name");
         $email = $this->request->getParam("email");
         $villeId = $this->request->getParam("ville");
         $yearsOfExperience = $this->request->getParam("experience");
         $specialty = $this->request->getParam("specialty");
         $consulting = $this->request->getParam("consulting");

         $foundAvocat = $this->avocaRepo->find($id);
         if(!$foundAvocat) throw new LocalErrorException("error", "avocat", "avocat.log", 404, "avocat $id not found", ["message" => "avocat $id not found"]); 

         $foundAvocat->setName($name);
         $foundAvocat->setEmail($email);
         $foundAvocat->setYearsOfExperience($yearsOfExperience);
         $foundAvocat->setSpecialite($specialty);
         $foundAvocat->setConsultationEnLigne($consulting === "true");
            
         $foundVille = $this->villeRepo->find($villeId);
         if(!$foundVille) throw new LocalErrorException("error", "avocat", "avocat.log", 404, "Ville $id not found", ['message' => "ville $id not found"]);

         $this->avocaRepo->update($foundAvocat, $villeId);
         $send = ["state" => "success", "message" => "avocat got updated"];

         $context = ["sent" => $send, "receiver" => $this->request->getOrigin() ,"location" => AvocatController::class, "classMethod" => "updateAvocat"];
         $this->logsSystem->logResponse($context);
         
         echo json_encode($send);
      }
   }

   #[Route("/avocats/delete/{id}", ["POST"])]
   public function deletAvocat($id){
      $foundAvocat = $this->avocaRepo->find($id);
      if(!$foundAvocat) throw new LocalErrorException("error", "avocat", "avocat.log", 400, "avocat $id not found", ["message" => "avocat $id not found"]);

      $this->avocaRepo->delete($foundAvocat);
      $send = ["state" => "success", "message" => "avocat got deleted"]; 
      
      $context = ["sent" => $send, "receiver" => $this->request->getOrigin() ,"location" => AvocatController::class, "classMethod" => "deleteAvocat"];
      $this->logsSystem->logResponse($context);
    
      echo json_encode($send);
   }
}