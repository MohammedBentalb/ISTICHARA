<?php

namespace App\Core\Error;

use App\Core\Http\Exception\LocalErrorException;
use App\Core\Logger\LoggerFactory;
use Monolog\Level;
use Throwable;

class ErrorHandler {
    public function __construct(private LoggerFactory $loggerFactory) {}

    public function __invoke(Throwable $error){
        $logMessage = "Internal Server Error";
        $channel = "errors";
        $logFille = "errors.log";
        $status = 500;
        $level = Level::Error;
        $message = "Internal Errorrrrrr";
        $info = [];

        if($error instanceof LocalErrorException){
            $status = $error->getStatusCode();
            $channel = $error->getChannel();
            $level = $error->getLevel();
            $logMessage = $error->getLogMessage();
            $logFille = $error->getLogFile();
            $info = $error->getInfo();
            $message = $error->getPublicMessage();
        }

        $logger = $this->loggerFactory->get($channel, $logFille, $level);
        $logger->log($level, $logMessage, ["file" => $error->getFile(), "line" => $error->getLine(), "trace" => $error->getTraceAsString(), ...$info]);
        http_response_code($status);
        echo json_encode(["message" => $message]);
    }

}