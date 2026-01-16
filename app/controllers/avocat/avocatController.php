<?php

namespace App\Controllers\Avocat;

use App\Controllers\service\toArray;
use App\controllers\service\Validator;
use App\Core\Attributes\Route;
use App\Core\Attributes\RouteController;
use App\Core\Http\Request;
use App\Models\Avocat;
use App\Repository\avocatRepository;
use App\Repository\VilleRepository;

#[RouteController]
class AvocatController {
public function __construct(private Validator $validator, private VilleRepository $villeRepo,  private Request $request, private avocatRepository $avocaRepo, private ToArray $toArray) {}

   #[Route("/avocats", ["GET"])]
   public function showAvocats(){
      $searchQuery = $this->request->getQuery("search");
      $filterQuery = $this->request->getQuery("filter");
      $limit = $this->request->getQuery("limit");
      $offset = $this->request->getQuery("offset");
      $avocats = $this->avocaRepo->search($searchQuery, $filterQuery, $limit, $offset);    
      echo json_encode($avocats);
   }

   #[Route("/avocats/get/{id}", ["GET"])]
   public function showOneAvocat(int $id){
      $avocat = $this->avocaRepo->find($id); 
      if(!$avocat){
         http_response_code(404);
         echo json_encode(["message" => "avocat $id not found"]);
         exit();
      }   
      $result = ($this->toArray->objectToArray($avocat));
      echo json_encode(["status" => "success", "data" => $result]);
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
         echo json_encode(["state" => "success", "message" => "avocat got inserted"]);
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
         if(!$foundAvocat){
            http_response_code(404);
            echo json_encode(["message" => "avocat $id not found"]);
            exit();
         }   

         $foundAvocat->setName($name);
         $foundAvocat->setEmail($email);
         $foundAvocat->setYearsOfExperience($yearsOfExperience);
         $foundAvocat->setSpecialite($specialty);
         $foundAvocat->setConsultationEnLigne($consulting === "true");
                  
         $foundVille = $this->villeRepo->find($villeId);
         if(!$foundVille){
            http_response_code(404);
            echo json_encode(["message" => "city is not defined"]);
            exit();
         }  

         $this->avocaRepo->update($foundAvocat, $villeId);
         echo json_encode(["state" => "success", "message" => "avocat got updated"]);
      }
   }

   #[Route("/avocats/delete/{id}", ["POST"])]
   public function DeletAvocat($id){
      $foundAvocat = $this->avocaRepo->find($id);
      if(!$foundAvocat){
         http_response_code(404);
         echo json_encode(["message" => "avocat $id not found"]);
         exit();
      }
      $this->avocaRepo->delete($foundAvocat);
      echo json_encode(["state" => "success", "message" => "avocat got deleted"]);
   }
}