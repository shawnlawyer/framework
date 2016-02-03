<?php

namespace Sequode\Application\Modules\Auth\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;

class Operations {
    public static $package = 'Auth';
	public static $merge = false;
	public static $routes = array(
		'login'
	);
	public static $routes_to_methods = array(
		'login' => 'login'
    );
    public static function login($json = null){
        
        $dialog = ModuleRegistry::model(static::$package)->xhr->dialogs[__FUNCTION__];
        if(!\SQDE_Session::is($dialog['session_store_key'])){ return; }
        $cards_xhr = ModuleRegistry::model(static::$package)->xhr->cards;
        $operations_xhr = ModuleRegistry::model(static::$package)->xhr->operations;
        $operations = ModuleRegistry::model(static::$package)->operations;
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        if($json != null){
                $input = json_decode(rawurldecode($json)); 
                if(isset($input->reset)){ 
                    \SQDE_Session::set($dialog['session_store_key'], $dialog['session_store_setup']);
                    return forward_static_call_array(array($cards_xhr,__FUNCTION__),array());  
                }
        }
        $dialog_store = \SQDE_Session::get($dialog['session_store_key']);
        $dialog_step = $dialog['steps'][$dialog_store->step];
        if(isset($dialog_step->prep) && $dialog_step->prep == true){
            if(isset($dialog_step->required_members)){
                foreach($dialog_step->required_members as $m){
                    if(!isset($input->$m)){ return;}
                }
            }
            switch($dialog_store->step){
                case 0:
                    if(
                    (
                        $modeler::exists(rawurldecode($input->login),'email')
                        || $modeler::exists(rawurldecode($input->login),'username')
                    )
                    && \SQDE_UserAuthority::isActive($modeler::model())
                    ){
                        $dialog_store->prep->user_id = $modeler::model()->id;
                        \SQDE_Session::set($dialog['session_store_key'], $dialog_store);
                    }
                    else
                    {
                        $error = 1;
                    }
                    break;
                case 1:
                    if(
                        $modeler::exists($dialog_store->prep->user_id, 'id')
                        && \SQDE_UserAuthority::isPassword(rawurldecode($input->secret), $modeler::model())
                    ){
                        $_a = array($modeler::model());
                    }
                    else
                    {
                        $error = 2;
                    }
                    break;
            }
        }
        if(isset($dialog_step->operation) && is_array($_a)){
            if(!(forward_static_call_array(array($operations, $dialog_step->operation),$_a))){
                $error = 3;
            }
        }
        if(!isset($error)){
            $dialog_store->step++;
            \SQDE_Session::set($dialog['session_store_key'], $dialog_store);
            return (intval($dialog_store->step) == 2) ? \SQDE_ConsoleRoutes::js(false) : forward_static_call_array(array($cards_xhr,__FUNCTION__),array());
        }else{
                echo $error;
        }
    }
}