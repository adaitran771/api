<?php 
use Firebase\JWT\JWT;
use App\Core\Route;
require_once('./vendor/autoload.php');
require_once('writeLog.php');
$router = new Route();
$router->addRoute('POST', "/api/users/login",['App\Controller\Auth','Login']);
$router->addRoute('POST', "/api/users/logout",['App\Controller\Auth','Logout']);

$router->doRouting();




?>