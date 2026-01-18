<?php
namespace App\Controllers\Service;

use App\Core\Http\Exception\LocalErrorException;

class Validator {

    public function isString($value, string $fieldName = 'field') {
        if (!is_string($value)) throw new LocalErrorException("error", "validation", "avocat.log", 400, "invalid input",["message" => "Invalid input"]);
        }
        
    public function isNumber($value, string $fieldName = 'field') {
        if (!is_numeric($value)) throw new LocalErrorException("error", "validation", "avocat.log", 400, "invalid email",["message" => "Invalid input"]);
        }
        
    public function isValidEmail($value, string $fieldName = 'email') {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) throw new LocalErrorException("error", "validation", "avocat.log", 400, "invalid email",[ "message" => "Invalid input", "errors" => ["email" => "Invalid email"]]);
    }
}
