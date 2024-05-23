<?php
namespace App\Controller;
use Firebase\JWT\JWT;
use App\Model;
class Auth {
    public function Login(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = json_decode(file_get_contents('php://input'), true);
            $username = $data['username'];
            $password = $data['password'];

            $user = new Model\User();
            $user->setUsername($username);
            $user->setPassword($password);
            $user = $user->authenticate();
            if($user) {
                $id_user = $user->getId();
                $key = "111111";
                $payload = array(                    
                    "id" => $id_user
                );
                $jwt = JWT::encode($payload, $key, 'HS256');
                echo json_encode(array("message" => "Successful login.", "jwt" => $jwt));
            } else {
                echo json_encode(array("message" => "Login failed."));
            }

            
        }
    }
    public function Logout(){
        echo "Logout";
    }
}
?>