<?php
namespace App\Controller;
use Firebase\JWT\JWT;
use \Exception;

require_once(__DIR__ . "/../Core/config.php");
require_once(__DIR__ . "/../../writeLog.php");

class Rest {
    protected $request;
    protected $jwt;
    protected $LogFunc;

    function __construct() {
        $this->LogFunc = new \Log;
        $this->getRequest();
        $this->getBearerToken();
    }

    private function getRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $data = json_decode(file_get_contents('php://input'), true);
            $this->validateRequest();
            $this->request = $data;
        } else {
            $this->request = $_GET; // For GET requests, use query parameters
        }
    }

    private function validateRequest() {
        $header = apache_request_headers();
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            if (!isset($header['Content-Type'])) {
                $this->response(['message' => 'Content type is not set'], REQUEST_CONTENTTYPE_NOT_VALID);
            }

            if (strpos($header['Content-Type'], 'application/json') === false) {
                $this->response(['message' => 'Invalid content type'], REQUEST_CONTENTTYPE_NOT_VALID);
            }
        }
    }

    protected function response($data, $status = 200) {
        header("Content-Type: application/json; Charset=UTF-8");
        $rawResponse = ['response' => ['status' => $status, 'data' => $data]];
        $logJson = json_encode($rawResponse);
        $this->LogFunc->writeLogRes($logJson);
        echo json_encode($rawResponse);
        exit;
    }

    private function getAuthorizationHeader() {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { // Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    protected function getBearerToken() {
        try {
            $headers = $this->getAuthorizationHeader();
            if (!empty($headers)) {
                if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                    $this->jwt = $matches[1];
                }
            }
        } catch (Exception $e) {
            $this->response($e->getMessage(), 500);
        }
    }
}

?>