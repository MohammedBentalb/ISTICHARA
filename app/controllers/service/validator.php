<?php
namespace App\Controllers\Service;

class Validator {

    public function isString($value, string $fieldName = 'field') {
        if (!is_string($value)) {
            http_response_code(400);
            echo json_encode(["message" => "Invalid input", "errors" => [$fieldName => "Expected string"]]);
            exit();
        }
    }

    public function isNumber($value, string $fieldName = 'field') {
        if (!is_numeric($value)) {
            http_response_code(400);
            echo json_encode([ "message" => "Invalid input", "errors" => [$fieldName => "Expected number"]]);
            exit();
        }
    }

    public function isValidEmail($value, string $fieldName = 'email') {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode([ "message" => "Invalid input", "errors" => [$fieldName => "Invalid email"]]);
            exit();
        }
    }
}
