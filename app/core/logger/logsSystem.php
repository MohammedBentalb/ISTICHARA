<?php 

namespace App\Core\Logger;

use App\Core\Http\Request;

class LogsSystem {
    public function __construct(private LoggerFactory $loggerFactory, private Request $request) {}

    public function logResponse(array $additionalInfo = [], int $status = 200){
        $info = ["status" => $status, ...$additionalInfo];
        $logger = $this->loggerFactory->http();
        $logger->info("Request sent", $info);
    }

    public function logRequest(array $additionalInfo = []){
        $info = ["method" => $this->request->getRequestType(), "uri" => $this->request->getClearPath(), "body" => $_POST, "queries" => $_GET, ...$additionalInfo];
        $logger = $this->loggerFactory->http();
        $logger->info("Request received", $info);
    }

    public function logError(array $info){
        $logger = $this->loggerFactory->error();
        $logger->info("ERROR OCCURRED", $info);
    }
}