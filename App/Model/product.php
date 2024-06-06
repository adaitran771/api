<?php
namespace App\Model;
use App\Core\Database;
use PDO;

class product extends Database{
    private $id;
    private $name;
    private $img;
    private $price;
    private $description;
    private $status;
    private $idcategory;
    private $size;
    private $color;
    //make setter getter
    
    public function setId($id){
        $this->id = $id;
    }
    public function getId(){
        return $this->id;
    }
    public function setName($name){
        $this->name = $name;
    }
    public function getName(){
        return $this->name;
    }
    public function setImg($img){
        $this->img = $img;
    }
    public function getImg(){
        return $this->img;
    }
    public function setPrice($price){
        $this->price = $price;
    }
    public function getPrice(){
        return $this->price;
    }
    public function setDescription($description){
        $this->description = $description;
    }
    public function getDescription(){
        return $this->description;
    }
    public function setStatus($status){
        $this->status = $status;
    }
    public function getStatus(){
        return $this->status;
    }
    public function setIdcategory($idcategory){
        $this->idcategory = $idcategory;
    }
    public function getIdcategory(){
        return $this->idcategory;
    }
    public function setSize($size){
        $this->size = $size;
    }
    public function getSize(){
        return $this->size;
    }
    public function setColor($color){
        $this->color = $color;
    }
    public function getColor(){
        return $this->color;
    }
    public function getAll(){
        $sql = "SELECT * FROM product";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    public function getById(){
        $sql = "SELECT * FROM product WHERE id = :id";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindValue(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function getPaging($page, $limit){
        $sql = "SELECT * FROM product LIMIT :start, :limit";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindValue(':start', ($page - 1) * $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function count(){
        $sql = "SELECT COUNT(*) as total FROM product";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    public function getTotalPage($limit){
        $total = $this->count();
        return ceil($total/$limit);
    }
    public function searchByName($name){
        $sql = "SELECT * FROM product WHERE name LIKE :name";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindValue(':name', "%$name%");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getRangePrice(float $start, float $end)
    {
        $sql = "SELECT * FROM product WHERE price BETWEEN :Start and :End";
        $stmt = $this->connect->prepare($sql);
        $stmt->bindParam(':Start', $start);
        $stmt->bindParam(':End', $end);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>