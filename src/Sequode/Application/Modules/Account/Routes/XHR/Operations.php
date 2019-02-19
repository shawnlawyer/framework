<?php

namespace Sequode\Application\Modules\Account\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;

use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\View\Email\EmailContent;
use Sequode\Controller\Email\Email;

use Sequode\Foundation\Hashes;

use Sequode\Application\Modules\Account\Module;


use Sequode\Application\Modules\Sequode\Modeler as SequodeModeler;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;

class Operations {
    
    public static $module = Module::class;
    
	public static $merge = false;
	public static $routes = [
		'addToSequodeFavorites',
        'removeFromSequodeFavorites',
        'emptySequodeFavorites',
		'updatePassword',
		'updateEmail'
	];
    
	public static $routes_to_methods = [
		'addToSequodeFavorites' => 'addToSequodeFavorites',
        'removeFromSequodeFavorites' => 'removeFromSequodeFavorites',
        'emptySequodeFavorites' => 'emptySequodeFavorites',
		'updatePassword' => 'updatePassword',
		'updateEmail' => 'updateEmail'
    ];

    public static function emptySequodeFavorites($confirmed=false){

        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        if ($confirmed===false && is_array($sequence) && count(json_decode($modeler::model()->sequence)) != 0){
            $js = array();
            $js[] = 'if(';
            $js[] = 'confirm(\'Are you sure you want to empty your sequode favorites?\')';
            $js[] = '){';
            $js[] = 'new XHRCall({route:"operations/account/emptySequodeFavorites",inputs:[true]});';
            $js[] = '}';
            return implode(' ',$js);
        } else {
            forward_static_call_array([$operations, __FUNCTION__], []);
            $js = array();
            $collection = 'sequode_favorites';
            $js[] = DOMElementKitJS::fetchCollection($collection);
            return implode(' ', $js);
        }
    }

    public static function addToSequodeFavorites($_model_id){

        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;

        if(!(

            SequodeModeler::exists($_model_id,'id')
            && AccountAuthority::canRun()

        )){ return; }

        forward_static_call_array([$operations, __FUNCTION__], []);
        $js = array();
        $collection = 'sequode_favorites';
        $js[] = DOMElementKitJS::fetchCollection($collection);
        $js[] = DOMElementKitJS::fetchCollection('sequodes', SequodeModeler::model()->id);
        $js[] = 'if(typeof registry.active_context != \'undefined\' && typeof registry.active_context.card != \'undefined\'){';
        $js[] = 'new XHRCall({route:registry.active_context.card, inputs:['.SequodeModeler::model()->id.']});';
        $js[] = '}';
        return implode(' ', $js);
    }

    public static function removeFromSequodeFavorites($_model_id){

        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;

        if(!(

            SequodeModeler::exists($_model_id,'id')
            && AccountAuthority::isInSequodeFavorites()

        )){ return; }

        forward_static_call_array([$operations, __FUNCTION__], []);
        $js = array();
        $collection = 'sequode_favorites';
        $js[] = DOMElementKitJS::fetchCollection($collection);
        $js[] = DOMElementKitJS::fetchCollection('sequodes', SequodeModeler::model()->id);
        $js[] = 'if(typeof registry.active_context != \'undefined\' && typeof registry.active_context.card != \'undefined\'){';
        $js[] = 'new XHRCall({route:registry.active_context.card, inputs:['.SequodeModeler::model()->id.']});';
        $js[] = '}';
        return implode(' ', $js);
    }

    public static function updatePassword($json = null){
        
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
                        rawurldecode($input->password) == rawurldecode($input->confirm_password)
                        && AccountAuthority::isSecurePassword(rawurldecode($input->password))
                    ){
                        $dialog_store->prep->new_secret = rawurldecode($input->password);
                        SessionStore::set($dialog->session_store_key, $dialog_store);
                    }
                    else
                    {
                        $error = true;
                    }
                    break;
                case 1:
                    if(
                        AccountAuthority::isPassword(rawurldecode($input->password), $modeler::model())
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
            SessionStore::set($dialog->session_store_key, $dialog_store);
            return forward_static_call_array(array($xhr_cards, __FUNCTION__) ,array()); 
            
        }
        
    }
    
    public static function updateEmail($json = null){
        
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
                        !$modeler::exists(rawurldecode($input->email),'email')
                        && AccountAuthority::isAnEmailAddress(rawurldecode($input->email))
                    ){
                        $dialog_store->prep->new_email = rawurldecode($input->email);
                        $dialog_store->prep->token = Hashes::generateHash();
                        SessionStore::set($dialog->session_store_key, $dialog_store);
                        
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