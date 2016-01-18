<?php

namespace Sequode\Controller;

class HTTPRequest {
	public static function run(){
        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], array('https://sequode.com','https://origin.sequode.com','https://console.sequode.com','https://xhr.sequode.com','https://api.sequode.com'))) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])){
                    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
                }
                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])){
                    header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
                }
            }
        }
		$route_class = false;
        $routes_classes = \Sequode\ApplicationProfile::model()->routes;
		$route = 'index';
        $request_pieces = self::requestUriPieces();
		if(isset($request_pieces[0]) && trim($request_pieces[0]) == ''){
			foreach($routes_classes as $routes_class){
				if(in_array('index',get_class_methods('\\'.$routes_class))){
					$parameters = array(); 
					unset($request_pieces);
					forward_static_call_array(array('\\'.$routes_class ,'index'), $parameters);
					return;
				}
			}
		}
		if(isset($request_pieces[0]) && trim($request_pieces[0]) != ''){
			foreach($routes_classes as $routes_class){
				if(isset($request_pieces[0]) && in_array($request_pieces[0], \Sequode\Routes::routes('\\'.$routes_class))){
					$route = \Sequode\Routes::route('\\'.$routes_class, trim($request_pieces[0]));
					array_shift($request_pieces);
					$parameters = array(); 
					if(isset($request_pieces[0])){
						$parameters = $request_pieces;
					}
					unset($request_pieces);
					forward_static_call_array(array('\\'.$routes_class ,$route), $parameters);
					return;
				}
			}
		}
		if(isset(\Sequode\ApplicationProfile::model()->module)){
			return forward_static_call_array(array('\\' . \Sequode\ApplicationProfile::model()->module ,'run'), array());	
		}
    }
    public static function requestUriPieces(){
        
        $request_pieces = $_SERVER['REQUEST_URI'];
        $request_pieces = explode('?',$request_pieces)[0];
        $request_pieces = explode('#',$request_pieces)[0];
        $request_pieces = explode('/',$request_pieces);
        
        array_shift($request_pieces);
        return $request_pieces;
    }
	public static function setCookie($name = '', $value = '', $expire = 0){
        setcookie($name, $value, $expire, \Sequode\Model\Application::model()->sessions->path, \Sequode\Model\Application::model()->sessions->domain);
	}
}