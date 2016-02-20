<?php

namespace Sequode\Application\Modules\Account\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Email\EmailContent;
use Sequode\Controller\Email\Email;

use Sequode\Foundation\Hashes;

class Operations {
    public static $module_registry_key = Sequode\Application\Modules\Account\Module::class;
	public static $merge = false;
	public static $routes = array(
		'updatePassword',
		'updateEmail'
	);
	public static $routes_to_methods = array(
		'updatePassword' => 'updatePassword',
		'updateEmail' => 'updateEmail'
    );
    public static function updatePassword($json = null){
        
        $dialog = ModuleRegistry::model(static::$module_registry_key)->xhr->dialogs[__FUNCTION__];
        if(!SessionStore::is($dialog['session_store_key'])){ return; }
        $cards_xhr = ModuleRegistry::model(static::$module_registry_key)->xhr->cards;
        $operations_xhr = ModuleRegistry::model(static::$module_registry_key)->xhr->operations;
        $operations = ModuleRegistry::model(static::$module_registry_key)->operations;
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        if($json != null){
                $input = json_decode(rawurldecode($json)); 
                if(isset($input->reset)){ 
                    SessionStore::set($dialog['session_store_key'], $dialog['session_store_setup']);
                    return forward_static_call_array(array($cards_xhr,__FUNCTION__),array());  
                }
        }
        $dialog_store = SessionStore::get($dialog['session_store_key']);
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
                        rawurldecode($input->password) == rawurldecode($input->confirm_password)
                        && \Sequode\Application\Modules\Account\Authority::isSecurePassword(rawurldecode($input->password))
                    ){
                        $dialog_store->prep->new_secret = rawurldecode($input->password);
                        SessionStore::set($dialog['session_store_key'], $dialog_store);
                    }
                    else
                    {
                        $error = true;
                    }
                    break;
                case 1:
                    if(
                        \Sequode\Application\Modules\Account\Authority::isPassword(rawurldecode($input->password), $modeler::model())
                    ){
                        $_a =  array($dialog_store->prep->new_secret);
                    }
                    else
                    {
                        $error = true;
                    }
                    break;
            }
        }
        if(isset($dialog_step->operation) && is_array($_a)){
            if(!(forward_static_call_array(array($operations, $dialog_step->operation),$_a))){
                $error = true;
            }
        }
        if(!isset($error)){
            $dialog_store->step++;
            SessionStore::set($dialog['session_store_key'], $dialog_store);
            return forward_static_call_array(array($cards_xhr,__FUNCTION__),array()); 
        }
    }
    public static function updateEmail($json = null){
        
        $dialog = ModuleRegistry::model(static::$module_registry_key)->xhr->dialogs[__FUNCTION__];
        if(!SessionStore::is($dialog['session_store_key'])){ return; }
        $cards_xhr = ModuleRegistry::model(static::$module_registry_key)->xhr->cards;
        $operations_xhr = ModuleRegistry::model(static::$module_registry_key)->xhr->operations;
        $operations = ModuleRegistry::model(static::$module_registry_key)->operations;
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        if($json != null){
                $input = json_decode(rawurldecode($json)); 
                if(isset($input->reset)){ 
                    SessionStore::set($dialog['session_store_key'], $dialog['session_store_setup']);
                    return forward_static_call_array(array($cards_xhr,__FUNCTION__),array());  
                }
        }
        $dialog_store = SessionStore::get($dialog['session_store_key']);
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
                        !$modeler::exists(rawurldecode($input->email),'email')
                        && \Sequode\Application\Modules\Account\Authority::isAnEmailAddress(rawurldecode($input->email))
                    ){
                        $dialog_store->prep->new_email = rawurldecode($input->email);
                        $dialog_store->prep->token = Hashes::generateHash();
                        SessionStore::set($dialog['session_store_key'], $dialog_store);
                        
                        $hooks = array(
                            "searchStrs" => array('#TOKEN#'),
                            "subjectStrs" => array($dialog_store->prep->token)
                        );
                        Email::systemSend($dialog_store->prep->new_email,'Verify your email address with sequode.com', EmailContent::render('activation.txt',$hooks));
                    }
                    else
                    {
                        $error = true;
                    }
                    break;
                case 1:
                    if(
                        $dialog_store->prep->token == rawurldecode($input->token)
                    ){  
                        $_a =  array($dialog_store->prep->new_email);
                    }
                    else
                    {
                        $error = true;
                    }
                    break;
            }
        }
        if(isset($dialog_step->operation) && is_array($_a)){
            if(!(forward_static_call_array(array($operations, $dialog_step->operation),$_a))){
                $error = true;
            }
        }
        if(!isset($error)){
            $dialog_store->step++;
            SessionStore::set($dialog['session_store_key'], $dialog_store);
            return forward_static_call_array(array($cards_xhr,__FUNCTION__),array()); 
        }
    }
}