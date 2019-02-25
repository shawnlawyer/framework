<?php

namespace Sequode\Controller\Application;

use Sequode\Model\Application\Configuration;
use Sequode\Model\Application\Runtime;
use Sequode\Model\Application\Routes;

class HTTPRequest {
	public static function run(){
        
		$route_class = false;
        $routes_classes = Runtime::model()->routes;
		$route = 'index';
        $request_pieces = self::requestUriPieces();
		if(isset($request_pieces[0]) && trim($request_pieces[0]) == ''){
			foreach($routes_classes as $routes_class){
				if(in_array('index',get_class_methods('\\'.$routes_class))){
					$parameters = [];
					unset($request_pieces);
					forward_static_call_array(['\\'.$routes_class ,'index'], $parameters);
					return;
				}
			}
		}
		if(isset($request_pieces[0]) && trim($request_pieces[0]) != ''){
			foreach($routes_classes as $routes_class){
				if(isset($request_pieces[0]) && in_array($request_pieces[0], Routes::routes('\\'.$routes_class))){
					$route = Routes::route('\\'.$routes_class, trim($request_pieces[0]));
					array_shift($request_pieces);
					$parameters = [];
					if(isset($request_pieces[0])){
						$parameters = $request_pieces;
					}
					unset($request_pieces);
					forward_static_call_array(['\\'.$routes_class ,$route], $parameters);
					return;
				}
			}
		}
		if(isset(Runtime::model()->module)){
			return forward_static_call_array(['\\' . Runtime::model()->module ,'run'], []);
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
        setcookie($name, $value, $expire, Configuration::model()->sessions->path, Configuration::model()->sessions->domain);
	}
}