<?php

namespace Sequode\Application\Modules\Register\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Email\EmailContent;
use Sequode\Controller\Email\Email;

use Sequode\Application\Modules\Register\Module;

class Operations {
    
    public static $module = Module::class;
    
    public static function signup($json = null){
        
        $module = static::$module;
        $dialogs = $module::model()->components->dialogs;
        $dialog = forward_static_call_array(array($dialogs, __FUNCTION__), array());
        
        if(!SessionStore::is($dialog->session_store_key)){ return; }
        
        $xhr_cards = $module::model()->xhr->cards;
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
                        !$modeler::exists(rawurldecode($input->email), 'email')
                        && \Sequode\Application\Modules\Account\Authority::isAnEmailAddress(rawurldecode($input->email))
                    ){
                        $dialog_store->prep->email = rawurldecode($input->email);
                        $dialog_store->prep->token = $operations::generateHash();
                        SessionStore::set($dialog->session_store_key, $dialog_store);
                    }
                    else
                    {
                        $error = true;
                    }
                    break;
                case 1:
                    if(
                        rawurldecode($input->password) == rawurldecode($input->confirm_password)
                        && \Sequode\Application\Modules\Account\Authority::isSecurePassword(rawurldecode($input->password))
                    ){
                        $dialog_store->prep->password = rawurldecode($input->password);
                        SessionStore::set($dialog->session_store_key, $dialog_store);
                    }
                    else
                    {
                        $error = true;
                    }
                    break;
                case 2:
                    if(
                        rawurldecode($input->accept) == 1
                    ){
                        $hooks = array(
                            "searchStrs" => array('#TOKEN#'),
                            "subjectStrs" => array($dialog_store->prep->token)
                        );
                        Email::systemSend($dialog_store->prep->email, 'Verify your email address with sequode.com', EmailContent::render('activation.txt', $hooks));        
                    }
                    else
                    {
                        $error = true;
                    }
                    break;
                case 3:
                    if(
                        !$modeler::exists($dialog_store->prep->email, 'email')
                        && $dialog_store->prep->token == trim(rawurldecode($input->token))
                    ){  
                        $_a =  array($dialog_store->prep->email, $dialog_store->prep->password);
                    }
                    else
                    {
                        $error = true;
                    }
                    break;
            }
        }
        
        if(isset($dialog_step->operation) && is_array($_a)){
            if(!(forward_static_call_array(array($operations, $dialog_step->operation), $_a))){
                $error = true;
            }
        }
        
        if(!isset($error)){
            $dialog_store->step++;
            SessionStore::set($dialog->session_store_key, $dialog_store);
            return forward_static_call_array(array($xhr_cards, __FUNCTION__), array()); 
        }
        
    }
    
}