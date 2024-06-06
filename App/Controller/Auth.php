<?php
namespace App\Controller;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Model\User;
use Exception;
class Auth extends Rest {
    public function __construct() {
        parent::__construct();
    }
    public function Login(){
      try {
        
        if(!isset($this->request['username']) || !isset($this->request['password'])) {
            $this->response(['message' => 'Invalid username or password'], INVALID_USER_PASS);
        }

        $user = new User();

       

        $user->setUsername($this->request['username']);
        $user->setPassword($this->request['password']);
        $user = $user->authenticate();
        if($user) {
            $id_user = $user->getId();
            //login success generate token in database
            
            $key = "111111";
            $payload = array(                    
                "id" => $id_user
            );
            $jwt = JWT::encode($payload, $key, 'HS256');
            $user->setAccessToken($jwt);
            $user->updateAccessToken();
            //chuyển đối tượng user thành json
            $user = json_decode(json_encode($user), true);
            $this->response(['jwt' => $jwt]);
        } else {
            $this->response(['message' => 'Invalid username or password'], INVALID_USER_PASS);
        }
      } catch (Exception $e) {
        $this->response($e->getMessage(), 500);
      }

            

            
        
    }
    public function Logout(){
        $jwt = $this->jwt;
        if($jwt) {
            $key = "111111";
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            
            $user = new User();
            $user->setId($decoded->id);
            $user->updateAccessToken();

            $this->response(['message' => 'Logout success']);
        } else {
            $this->response(['message' => 'Invalid token'], ATHORIZATION_HEADER_NOT_FOUND);
        }
    }
}
?>