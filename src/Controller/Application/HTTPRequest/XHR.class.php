<?php
namespace Sequode\Controller\Application\HTTPRequest\XHR;

class XHR  {
	public static function call($route_class, $route, $parameters = null){
        if($parameters == null){ $parameters = array();}
		return forward_static_call_array(array($route_class, $route), $parameters);
    }
}