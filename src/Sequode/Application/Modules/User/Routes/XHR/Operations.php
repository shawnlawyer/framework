<?php

namespace Sequode\Application\Modules\User\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

use Sequode\Application\Modules\User\Module;

use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\Role\Modeler as RoleModeler;

class Operations {
    
    public static $module = Module::class;
    
    public static function newUser(){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        if(!(
        AccountAuthority::isSystemOwner()
        )){ return; }
        return $xhr_cards::details($operations::newUser()->id);
    }
    public static function newGuest(){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        if(!(
        AccountAuthority::isSystemOwner()
        )){ return; }
        return $xhr_cards::details($operations::newGuest()->id);
    }
    public static function delete($_model_id, $confirmed=false){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        if(!(
        AccountAuthority::isSystemOwner()
        && $modeler::exists($_model_id,'id')
        )){return;}
        if ($confirmed===false){
            $js = array();
            $js[] = 'if(';
            $js[] = 'confirm(\'Are you sure you want to delete this?\')';
            $js[] = '){';
            $js[] = 'new XHRCall({route:"operations/user/delete",inputs:['.$modeler::model()->id.', true]});';
            $js[] = '}';
            return implode(' ',$js);
        }else{
            forward_static_call_array(array($operations, __FUNCTION__), array());
            $js = array();
            $collection = 'users';
            $js[] = DOMElementKitJS::fetchCollection($collection, $modeler::model()->id);
            $js[] = forward_static_call_array(array($xhr_cards, 'search'), array());
            return implode(' ', $js);
        }
    }
    public static function loginAs($_model_id){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;

        if(!(
            AccountAuthority::isSystemOwner()
            && $modeler::exists($_model_id,'id')
        )){return;}
        $operations::login();
        $console_module =  ModuleRegistry::model()['Console'];
        return forward_static_call_array(array($console_module::model()->routes['http'], 'js'), array(false));
    }
    public static function updatePassword($_model_id, $json){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        $input = json_decode(rawurldecode($json));
        if(!(
        AccountAuthority::isSystemOwner()
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
            AccountAuthority::isSystemOwner()
            && $modeler::exists($_model_id,'id')
            && RoleModeler::exists($input->role,'id')
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
            AccountAuthority::isSystemOwner()
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
        $name = trim(str_replace('-','_',str_replace(' ','_',urldecode($input->name))));
        if(strlen($name)==0){
            return ' alert(\'Name cannot be empty\');';
        }
        if(!preg_match("/^([A-Za-z0-9_])*$/i",$name)){
            return ' alert(\'Name can be alphanumeric and contain spaces only\');';
        }
        
        if($modeler::exists($name,'name') && $modeler::model()->id != $_model_id){
            return ' alert(\'Name already exists\');';
        }elseif($modeler::exists($name,'name') && $modeler::model()->id == $_model_id){
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