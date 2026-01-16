<?php

namespace App\Controllers\Huissier;

use App\Controllers\service\ToArray;
use App\Controllers\Service\Validator;
use App\Core\Attributes\Route;
use App\Core\Attributes\RouteController;
use App\Core\Http\Request;
use App\Models\Huissier;
use App\Repository\HuissierRepository;
use App\Repository\VilleRepository;

#[RouteController]
class HuissierController {
 
   public function __construct(private Validator $validator, private VilleRepository $villeRepo, private HuissierRepository $huissierRepo, private ToArray $toArray, private Request $request) {}

   #[Route("/huissiers", ["GET"])]
   public function showHuissiers(){
      $huissier = $this->huissierRepo->findAll();
      $result = ($this->toArray->objectToArray($huissier));
      echo json_encode($result);
      }
      
   #[Route("/huissiers/get/{id}", ["GET"])]
   public function showOneHuissier(int $id){
      $huissier = $this->huissierRepo->find($id);    
      if(!$huissier){
         http_response_code(404);
         echo json_encode(["message" => "huissier $id not found"]);
         exit();
      }
      $result = ($this->toArray->objectToArray($huissier));
      echo json_encode($result);
   }


   
   #[Route("/huissiers/create", ["POST"])]
   public function createHuissier(){
      if($this->request->getRequestType() === "POST"){
         $name = $this->request->getParam("name");
         $email = $this->request->getParam("email");
         $villeId = $this->request->getParam("ville");
         $yearsOfExperience = $this->request->getParam("experience");
         $actes = $this->request->getParam("types_actes");
        
         $this->validator->isString($name);
         $this->validator->isString($actes);
         $this->validator->isValidEmail($email);
         $this->validator->isNumber($villeId);
         $this->validator->isNumber($yearsOfExperience);

         $huissier = new Huissier($actes, $name, $email, (int) $yearsOfExperience, null);
                  
         $foundVulle = $this->villeRepo->find($villeId);
         if(!$foundVulle){
            http_response_code(404);
            echo json_encode(["message" => "city is not defined"]);
            exit();
         }  
         $this->huissierRepo->save($huissier, $villeId);
         echo json_encode("hi there everything is cool");
      }
   }

   #[Route("/huissiers/update/{id}", ["POST"])]
   public function updateAvocat($id){
      if($this->request->getRequestType() === "POST"){
         
         $name = $this->request->getParam("name");
         $email = $this->request->getParam("email");
         $villeId = $this->request->getParam("ville");
         $yearsOfExperience = $this->request->getParam("experience");
         $actes = $this->request->getParam("types_actes");

         $foundHuissier = $this->huissierRepo->find($id);
         if(!$foundHuissier){
            http_response_code(404);
            echo json_encode(["message" => "Huissier $id not found"]);
            exit();
         }   

         $foundHuissier->setName($name);
         $foundHuissier->setEmail($email);
         $foundHuissier->setYearsOfExperience($yearsOfExperience);
         $foundHuissier->setTypesSctes($actes);
         
         $foundVille = $this->villeRepo->find($villeId);
         if(!$foundVille){
            http_response_code(404);
            echo json_encode(["message" => "city is not defined"]);
            exit();
         }  

         $this->huissierRepo->update($foundHuissier, $villeId);
         echo json_encode("hi there everything is cool");
      }
   }

   #[Route("/huissiers/delete/{id}", ["POST"])]
   public function deletHuissier($id){
      $foundHuissier = $this->huissierRepo->find($id);
      if(!$foundHuissier){
         http_response_code(404);
         echo json_encode(["message" => "avocat $id not found"]);
         exit();
      }
         $this->huissierRepo->delete($foundHuissier);
         echo json_encode(["state" => "success", "message" => "avocat got deleted"]);
   }

}