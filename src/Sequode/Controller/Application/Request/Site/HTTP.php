<?php

namespace Sequode\Controller\Application\Request\Site;

use Sequode\Model\Application\Configuration;
use Sequode\Model\Application\RuntimeModulesRouteableClasses;
use Sequode\Model\Application\Routes;
use Sequode\Controller\Application\Request\Traits\RequestURIPiecesTrait;
use Sequode\Controller\Application\Request\Traits\RequestCallTrait;

class HTTP {

    use RequestURIPiecesTrait,
        RequestCallTrait;

	public static function handle(){
        
		$route_class = false;
        $routeables = RuntimeModulesRouteableClasses::model()->routes;
		$route = 'index';

        $request_pieces = static::URIPieces();
		if(isset($request_pieces[0]) && trim($request_pieces[0]) == ''){
			foreach($routeables as $routeable){
                if(in_array('index', get_class_methods($routeable->class))){
					$parameters = [];
                    unset($request_pieces);
					forward_static_call_array([$routeable->class , 'index'], $parameters);
					return;
				}
			}
		}
		if(isset($request_pieces[0]) && trim($request_pieces[0]) != ''){
			foreach($routeables as $routeable){
				if(isset($request_pieces[0]) && in_array($request_pieces[0], Routes::routes($routeable->class))){
					$route = Routes::route($routeable->class, trim($request_pieces[0]));
					array_shift($request_pieces);
					$parameters = [];
					if(isset($request_pieces[0])){
						$parameters = $request_pieces;
					}
					unset($request_pieces);
					forward_static_call_array([$routeable->class ,$route], $parameters);
					return;
				}
			}
		}
    }
	public static function setCookie($name = '', $value = '', $expire = 0){
        setcookie($name, $value, $expire, Configuration::model()->sessions->path, Configuration::model()->sessions->domain);
	}
}