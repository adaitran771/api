<?php
   namespace App\Model;

    use App\Core\Database;
    use PDO;
    use PDOException;
   


    class User extends Database {
        private $username;
        private $password;
        private $role;
        private $name;
        private $email;
        private $phone;
        private $address;
        private $id;
        private $AccessToken;
        public function __construct() {
            
            parent::__construct();
        }
        // Phương thức getter cho $username
        public function getUsername() {
            return $this->username;
        }

        // Phương thức setter cho $username
        public function setUsername($username) {
            $this->username = $username;
        }

      
        public function getPassword() {
            return $this->password;
        }

        public function setPassword($password) {
            $this->password = $password;
        }

        public function getRole() {
            return $this->role;
        }

        public function setRole($role) {
            $this->role = $role;
        }

        public function getName() {
            return $this->name;
        }

        public function setName($name) {
            $this->name = $name;
        }

        public function getEmail() {
            return $this->email;
        }

        public function setEmail($email) {
            $this->email = $email;
        }

        public function getPhone() {
            return $this->phone;
        }

        public function setPhone($phone) {
            $this->phone = $phone;
        }

        public function getAddress() {
            return $this->address;
        }

        public function setAddress($address) {
            $this->address = $address;
        }
        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
        }
        /** kiểm tra thông tin user có đầy đủ không */
        public function checkUser() {
           return  $this->username && $this->email && $this->name && $this->address && $this->phone;
           
        }
        public function authenticate() {
            // Retrieve the user from your storage (e.g., database) based on the username
            $user = $this->getUserByUserName();
    
            if(!empty($user)) {
                // Retrieve the stored password hash from the user data
                $stored_hash = $user['password'];
                // Use password_verify to check if the entered password matches the stored hash
                if(password_verify($this->password, $stored_hash)) {
                    // Passwords match, authentication successful
                    return true;
                } else {
                    // Passwords don't match, authentication failed
                    return false;
                }
            }
    
            // User not found, authentication failed
            return false;
        }
        public function authenticateGoogle() {
          //kiểm tra email có trên database không
            $user = $this->getUserByEmail();
    
            if(!empty($user)) {                
                    return true;
            }
    
            // User not found, authentication failed
            return false;
        }
        /**trả về đối tượng hoặc mảng user trùng với username ,
          *chế độ trả về class nếu tham số là true ,
         *không thì trả về array_assoc
          */
        public function getUserByUserName($fetchModeClass = false) {
            $sql = "SELECT * FROM account WHERE username=:username ";
            $stmt = $this->connect->prepare($sql);
            $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
           
            $stmt->execute();
            if(!$fetchModeClass) 
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            else
                $user = $stmt->fetchObject(__CLASS__);
            
            if($user) {
                return $user;
            } else {
                return null;
            }
        }
        /**trả về đối tượng hoặc mảng user trùng với email ,
         *chế độ trả về class nếu tham số là true ,
         *không thì trả về array_assoc
        */
        public function getUserByEmail($fetchModeClass = false) {
            $sql = "SELECT * FROM account WHERE email=:email";
            $stmt = $this->connect->prepare($sql);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->execute();
            if(!$fetchModeClass) 
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            else
                $user = $stmt->fetchObject(__CLASS__);
            if($user) {
                return $user;
            } else {
                return null;
            }
        }
    
        public function addUser() {
            try {
                $sql = 'INSERT INTO account (username, email, password, name, phone, address) VALUES (:username, :email, :password, :name, :phone, :address)';
                $stmt = $this->connect->prepare($sql);
        
                $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
                $this->setPassword($password_hash);
                // Gán giá trị cho các tham số
                $stmt->bindParam(':username', $this->username, PDO::PARAM_STR);
                $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
                $stmt->bindParam(':password',$this->password, PDO::PARAM_STR);
                $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
                $stmt->bindParam(':phone', $this->phone, PDO::PARAM_STR);
                $stmt->bindParam(':address', $this->address, PDO::PARAM_STR);
        
                // Thực thi câu lệnh
                $success = $stmt->execute();
        
                if ($success) {
                    return $this->connect->lastInsertId();; // Trả về id cuối cùng của bảng nếu thêm người dùng thành công
                } else {
                    return false; // Trả về false nếu thêm người dùng không thành công
                }
            } catch (PDOException $e) {
                // Xử lý lỗi nếu có
                $e->getMessage();

                return false; // Trả về false nếu có lỗi xảy ra
            }
        }
        
    
        public function update($userId) {
            try {
                // Hash the password before storing it in the database
                $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
                $this->setPassword($password_hash);
                // Prepare SQL statement to update user information
                $sql = 'UPDATE account SET username = :username, email = :email, password = :password, name = :name, phone = :phone, address = :address WHERE id = :userId';
                $stmt = $this->connect->prepare($sql);
    
                // Bind parameters
                $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':username', $this->username, PDO::PARAM_STR);
                $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
                $stmt->bindParam(':password',$this->password, PDO::PARAM_STR);
                $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
                $stmt->bindParam(':phone', $this->phone, PDO::PARAM_STR);
                $stmt->bindParam(':address', $this->address, PDO::PARAM_STR);
    
                // Execute the statement
                $success = $stmt->execute();
    
                // Return appropriate value based on success or failure
                return $success;
            } catch (PDOException $e) {
                // Log the error or throw an exception for better error handling
                $e->getMessage();

                return false;
            }
        }
    }
    
?>