<?php

namespace App\Core\Http;

class Request{
    public function getRequestType(){
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getPath(){
        return $_SERVER['REQUEST_URI'];
    }
    public function getClearPath(){
        return trim(parse_url($this->getPath(), PHP_URL_PATH), "/");
    }

    public function getQuery($query){
        return isset($_GET[$query]) ? $_GET[$query] : null;
    }

    public function getParam($param){
        return isset($_POST[$param]) ? $_POST[$param] : null;
    }

    public function getPreviousPath(){
        return htmlspecialchars($_SERVER['HTTP_REFERER'], ENT_QUOTES, 'UTF-8');
    }

    public function getOrigin(){
        return $_SERVER['REMOTE_ADDR'] ?? "UNKNOWN";
    }
}