<?php 
use Firebase\JWT\JWT;
use App\Core\Route;
require_once('./vendor/autoload.php');

$router = new Route();
$router->addRoute('POST', "/api/users/login",['App\Controller\Auth','Login']);
$router->addRoute('GET', "/api/users/logout",['App\Model\User','Logout']);

$router->doRouting();




?>