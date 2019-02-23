<?php
namespace Sequode\Controller\Application\HTTPRequest;

class XHR  {
	public static function call($route_class, $route, $parameters = null){
        if($parameters == null){ $parameters = [];}
		return forward_static_call_array([$route_class, $route], $parameters);
    }
}