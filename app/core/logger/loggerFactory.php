<?php

namespace App\Core\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class LoggerFactory {

    private array $loggers = [];

    public function error(){
        return $this->get("errors" , "errors.log", Level::Error);
    }

    public function info(){
        return $this->get("info", "info.php" , Level::Info);
    }

    public function http(){
        return $this->get("http", "http.log" , Level::Info);
    }

    public function get(string $channel, $file, Level $level){
        if(isset($this->loggers[$channel])){
            return $this->loggers[$channel];
        }

        $logger = new Logger($channel);
        $logger->pushHandler(new StreamHandler( __DIR__ . "/../../logs/" . $file , $level, true, 0664));
        $this->loggers[$channel] = $logger;
        return $logger;
    }
}