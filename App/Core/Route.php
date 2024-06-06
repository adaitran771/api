<?php
namespace App\Core;

class Route {

    private $routes = [];
    private $LogFunc;

    public function __construct(){
        $this->LogFunc = new \Log();
    }

    public function addRoute($method, $url, $callback){
        $this->routes[] = array('method' => $method, 'url' => $url, 'callback' => $callback);
    }

    public function doRouting(){
        $reqUrl = $_SERVER['REQUEST_URI'];
        $reqMet = $_SERVER['REQUEST_METHOD'];

        $this->LogFunc->writeLogReq(); // Log the request

        foreach($this->routes as $route) {
            $regex = $route['url'];
            $regex = preg_replace('/\{:username\}/', '([a-zA-Z0-9]+)', $regex);
            $regex = preg_replace('/\{:number\}/', '([0-9]+)', $regex);
            $regex = preg_replace('/\{:productName\}/', '([a-zA-Z]+)', $regex);
            $regex = str_replace('?', '\?', $regex);
            $regex = "#^$regex$#";

            $matches = array();
            if (preg_match($regex, $reqUrl, $matches) && $reqMet == $route['method']) {
                $this->LogFunc->writeLogRes("Matched Route: " . $route['url']); // Log the matched route

                $controller = new $route['callback'][0]();
                $method = $route['callback'][1];

                return call_user_func_array([$controller, $method], array_slice($matches, 1));
            }
        }

        $this->LogFunc->writeLogRes("<h1>404 Not Found</h1>"); // Log the 404 error
        echo "<h1>404 Not Found</h1>";
    }
}

?>
