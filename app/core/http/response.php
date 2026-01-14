<?php

namespace App\Core\Http;


class Response {
    public function header($path){
        header("Location: $path");
        exit();
    }
}