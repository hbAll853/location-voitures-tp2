<?php
namespace App\Routes;

class Route {
    private static $routes = [];

    public static function get($url, $controller){
        self::$routes[] = ['url' => $url, 'controller' => $controller, 'method' => 'GET'];
    }

     public static function post($url, $controller){
        self::$routes[] = ['url' => $url, 'controller' => $controller, 'method' => 'POST'];
    }

    public static function dispatch(){
        $url = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $urlSegments = explode('?', $url);
        $urlPath = rtrim($urlSegments[0],'/');


        foreach(self::$routes as $route){
            if(BASE.$route['url'] == $urlPath && $route['method'] == $method){
                $controllerSegments = explode('@', $route['controller']);

                $controllerName = 'App\\Controllers\\'.$controllerSegments[0];
                $methodName = $controllerSegments[1];
                $controllerInstance = new $controllerName;

                if($method == 'GET'){
                    if(isset($urlSegments[1])){
                         parse_str($urlSegments[1], $queryParams);
                        $controllerInstance->$methodName($queryParams);
                    }else{
                        $controllerInstance->$methodName();
                    }
                }elseif($method == 'POST'){
                    $controllerInstance->$methodName($_POST);
                }
                
             return;
            }
        }
        http_response_code(404);
        echo "404 page not found";
    }
}