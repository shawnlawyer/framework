<?php

namespace Sequode\Application\Modules\Auth\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Model\Module\Registry as ModuleRegistry;

use Sequode\Application\Modules\Auth\Module;

class Operations {
    
    public static $module = Module::class;
    
	public static $merge = false;
	public static $routes = array(
		'login'
	);
	public static $routes_to_methods = array(
		'login' => 'login'
    );
    public static function login($json = null){
               
        $module = static::$module;
        $dialogs = $module::model()->components->dialogs;
        $dialog = forward_static_call_array(array($dialogs, __FUNCTION__), array());
        
        if(!SessionStore::is($dialog->session_store_key)){ return; }
        $xhr_cards = $module::model()->xhr->cards;
        $operations_xhr = $module::model()->xhr->operations;
        $operations = $module::model()->operations;
        $modeler = $module::model()->modeler;
        if($json != null){
                $input = json_decode(rawurldecode($json)); 
                if(isset($input->reset)){ 
                    SessionStore::set($dialog->session_store_key, $dialog->session_store_setup);
                    return forward_static_call_array(array($xhr_cards, __FUNCTION__), array());  
                }
        }
        $dialog_store = SessionStore::get($dialog->session_store_key);
        $dialog_step = $dialog->steps[$dialog_store->step];
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
                    && \Sequode\Application\Modules\Account\Authority::isActive($modeler::model())
                    ){
                        $dialog_store->prep->user_id = $modeler::model()->id;
                        SessionStore::set($dialog->session_store_key, $dialog_store);
                    }
                    else
                    {
                        $error = 1;
                    }
                    break;
                case 1:
                    if(
                        $modeler::exists($dialog_store->prep->user_id, 'id')
                        && \Sequode\Application\Modules\Account\Authority::isPassword(rawurldecode($input->secret), $modeler::model())
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
            if(!(forward_static_call_array(array($operations, $dialog_step->operation), $_a))){
                $error = 3;
            }
        }
        if(!isset($error)){
            $dialog_store->step++;
            SessionStore::set($dialog->session_store_key, $dialog_store);
            return (intval($dialog_store->step) == 2) ? \Sequode\Application\Modules\SequencerConsole\Routes\Routes::js(false) : forward_static_call_array(array($xhr_cards,__FUNCTION__),array());
        }
    }
}