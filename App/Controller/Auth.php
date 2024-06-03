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
    public function login(){
        try {
            // Kiểm tra xem username và password có được gửi đi không
            if(!isset($this->request['username']) || !isset($this->request['password'])) {
                $this->response(['message' => 'Invalid username or password!'], INVALID_USER_PASS);
            }
    
            // Khởi tạo một đối tượng User
            $user = new User();
    
            // Đặt username và password từ dữ liệu nhận được
            $user->setUsername($this->request['username']);
            $user->setPassword($this->request['password']);
    
            // Thực hiện xác thực tài khoản
            $authenticatedUser = $user->authenticate();
    
            if($authenticatedUser) {
                // Lấy id của người dùng xác thực thành công
                $userId = $authenticatedUser->getId();
                
                // Tạo JWT
                $key = "111111";
                $payload = array("id" => $userId);
                $jwt = JWT::encode($payload, $key, 'HS256');
                
                // Cập nhật JWT vào cơ sở dữ liệu
                $authenticatedUser->setAccessToken($jwt);
                $authenticatedUser->updateAccessToken();
                
                // Trả về JWT trong phản hồi
                $this->response(['jwt' => $jwt]);
            } else {
                // Xác thực không thành công
                $this->response(['message' => 'Invalid username or password!!'], INVALID_USER_PASS);
            }
        } catch (Exception $e) {
            // Xử lý lỗi nếu có
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

    public function register() {
        try {
            
            // Check if all required fields are present
            $requiredFields = ['username', 'email', 'password', 'confirmPassword', 'name', 'phone', 'address'];
            foreach ($requiredFields as $field) {
                if (!isset($this->request[$field])) {
                    $this->response(['message' => "$field is required"], 400);
                    return;
                }
            }

            $username = $this->request['username'];
            $email = $this->request['email'];
            $password = $this->request['password'];
            $confirmPassword = $this->request['confirmPassword'];
            $name = $this->request['name'];
            $phone = $this->request['phone'];
            $address = $this->request['address'];

            // Check if passwords match
            if ($password !== $confirmPassword) {
                $this->response(['message' => 'Passwords do not match'], 400);
                return;
            }

            // Create a new User object
            $user = new User();
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPassword($password); // The password will be hashed in the addUser method
            $user->setName($name);
            $user->setPhone($phone);
            $user->setAddress($address);

            // Check if the user already exists
            if ($user->getUserByUserName() || $user->getUserByEmail()) {
                $this->response(['message' => 'User already exists'], 400);
                return;
            }

            // Add the user to the database
            $userId = $user->addUser();
            
            if ($userId) {
                $this->response(['message' => 'User registered successfully'], 201);
            } else {
                $this->response(['message' => 'Failed to register user'], 500);
            }
        } catch (Exception $e) {
            $this->response(['message' => $e->getMessage()], 500);
        }
    }
}
?>