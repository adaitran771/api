<?php
namespace App\Controller;
use App\Model\Product;
require_once(__DIR__. "/../Core/config.php");
class ProductService extends Rest{

    private $product;
    private $totalPage;
    private $page;
    private $productName;

    public function __construct() {
        parent::__construct();
        $this->product = new Product();
        $this->page = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->totalPage = $this->product->getTotalPage(\LIMIT);
        $this->productName = isset($_GET['productName']) ? $_GET['productName'] : '';
    }
    public function index() {
       
        $data = $this->product->getAll();
        $this->response($data);
        //print_r($data);
    }
    public function paging() {
        //echo $this->page;exit;
        if($this->page > $this->totalPage) {
            $this->response(['message' => 'Page not found'], 404);
        }
        $data = $this->product->getPaging($this->page, \LIMIT);
        $this->response($data);
    }
    public function searchByName() {
        $name = $this->productName;
        $data = $this->product->searchByName($name);
        $this->response($data);
    }
    public function getRangePrice() {
        $start = $this->request['start'];
        $end = $this->request['end'];
        $data = $this->product->getRangePrice($start, $end);
        if($data) {
            $this->response($data);
        } else {
            $this->response(['message' => 'No product found'], 404);
        }
    }
}
?>