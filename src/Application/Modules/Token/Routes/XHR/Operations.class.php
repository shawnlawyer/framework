<?php

namespace Sequode\Application\Modules\Token\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

class Operations {
    public static $module_registry_key = 'Token';
    public static function newToken(){
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        forward_static_call_array(array(ModuleRegistry::model(static::$module_registry_key)->operations,__FUNCTION__),array(\Sequode\Application\Modules\Account\Modeler::model()->id));
        $js = array();
        $js[] = DOMElementKitJS::fetchCollection(ModuleRegistry::model(static::$module_registry_key)->collections->main, $modeler::model()->id);
        $js[] = forward_static_call_array(array(ModuleRegistry::model(static::$module_registry_key)->xhr->cards,'details'),array($modeler::model()->id));
        return implode(' ', $js);
    }
    public static function updateName($_model_id, $json){
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        if(!(
        $modeler::exists($_model_id,'id')
        && (\Sequode\Application\Modules\Account\Authority::isOwner( $modeler::model() )
        || \Sequode\Application\Modules\Account\Authority::isSystemOwner())
        )){ return; }
        $_o = json_decode($json);
        $name = trim(str_replace('-','_',str_replace(' ','_',urldecode($_o->name))));
        if(strlen($name) < 2){
            return ' alert(\'Token name should be more than 1 character long.\');';
        }
        if(!eregi("^([A-Za-z_])*$",substr($name,0,1))){
            return ' alert(\'Token name should start with a letter or underscore\');';
        }
        if(!eregi("^([A-Za-z0-9_])*$",$name)){
            return ' alert(\'Token name must be alphanumeric and all spaces will convert to underscore.\');';
        }
        forward_static_call_array(array(ModuleRegistry::model(static::$module_registry_key)->operations,__FUNCTION__),array($name));
        $js = array();
        $collection = 'tokens';
        $js[] = DOMElementKitJS::fetchCollection($collection, $modeler::model()->id);
        $js[] = forward_static_call_array(array(ModuleRegistry::model(static::$module_registry_key)->xhr->cards,'details'),array($modeler::model()->id));
        return implode(' ', $js);
    }
    public static function delete($_model_id){
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        if(!(
        $modeler::exists($_model_id,'id')
        && (\Sequode\Application\Modules\Account\Authority::isOwner( $modeler::model() )
        || \Sequode\Application\Modules\Account\Authority::isSystemOwner())
        )){ return; }
        forward_static_call_array(array(ModuleRegistry::model(static::$module_registry_key)->operations,__FUNCTION__),array());
        $js = array();
        $js[] = forward_static_call_array(array(ModuleRegistry::model(static::$module_registry_key)->xhr->cards,'my'),array());
        return implode(' ', $js);
    }
    public static function search($json){
        $_o = json_decode(stripslashes($json));
        $_o = (!is_object($_o) || (trim($_o->search) == '' || empty(trim($_o->search)))) ? (object) null : $_o;
        $collection = 'token_search';
        SessionStore::set($collection, $_o);
		$js=array();
        $js[] = DOMElementKitJS::fetchCollection($collection);
        return implode(' ',$js);
    }
}