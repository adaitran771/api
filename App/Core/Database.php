<?php
namespace App\Core;
use PDO;
use PDOException;
class Database {
    public $connect;

    public function __construct() {
        $dsn = "mysql:host=".DB_HOST . ";dbname=". DB_NAME . ";charset=utf8";
        try{
            $conn = new PDO($dsn, DB_USER, DB_PASS);
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            return $this->connect = $conn;
            
        }
        catch(PDOException $e){
            
            echo $e->getMessage();
            exit;
        }
    }



}
?>