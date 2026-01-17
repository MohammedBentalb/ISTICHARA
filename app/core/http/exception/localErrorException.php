<?php

namespace App\Core\Http\Exception;

use Exception;
use Monolog\Level;

class LocalErrorException extends Exception{
    public function __construct(private string $logMessage, private string $channel, private string $logFile, private int $statusCode, private string $publicMessage, private array $info = [], private Level $level = Level::Error) {
        parent::__construct($this->publicMessage);
    }

    public function getLogMessage(){
        return $this->logMessage;
    }
    
    public function getPublicMessage(){
        return $this->publicMessage;
    }

    
    public function getStatusCode(){
        return $this->statusCode;
    }
  
    public function getChannel(){
        return $this->channel;
    }
  
    public function getLevel(){
        return $this->level;
    }

    public function getLogFile(){
        return $this->logFile;
    }

    public function getInfo(){
        return $this->info;
    }
}


    