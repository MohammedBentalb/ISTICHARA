<?php

namespace App\Controllers\Huissier;

use App\Controllers\service\ToArray;
use App\Controllers\Service\Validator;
use App\Core\Attributes\Route;
use App\Core\Attributes\RouteController;
use App\Core\Http\Exception\LocalErrorException;
use App\Core\Http\Request;
use App\Core\Logger\LogsSystem;
use App\Models\Huissier;
use App\Repository\HuissierRepository;
use App\Repository\VilleRepository;

#[RouteController]
class HuissierController {
 
   public function __construct(private LogsSystem $logsSystem, private Validator $validator, private VilleRepository $villeRepo, private HuissierRepository $huissierRepo, private ToArray $toArray, private Request $request) {}

   #[Route("/huissiers", ["GET"])]
   public function showHuissiers(){
      $searchQuery = $this->request->getQuery("search");
      $filterQuery = $this->request->getQuery("filter");
      $limit = $this->request->getQuery("limit");
      $offset = $this->request->getQuery("offset");
      $huissier = $this->huissierRepo->search($searchQuery, $filterQuery, $limit, $offset);    
      echo json_encode($huissier);
   }
      
   #[Route("/huissiers/get/{id}", ["GET"], true, ['ADMIN'])]
   public function showOneHuissier(int $id){
      $huissier = $this->huissierRepo->find($id); 
      if(!$huissier) throw new LocalErrorException("error", "huissier", "huissier.log", 404, "huissier $id not found", ["message" => "Huissier $id not found"]);

      $result = ($this->toArray->objectToArray($huissier));
      $send = ["status" => "success", "data" => $result];

      $context = ["sent" => $send, "receiver" => $this->request->getOrigin() ,"location" => HuissierController::class, "classMethod" => "showOneHuissier"];
      $this->logsSystem->logResponse($context);
      echo json_encode($send);
   }
   
   #[Route("/huissiers/create", ["POST"], true, ['ADMIN'])]
   public function createHuissier(){
      if($this->request->getRequestType() === "POST"){
         $name = $this->request->getParam("name");
         $email = $this->request->getParam("email");
         $villeId = $this->request->getParam("ville_id");
         $yearsOfExperience = $this->request->getParam('yearsOfExperience');
         $actes = $this->request->getParam("types_actes");
         
         $this->validator->isString($name);
         $this->validator->isString($actes);
         $this->validator->isValidEmail($email);
         $this->validator->isNumber($villeId);
         $this->validator->isNumber($yearsOfExperience);

         $foundHuissier = $this->huissierRepo->findOneBy(["email" => trim($email)]);
         if($foundHuissier) throw new LocalErrorException("duplicated email", "huissier", "huissier.log", 400, "email taken", ["message" => "email taken"]);

         $huissier = new Huissier($actes, $name, $email, (int) $yearsOfExperience, null);
                  
         $foundVulle = $this->villeRepo->find($villeId);
         if(!$foundVulle)  throw new LocalErrorException("error", "huissier", "huissier.log", 404, "city $villeId not found", ["message" => "ville $villeId not found"]);
         
         $this->huissierRepo->save($huissier, $villeId);
         echo json_encode(["status" => 'success', "message" => "huissier got inserted"]);
      }
   }

   #[Route("/huissiers/update/{id}", ["POST"], true, ['ADMIN'])]
   public function updateAvocat($id){
      if($this->request->getRequestType() === "POST"){
         
         $name = $this->request->getParam("name");
         $email = $this->request->getParam("email");
         $villeId = $this->request->getParam("ville_id");
         $yearsOfExperience = $this->request->getParam('yearsOfExperience');
         $actes = $this->request->getParam("typesActes");


         $foundHuissier = $this->huissierRepo->find($id);
         if(!$foundHuissier)  throw new LocalErrorException("error", "huissier", "huissier.log", 404, "huissier $id not found", ["message" => "Huissier $id not found"]);
         
         $foundHuissier->setName($name);
         $foundHuissier->setEmail($email);
         $foundHuissier->setYearsOfExperience($yearsOfExperience);
         $foundHuissier->setTypesSctes($actes);
         
         $foundVille = $this->villeRepo->find($villeId);
         if(!$foundVille) throw new LocalErrorException("error", "huissier-ville", "huissier.log", 404, "ville $villeId not found", ["message" => "Ville $villeId not found"]);

         $this->huissierRepo->update($foundHuissier, $villeId);
         echo json_encode("hi there everything is cool");
      }
   }

   #[Route("/huissiers/delete/{id}", ["POST"], true, ['ADMIN'])]
   public function deletHuissier($id){
      $foundHuissier = $this->huissierRepo->find($id);
      if(!$foundHuissier) throw new LocalErrorException("error", "huissier", "huissier.log", 404, "huissier $id not found", ["message" => "Huissier $id not found"]);
      $this->huissierRepo->delete($foundHuissier);
      echo json_encode(["status" => "success", "message" => "avocat got deleted"]);
   }
}