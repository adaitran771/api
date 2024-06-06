<?php
namespace App\Model;
use App\Core\Database;
use PDO;
use Exception;

class Order extends Database {
    private $id;
    private $id_account;
    private $productName;
    private $quantity;
    private $price;
    private $size;
    private $color;
    private $idProduct;
    private $status;

    //create getter and setter
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function getId_account() {
        return $this->id_account;
    }
    public function setId_account($id_account) {
        $this->id_account = $id_account;
    }
    public function getProductName() {
        return $this->productName;
    }
    public function setProductName($productName) {
        $this->productName = $productName;
    }
    public function getQuantity() {
        return $this->quantity;
    }
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }
    public function getPrice() {
        return $this->price;
    }
    public function setPrice($price) {
        $this->price = $price;
    }
    public function getSize() {
        return $this->size;
    }
    public function setSize($size) {
        $this->size = $size;
    }
    public function getColor() {
        return $this->color;
    }
    public function setColor($color) {
        $this->color = $color;
    }
    public function getIdProduct() {
        return $this->idProduct;
    }
    public function setIdProduct($idProduct) {
        $this->idProduct = $idProduct;
    }
    public function getStatus() {
        return $this->status;
    }
    public function setStatus($status) {
        $this->status = $status;
    }
    

    public function createOrder() {
        
        $sql = "INSERT INTO order_detail (productName, id_account, idProduct, quantity, price, size, color, status) VALUES (:productName, :id_account, :idProduct, :quantity, :price, :size, :color, :status)";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindParam(':id_account', $this->id_account);
        $stmt->bindParam(':idProduct', $this->idProduct);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':price', $this->price);

        $stmt->bindParam(':size', $this->size);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':productName', $this->productName);
        $stmt->execute();
        return $this->connect->lastInsertId();
    }

    public function updateOrderStatus() {
        try {
            $sql = "UPDATE order_detail SET status = :status WHERE id = :id";
            $stmt = $this->connect->prepare($sql);
            $stmt->bindParam(':status', $this->status);
            $stmt->bindParam(':id', $this->id);
            return $stmt->execute(); 
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function getOrderByStatus () {
        $sql = "SELECT order_detail.*, product.img FROM order_detail INNER JOIN product On order_detail.idProduct = product.id
                WHERE order_detail.status = :status and order_detail.id_account = :id_account ";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id_account', $this->id_account);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
?>