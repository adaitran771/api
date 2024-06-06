<?php 
use Firebase\JWT\JWT;
use App\Core\Route;
require_once('./vendor/autoload.php');
require_once('writeLog.php');
$Log = new Log();
$Log->writeLogReq();
$router = new Route();
$router->addRoute('POST', "/api/users/update",['App\Controller\Auth','update']);
$router->addRoute('GET', "/api/users?username={:username}", ['App\Controller\Auth', 'getUserByUsername']);
$router->addRoute('POST', "/api/users/login",['App\Controller\Auth','login']);
$router->addRoute('POST', "/api/users/register",['App\Controller\Auth','register']);
$router->addRoute('POST', "/api/users/logout",['App\Controller\Auth','Logout']);
$router->addRoute('GET', "/api/products",['App\Controller\ProductService','index']);
$router->addRoute('GET', "/api/products?page={:number}",['App\Controller\ProductService','paging']);
$router->addRoute('GET', "/api/products?productName={:productName}",['App\Controller\ProductService','searchByName']);
$router->addRoute('POST', "/api/order",['App\Controller\OrderService','createOrder']);
$router->addRoute('POST', "/api/order/user",['App\Controller\OrderService','getOrderByStatus']);
$router->addRoute('POST', "/api/order/user/update",['App\Controller\OrderService','cancelOrder']);

$router->addRoute('POST', "/api/products/rangePrice",['App\Controller\ProductService','getRangePrice']);


$router->doRouting();




?>