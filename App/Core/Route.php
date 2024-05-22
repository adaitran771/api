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
    
            // convert urls like '/controller/action' to regular expression   
            $pattern = "^/[a-zA-Z]*/[a-zA-Z]*$";
            $matches = array();
    
            if($reqMet == $route['method'] && $reqUrl == $route['url']) {
                
                
                return call_user_func_array($route['callback'], $matches);
            }
        }
    }

}
?>