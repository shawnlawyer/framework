<?php
namespace Sequode;
class Routes{
	
	public static function routes($routes_class){
        if(property_exists($routes_class, 'routes') && is_array($routes_class::$routes)){
			if(isset($routes_class::$merge) && $routes_class::$merge == true){
				return array_merge(get_class_methods($routes_class),$routes_class::$routes);
			}
			return $routes_class::$routes;
        }
        return get_class_methods($routes_class);
    }
	
    public static function route($routes_class, $route){
        if(isset($routes_class::$routes_to_methods)	&& is_array($routes_class::$routes_to_methods) && array_key_exists($route,$routes_class::$routes_to_methods)){
			$routes = $routes_class::$routes_to_methods;
			return $routes[$route];
        }
        return $route;
	}
}	