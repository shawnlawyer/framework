<?php

namespace Sequode\Application\Modules\User\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

class Operations{
    public static $package = 'User';
    public static function newUser(){
        $operations = ModuleRegistry::model(static::$package)->operations;
        $cards_xhr = ModuleRegistry::model(static::$package)->xhr->cards;
        if(!(
        \SQDE_UserAuthority::isSystemOwner()
        )){ return; }
        return $cards_xhr::details($operations::newUser()->id);
    }
    public static function newGuest(){
        $operations = ModuleRegistry::model(static::$package)->operations;
        $cards_xhr = ModuleRegistry::model(static::$package)->xhr->cards;
        if(!(
        \SQDE_UserAuthority::isSystemOwner()
        )){ return; }
        return $cards_xhr::details($operations::newGuest()->id);
    }
    public static function delete($_model_id){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $operations = ModuleRegistry::model(static::$package)->operations;
        $cards_xhr = ModuleRegistry::model(static::$package)->xhr->cards;
        if(!(
        \SQDE_UserAuthority::isSystemOwner()
        && $modeler::exists($_model_id,'id')
        )){return;}
        $operations::delete();
        return $cards_xhr::search();
    }
    public static function loginAs($_model_id){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $operations = ModuleRegistry::model(static::$package)->operations;
        $input = json_decode(rawurldecode($json));
        if(!(
        \SQDE_UserAuthority::isSystemOwner()
        && $modeler::exists($_model_id,'id')
        )){return;}
        $operations::login();
        return \SQDE_ConsoleRoutes::js(false);
    }
    public static function updatePassword($_model_id, $json){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $operations = ModuleRegistry::model(static::$package)->operations;
        $cards_xhr = ModuleRegistry::model(static::$package)->xhr->cards;
        $input = json_decode(rawurldecode($json));
        if(!(
        \SQDE_UserAuthority::isSystemOwner()
        && $modeler::exists($_model_id,'id')
        )){return;}
        $operations::updatePassword($input->password);
        return $cards_xhr::details($modeler::model()->id);
    }
    public static function updateRole($_model_id, $json){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $operations = ModuleRegistry::model(static::$package)->operations;
        $cards_xhr = ModuleRegistry::model(static::$package)->xhr->cards;
        $input = json_decode(rawurldecode($json));
        if(!(
            \SQDE_UserAuthority::isSystemOwner()
            && $modeler::exists($_model_id,'id')
            && \SQDE_Role::exists($input->role,'id')
        )){return;}
        $operations::updateRole();
        return $cards_xhr::details($modeler::model()->id);
    }
    public static function updateActive($_model_id, $json){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $operations = ModuleRegistry::model(static::$package)->operations;
        $cards_xhr = ModuleRegistry::model(static::$package)->xhr->cards;
        $input = json_decode(rawurldecode($json));
        if(!(
            \SQDE_UserAuthority::isSystemOwner()
            && $modeler::exists($_model_id,'id')
        )){return;}
        $operations::updateActive($input->active);
        return $cards_xhr::details($modeler::model()->id);
    }
    public static function updateName($_model_id, $json){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $operations = ModuleRegistry::model(static::$package)->operations;
        $cards_xhr = ModuleRegistry::model(static::$package)->xhr->cards;
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
        return $cards_xhr::details($modeler::model()->id);
    }
    public static function search($json){
        $_o = json_decode(stripslashes($json));
        $_o = (!is_object($_o) || (trim($_o->search) == '' || empty(trim($_o->search)))) ? (object) null : $_o;
        $collection = 'user_search';
        \SQDE_Session::set($collection, $_o);
		$js=array();
        $js[] = DOMElementKitJS::fetchCollection($collection);
        return implode(' ',$js);
    }
}