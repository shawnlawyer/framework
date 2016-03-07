<?php

namespace Sequode\Application\Modules\User\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

use Sequode\Application\Modules\User\Module;

class Operations {
    
    public static $module = Module::class;
    
    public static function newUser(){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        if(!(
        \Sequode\Application\Modules\Account\Authority::isSystemOwner()
        )){ return; }
        return $xhr_cards::details($operations::newUser()->id);
    }
    public static function newGuest(){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        if(!(
        \Sequode\Application\Modules\Account\Authority::isSystemOwner()
        )){ return; }
        return $xhr_cards::details($operations::newGuest()->id);
    }
    public static function delete($_model_id){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        if(!(
        \Sequode\Application\Modules\Account\Authority::isSystemOwner()
        && $modeler::exists($_model_id,'id')
        )){return;}
        $operations::delete();
        return $xhr_cards::search();
    }
    public static function loginAs($_model_id){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        $input = json_decode(rawurldecode($json));
        if(!(
        \Sequode\Application\Modules\Account\Authority::isSystemOwner()
        && $modeler::exists($_model_id,'id')
        )){return;}
        $operations::login();
        $console_module =  ModuleRegistry::model()['Console'];
        return forward_static_call_array(array($console_module::model()->routes->http, 'js'), array());
    }
    public static function updatePassword($_model_id, $json){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        $input = json_decode(rawurldecode($json));
        if(!(
        \Sequode\Application\Modules\Account\Authority::isSystemOwner()
        && $modeler::exists($_model_id,'id')
        )){return;}
        $operations::updatePassword($input->password);
        return $xhr_cards::details($modeler::model()->id);
    }
    public static function updateRole($_model_id, $json){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        $input = json_decode(rawurldecode($json));
        if(!(
            \Sequode\Application\Modules\Account\Authority::isSystemOwner()
            && $modeler::exists($_model_id,'id')
            && \Sequode\Application\Modules\Role\Modeler::exists($input->role,'id')
        )){return;}
        $operations::updateRole();
        return $xhr_cards::details($modeler::model()->id);
    }
    public static function updateActive($_model_id, $json){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        $input = json_decode(rawurldecode($json));
        if(!(
            \Sequode\Application\Modules\Account\Authority::isSystemOwner()
            && $modeler::exists($_model_id,'id')
        )){return;}
        $operations::updateActive($input->active);
        return $xhr_cards::details($modeler::model()->id);
    }
    public static function updateName($_model_id, $json){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        if(!(
            $modeler::exists($_model_id,'id')
        )){ return; }
        $input = json_decode($json);
        $name = trim(str_replace('-','_',str_replace(' ','_',urldecode($input->username))));
        if(strlen($name)==0){
            return ' alert(\'Name cannot be empty\');';
        }
        if(!eregi("^([A-Za-z0-9_])*$",$name)){
            return ' alert(\'Name can be alphanumeric and contain spaces only\');';
        }
        
        if($modeler::exists($name,'username') && $modeler::model()->id != $_model_id){
            return ' alert(\'Name already exists\');';
        }elseif($modeler::exists($name,'username') && $modeler::model()->id == $_model_id){
            return;
        }
        
        $modeler::exists($_model_id,'id');
        
        $operations::updateName($name);
        
        return $xhr_cards::details($modeler::model()->id);
        
    }
    public static function search($json){
        $module = static::$module;  
        $_o = json_decode(stripslashes($json));
        $_o = (!is_object($_o) || (trim($_o->search) == '' || empty(trim($_o->search)))) ? (object) null : $_o;
        $collection = $module::model()->context . '_' . __FUNCTION__;
        SessionStore::set($collection, $_o);
		$js=array();
        $js[] = DOMElementKitJS::fetchCollection($collection);
        return implode(' ',$js);
    }
}