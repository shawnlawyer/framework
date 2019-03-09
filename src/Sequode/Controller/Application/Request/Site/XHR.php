<?php

namespace Sequode\Controller\Application\Request\Site;

use Sequode\Application\Modules\Traits\Routes\XHR\CardsCardRouteTrait as XHRCardsCardRouteTrait;
use Sequode\Application\Modules\Traits\Routes\XHR\OperationsDialogTrait as XHROperationsDialogTrait;
use Sequode\Controller\Application\Request\Traits\RequestCallTrait as RequestCallTrait;
use Sequode\Model\Application\Module\Routeables;
use Sequode\Model\Module\Registry as ModuleRegistry;



class XHR{

    use RequestCallTrait;

    public static function handle(){

        $call = false;
        $args = [];

        if(isset($_POST['sub']) && !empty($_POST['sub'])){
            $call = $_POST['sub'];
        }elseif(isset($_GET['sub']) && !empty($_GET['sub'])){
            $call = $_GET['sub'];
        }

        $call_pieces = explode('/',$call);
        if(!isset($call_pieces[1])){
            return;
        }
        if(!isset($call_pieces[2])){
            return;
        }
        $context = strtolower($call_pieces[1]);
        $modules_context = ModuleRegistry::modulesContext();
        if(!array_key_exists($context, $modules_context)){
            return;
        }
        $module_key = $modules_context[$context];
        $request_type = $call_pieces[0];

        $module = ModuleRegistry::module($module_key);

        if(!isset($module::model()->xhr->$request_type)){
            return;
        }
        $routes_class = $module::model()->xhr->$request_type;
        if(!in_array($call_pieces[2], Routeables::routes($routes_class))){
            return;
        }

        $route = Routeables::route($routes_class, $call_pieces[2]);

        if(isset($_POST['args']) && !empty($_POST['args'])){
            if( 500000 < strlen(http_build_query($_POST))){ return; }
            $args = $_POST['args'];

        }elseif(isset($_GET['args']) && !empty($_GET['args'])){
            if( 500000 < strlen(http_build_query($_GET))){ return; }
            $args = $_GET['args'];
        }

        if(in_array(XHROperationsDialogTrait::class, class_uses($routes_class, true)) && isset($routes_class::$dialogs) && in_array($route, $routes_class::$dialogs)){
            echo forward_static_call_array([$routes_class, 'dialog'], [$route, $args[0]]);
        }elseif(in_array(XHRCardsCardRouteTrait::class, class_uses($routes_class, true)) && isset($routes_class::$routes) && in_array($route, $routes_class::$routes)) {
            $parameters = [];
            if(method_exists($routes_class, $route)){
                $parameters = forward_static_call_array([$routes_class, $route], $args);

                if($parameters === false){
                    return;
                }
                $parameters = (!is_array($parameters)) ? [$parameters] : $parameters;
            }
            echo forward_static_call_array([$routes_class, 'card'], [$route, is_array($parameters) ? $parameters : []]);

        }else{
            echo forward_static_call_array([$routes_class, $route], $args);
        }
        return true;
    }
}