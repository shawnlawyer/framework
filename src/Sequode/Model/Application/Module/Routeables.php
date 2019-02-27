<?php

namespace Sequode\Model\Application\Module;

use Sequode\Model\Module\Registry as ModuleRegistry;

class Routeables
{
    public static function model()
    {

        $modules = ModuleRegistry::modules();

        $_o = (object)[
            'routes' => [],
            'xhr' => [],
            'rest' => [],
            'collections' => []
        ];

        foreach ($modules as $module) {

            if (isset($module::model()->routes)) {
                foreach ($module::model()->routes as $key => $class) {
                    $_o->routes[] = (object)[
                        'module' => $module::$registry_key,
                        'type' => $key,
                        'class' => $class
                    ];
                }
            }

            if (isset($module::model()->xhr)) {
                foreach ($module::model()->xhr as $key => $class) {
                    $_o->xhr[] = (object) [
                        'module' => $module::$registry_key,
                        'type'   => $key,
                        'class'  => $class
                    ];
                }
            }

            if (isset($module::model()->rest)) {
                foreach ($module::model()->rest as $key => $class) {
                    $_o->rest[] = (object)[
                        'module' => $module::$registry_key,
                        'type' => $key,
                        'class' => $class
                    ];
                }
            }

            if (isset($module::model()->collections)) {
                $_o->collections[] = (object)[
                    'module' => $module::$registry_key,
                    'type' => 'http',
                    'class' => $class
                ];
            }
        }
        return $_o;
    }

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