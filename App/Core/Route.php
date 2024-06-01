<?php
namespace App\Core;
class Route{

    private $routes = [];
    
    public function __construct(){}
    
    public function addRoute($method, $url, $callback){
        $this->routes[] = array('method' => $method, 
                              'url' => $url, 
                              'callback' => $callback);
    }
    
    public function doRouting(){
        $reqUrl = $_SERVER['REQUEST_URI'];
        
        $reqMet = $_SERVER['REQUEST_METHOD'];

        foreach($this->routes as  $route) {
            $regex = preg_replace('/\{:number\}/', '([0-9]+)', $route['url']);

            $regex = preg_replace(('/\{:productName\}/'), '([a-zA-Z]+)', $regex);
            $regex = str_replace('?', '\?', $regex);

            $matches = array();
            if($reqMet == $route['method'] && $reqUrl == $route['url']) {
                
                $controller = new $route['callback'][0]();
                $method = $route['callback'][1];
                
                return call_user_func_array([$controller, $method], $matches);
            }
            $regex = "#^$regex$#";
           
            if (preg_match($regex, $reqUrl, $matches)) {
                
                $controller = new $route['callback'][0]();
                $method = $route['callback'][1];
                
                return call_user_func_array([$controller, $method], $matches);
                
            }
        }
        echo "<h1>404 Not Found</h1>";
    }

}
?>