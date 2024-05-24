<?php
namespace App\Controller;
use Firebase\JWT\JWT;
use App\Model\User;
class Auth extends Rest {
    public function Login(){
      

            $user = new User();
            $user->setUsername($this->request['username']);
            $user->setPassword($this->request['password']);
            $user = $user->authenticate();
            if($user) {
                $id_user = $user->getId();
                $key = "111111";
                $payload = array(                    
                    "id" => $id_user
                );
                $jwt = JWT::encode($payload, $key, 'HS256');
                $this->response(['jwt' => $jwt]);
            } else {
                $this->response(['message' => 'Invalid username or password'], INVALID_USER_PASS);
            }

            
        
    }
    public function Logout(){
        echo "Logout";
    }
}
?>