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

        if(!empty($_POST['sub']) || !empty($_GET['sub'])){

            $request = (!empty($_POST['sub'])) ? $_POST : $_GET;

            $call = $request['sub'];

            if( 500000 < strlen(http_build_query($request))){

                return false;

            }

        }else{

            return false;

        }

        $slots = explode('/', $call);

        if(count($slots) != 3){

            return false;

        }

        list($type, $context, $method) = $slots;

        $module = ModuleRegistry::module($context, 'context');

        if(!isset($module::model()->xhr->$type)){

            return false;

        }

        $routes_class = $module::model()->xhr->$type;

        if(!in_array($method, Routeables::routes($routes_class))){

            return false;

        }

        $route = Routeables::route($routes_class, $method);

        $args = (!empty($request['args'])) ? $request['args'] : [];

        if(
            in_array(XHROperationsDialogTrait::class, class_uses($routes_class, true))
            && defined($routes_class .'::Dialogs')
            && in_array($route, $routes_class::Dialogs)
        ){
            $response = forward_static_call_array([($routes_class), 'dialog'], [$route, $args[0]]);

        }elseif(
            in_array(XHRCardsCardRouteTrait::class, class_uses($routes_class, true))
            && isset($routes_class::$routes)
            && in_array($route, $routes_class::$routes)
        ) {

            $response = forward_static_call_array([$routes_class, 'card'], [$route, $routes_class::prepInputValues($route, $args)]);

        }else{

            $response = forward_static_call_array([$routes_class, $route], $args);

        }

        echo $response;

        return true;

    }

}