<?php

namespace Sequode\Application\Modules\Console\Traits\Routes;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Model\Application\Routes as ApplicationRoutes;
use Sequode\Controller\Application\HTTPRequest\XHR as XHRRequest;


use Sequode\Application\Modules\Traits\Routes\XHR\OperationsDialogTrait as XHROperationsDialogTrait;
use Sequode\Component\Card\Traits\CardsTrait;

trait XHRTrait {
	
	public static function xhr(){
        
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
        if(!in_array($call_pieces[2], ApplicationRoutes::routes($routes_class))){
            return;
        }
        
        $route = ApplicationRoutes::route($routes_class, $call_pieces[2]);
        
		if(isset($_POST['args']) && !empty($_POST['args'])){
            if( 500000 < strlen(http_build_query($_POST))){ return; }
			$args = $_POST['args'];
            
		}elseif(isset($_GET['args']) && !empty($_GET['args'])){
            if( 500000 < strlen(http_build_query($_GET))){ return; }
			$args = $_GET['args'];
		}

        if(in_array(XHROperationsDialogTrait::class, class_uses($routes_class, true)) && isset($routes_class::$dialogs) && in_array($route, $routes_class::$dialogs)){
            echo XHRRequest::call($routes_class, 'dialog', [$route, $args[0]]);
        }elseif(in_array('Sequode\\Component\\Card\\Traits\\CardsTrait', class_uses($routes_class, true)) && isset($routes_class::$routes) && in_array($route, $routes_class::$routes)) {

            if(method_exists($routes_class, $route)){
                $parameters = forward_static_call_array([$routes_class, $route], $args);

                if($parameters === false){
                    return;
                }
                $parameters = is_array($parameters) ? $parameters : [];
            }
            echo XHRRequest::call($routes_class, 'card', [$route, $parameters]);
        }else{

            echo XHRRequest::call($routes_class, $route, $args);
        }
        return true;
    }
    
}

