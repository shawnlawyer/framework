<?php

namespace Sequode\Controller\Application;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Controller\Application\HTTPRequest;
use Sequode\Model\Application\Routes;
use Sequode\Controller\Application\HTTPRequest\Rest as Request;

use Sequode\Application\Modules\Account\Modeler as AccountModeler;
use Sequode\Application\Modules\Token\Modeler as TokenModeler;

use Sequode\Application\Modules\Package\Module as PackageModule;
use Sequode\Application\Modules\Sequode\Module as SequodeModule;

class HTTPAPIRequest{
    public static function rest(){
        $request_pieces = HTTPRequest::requestUriPieces();
        if(!isset($request_pieces[0]) || trim($request_pieces[0]) == ''){
            exit;
        }
        $token = $request_pieces[0];
        array_shift($request_pieces);
        if(!(TokenModeler::exists($token, 'token'))){
            return;
        }

        AccountModeler::exists(TokenModeler::model()->owner_id,'id');
        ModuleRegistry::add(SequodeModule::class);
        ModuleRegistry::add(PackageModule::class);

        if(!isset($request_pieces[0]) || trim($request_pieces[0]) == ''){
            exit;
        }
        $request_type = $request_pieces[0];
        array_shift($request_pieces);


        if(!isset($request_pieces[0]) || trim($request_pieces[0]) == ''){
            exit;
        }

        $context = strtolower($request_pieces[0]);

        $modules_context = ModuleRegistry::modulesContext();
        if(!array_key_exists($context, $modules_context)){
            return;
        }
        $module_registry_key = $modules_context[$context];

        array_shift($request_pieces);

        $module = ModuleRegistry::module($module_registry_key);

        if(!isset($module::model()->rest->$request_type)){
            exit;
        }
        $routes_class = $module::model()->rest->$request_type;
        if(!in_array($request_pieces[0], Routes::routes('\\'.$routes_class))){
            exit;
        }
        $route = Routes::route('\\'.$routes_class, $request_pieces[0]);

        array_shift($request_pieces);
        if(isset($_POST['args']) && !empty($_POST['args'])){
            if( 500000 < strlen(http_build_query($_POST))){ return; }
            $inputs = $_POST['args'];

        }elseif(isset($_GET['args']) && !empty($_GET['args'])){
            if( 500000 < strlen(http_build_query($_GET))){ return; }
            $inputs = $_GET['args'];
        }
        $inputs = $request_pieces;
        Request::call('\\'.$routes_class, $route, $inputs);
        return true;
    }
}