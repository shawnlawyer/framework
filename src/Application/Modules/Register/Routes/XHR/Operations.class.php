<?php

namespace Sequode\Application\Modules\Register\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Email\EmailContent;
use Sequode\Controller\Email\Email;

class Operations {
    public static $package = 'Register';
	public static $merge = false;
	public static $routes = array(
		'signup',
        'reset'
	);
	public static $routes_to_methods = array(
		'signup' => 'signup'
    );
    public static function signup($json = null){
        
        $dialog = ModuleRegistry::model(static::$package)->xhr->dialogs[__FUNCTION__];
        if(!\Sequode\Application\Modules\Session\Modeler::is($dialog['session_store_key'])){ return; }
        $cards_xhr = ModuleRegistry::model(static::$package)->xhr->cards;
        $operations_xhr = ModuleRegistry::model(static::$package)->xhr->operations;
        $operations = ModuleRegistry::model(static::$package)->operations;
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        if($json != null){
                $input = json_decode(rawurldecode($json)); 
                if(isset($input->reset)){ 
                    \Sequode\Application\Modules\Session\Modeler::set($dialog['session_store_key'], $dialog['session_store_setup']);
                    return forward_static_call_array(array($cards_xhr,__FUNCTION__),array());  
                }
        }
        $dialog_store = \Sequode\Application\Modules\Session\Modeler::get($dialog['session_store_key']);
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
                        && \SQDE_UserAuthority::isAnEmailAddress(rawurldecode($input->email))
                    ){
                        $dialog_store->prep->email = rawurldecode($input->email);
                        $dialog_store->prep->token = $operations::generateHash();
                        \Sequode\Application\Modules\Session\Modeler::set($dialog['session_store_key'], $dialog_store);
                    }
                    else
                    {
                        $error = true;
                    }
                    break;
                case 1:
                    if(
                        rawurldecode($input->password) == rawurldecode($input->confirm_password)
                        && \SQDE_UserAuthority::isSecurePassword(rawurldecode($input->password))
                    ){
                        $dialog_store->prep->password = rawurldecode($input->password);
                        \Sequode\Application\Modules\Session\Modeler::set($dialog['session_store_key'], $dialog_store);
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
                        Email::systemSend($dialog_store->prep->email,'Verify your email address with sequode.com', EmailContent::render('activation.txt',$hooks));        
                    }
                    else
                    {
                        $error = true;
                    }
                    break;
                case 3:
                    if(
                        !$modeler::exists($dialog_store->prep->email,'email')
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
            if(!(forward_static_call_array(array($operations, $dialog_step->operation),$_a))){
                $error = true;
            }
        }
        if(!isset($error)){
            $dialog_store->step++;
            \Sequode\Application\Modules\Session\Modeler::set($dialog['session_store_key'], $dialog_store);
            return forward_static_call_array(array($cards_xhr,__FUNCTION__),array()); 
        }
    }
}