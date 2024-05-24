<?php
namespace App\Controller;
use Firebase\JWT\JWT;
require_once(__DIR__. "/../Core/config.php");
class Rest {
    protected $request;
    protected $jwt;

     function __construct() {
        $this->getRequest();
    }
    private function getRequest() {
        $data = json_decode(file_get_contents('php://input'), true);

        $this->validateRequest();
        $this->request = $data;
    }
    private function validateRequest() {
        $header = apache_request_headers();
        
       
        if($header['Content-Type'] !== 'application/json') {
            $this->response(['message' => 'Invalid content type'], REQUEST_CONTENTTYPE_NOT_VALID);
            exit;
        }
        
    }
    protected function response($data, $status = 200) {
        header("Content-Type: application/json");
        
        echo json_encode(['respone' => ['status' => $status, 'data' => $data]]);
    }

    private function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
    
    /**
     * get access token from header
     * */
    protected function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                $this->jwt = $matches[1];
            }
        }
        
    }

}
?>