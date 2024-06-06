<?php
namespace App\Controller;
use App\Model\Order;
use App\Model\Product;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
class OrderService extends Rest {
    public function __construct() {
        parent::__construct();
    }
    
    public function createOrder() {
        if($this->jwt != null) {
            //echo $this->jwt;exit;
            //\print_r($this->request);exit;
            $order = new Order();

            //validate request
            if(!isset($this->request['productName']) || !isset($this->request['quantity']) || !isset($this->request['price']) || !isset($this->request['size'])  || !isset($this->request['color']) || !isset($this->request['idProduct'])) {
                $this->response(["message" => "Invalid request"]);
            }
            


            //decode jwt
            $key = "111111";
            $idAccount = (array)JWT::decode($this->jwt, new Key($key, 'HS256'));
            $idAccount = $idAccount['id'];
            $order->setId_account($idAccount);
            $order->setProductName($this->request['productName']);
            $order->setQuantity($this->request['quantity']);
            $order->setPrice($this->request['price']);

            $order->setSize($this->request['size']);
            $order->setColor($this->request['color']);
            $order->setIdProduct($this->request['idProduct']);
            $order->setStatus(1);
            try {
            $idOrderInserted =  $order->createOrder();
            } catch (Exception $e) {
                $this->response(["message" => $e->getMessage()]); }

            if ($idOrderInserted) {
                $this->response(["message" => "Create order successfully"]);
            } else {
                $this->response(["message" => "Create order failed"]);
            }
        } else {
            $this->response(["message" => "Unauthorized"]);
        }

      
    }
    public function getOrderByStatus() {
        if($this->jwt != null) {
            $key = "111111";
            $idAccount = (array)JWT::decode($this->jwt, new Key($key, 'HS256'));
            $idAccount = $idAccount['id'];
            $order = new Order();
            $order->setId_account($idAccount);
           // $status = $this->request['status'];
            // \var_dump($this->request);exit;
            $order->setStatus($this->request['status']);
            $data = $order->getOrderByStatus();
           
            if($data) {
                $this->response(["orders" => $data]);
            } else {
                $this->response(["message" => "No order found"]);
            }
        } else {
            $this->response(["message" => "Unauthorized"]);
        }
        
    }
    public function cancelOrder()  {
        if($this->jwt != null) {
            $key = "111111";
            $idAccount = (array)JWT::decode($this->jwt, new Key($key, 'HS256'));
            $idAccount = $idAccount['id'];
            $order = new Order();
            $order->setId_account($idAccount);
            $order->setId($this->request['id']);
            $order->setStatus(4);
            $data = $order->updateOrderStatus();
            //echo $data;exit;
            if($data) {
                $this->response(["message" => "Cancel order successfully"]);
            } else {
                $this->response(["message" => "Cancel order failed"],404);
            }
        } else {
            $this->response(["message" => "Unauthorized"],401);
        }
    }
}
?>