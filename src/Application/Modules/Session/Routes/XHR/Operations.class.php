<?php

namespace Sequode\Application\Modules\Session\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

class Operations {
    public static $registry_key = 'Session';
    public static function delete($_model_id){
        $modeler = ModuleRegistry::model(static::$registry_key)->modeler;
        $cards_xhr = ModuleRegistry::model(static::$registry_key)->xhr->cards;
        if(!(
            $modeler::exists($_model_id,'id')
        )){ return; }
        $modeler::destroy();
        return $cards_xhr::search();
    }
    /* this should replace the above at a later day.
    public static function delete($_model_id){
        $modeler = ModuleRegistry::model(static::$registry_key)->modeler;
        if(!(
        $modeler::exists($_model_id,'id')
        && (\Sequode\Application\Modules\Account\Authority::isOwner( $modeler::model() )
        || \Sequode\Application\Modules\Account\Authority::isSystemOwner())
        )){ return; }
        forward_static_call_array(array(ModuleRegistry::model(static::$registry_key)->operations,__FUNCTION__),array());
        $js = array();
        $js[] = forward_static_call_array(array(ModuleRegistry::model(static::$registry_key)->xhr->cards,'my'),array());
        return implode(' ', $js);
    }
    */
    public static function blockIP($_model_id){
        $modeler = ModuleRegistry::model(static::$registry_key)->modeler;
        $session_ip = $modeler::model()->ip_address;
        if(!(
            $modeler::exists($_model_id,'id')
            && $modeler::model()->ip_address != $session_ip
        )){ return; }
        \Sequode\Application\Modules\BlockedIP\Modeler::model()->create($modeler::model()->ip_address);
    }
    public static function search($json){
        $_o = json_decode(stripslashes($json));
        $_o = (!is_object($_o) || (trim($_o->search) == '' || empty(trim($_o->search)))) ? (object) null : $_o;
        $collection = 'session_search';
        SessionStore::set($collection, $_o);
		$js=array();
        $js[] = DOMElementKitJS::fetchCollection($collection);
        return implode(' ',$js);
    }
}