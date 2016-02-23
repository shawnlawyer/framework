<?php

namespace Sequode\Application\Modules\Package\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

use Sequode\Application\Modules\Package\Module;

class Operations {
    
    public static $module = Module::class;
    
    public static function newPackage(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        forward_static_call_array(array($operations, __FUNCTION__), array(\Sequode\Application\Modules\Account\Modeler::model()->id));
        $js = array();
        $collection = 'packages';
        $js[] = DOMElementKitJS::fetchCollection($collection, $modeler::model()->id);
        $js[] = forward_static_call_array(array(xhr_cards, 'details'), array($modeler::model()->id));
        
        return implode(' ', $js);
        
    }
    
	public static function updatePackageSequode($_model_id, $json){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        $_o = json_decode($json);
        if(!(
        $modeler::exists($_model_id,'id')
        && \Sequode\Application\Modules\Sequode\Modeler::exists($_o->sequode,'id')
        && \Sequode\Application\Modules\Sequode\Authority::isPackage(\Sequode\Application\Modules\Sequode\Modeler::model())
        && ( \Sequode\Application\Modules\Account\Authority::isOwner($modeler::model()) || \Sequode\Application\Modules\Account\Authority::isSystemOwner() )
        )){ return; }
        
        forward_static_call_array(array($operations, __FUNCTION__), array($_o->sequode));
        $collection = 'packages';
        
        $js = array();
        $js[] = DOMElementKitJS::fetchCollection($collection, $modeler::model()->id);
        $js[] = forward_static_call_array(array($xhr_cards, 'details'), array($modeler::model()->id));
        
        return implode(' ', $js);
        
	}
    
    public static function updateName($_model_id, $json
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && (\Sequode\Application\Modules\Account\Authority::isOwner( $modeler::model() )
        || \Sequode\Application\Modules\Account\Authority::isSystemOwner())
        )){ return; }
        $_o = json_decode($json);
        $name = trim(str_replace('-', '_', str_replace(' ', '_', urldecode($_o->name))));
        if(strlen($name) < 2){
            return ' alert(\'Package name should be more than 1 character long.\');';
        }
        if(!eregi("^([A-Za-z_])*$", substr($name,0,1))){
            return ' alert(\'Package name should start with a letter or underscore\');';
        }
        if(!eregi("^([A-Za-z0-9_])*$", $name)){
            return ' alert(\'Package name must be alphanumeric and all spaces will convert to underscore.\');';
        }
        forward_static_call_array(array($operations, __FUNCTION__), array($name));
        $js = array();
        
        $collection = 'packages';
        $js[] = DOMElementKitJS::fetchCollection($collection, $modeler::model()->id);
        $js[] = forward_static_call_array(array($xhr_cards, 'details'), array($modeler::model()->id));
        
        return implode(' ', $js);
        
    }
    
    public static function delete($_model_id){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && (\Sequode\Application\Modules\Account\Authority::isOwner( $modeler::model() )
        || \Sequode\Application\Modules\Account\Authority::isSystemOwner())
        )){ return; }
        forward_static_call_array(array($operations, __FUNCTION__), array());
        $js = array();
        $js[] = forward_static_call_array(array($xhr_cards, 'my'), array());
        
        return implode(' ', $js);
        
    }
    
    public static function search($json){
        
        $module = static::$module;
        $context = $module::model()->context;
        
        $_o = json_decode(stripslashes($json));
        $_o = (!is_object($_o) || (trim($_o->search) == '' || empty(trim($_o->search)))) ? (object) null : $_o;
        $collection = $context . '_search';
        SessionStore::set($collection, $_o);
		$js=array();
        $js[] = DOMElementKitJS::fetchCollection($collection);
        
        return implode(' ',$js);
        
    }
    
}