<?php

namespace Sequode\Application\Modules\Session\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;


use Sequode\Application\Modules\Session\Module;
    
class Operations {
    
    public static $module = Module::class;
    
    public static function delete($_model_id){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $xhr_cards = $module::model()->xhr->cards;
        
        if(!(
            $modeler::exists($_model_id,'id')
        )){ return; }
        $modeler::destroy();
        return $xhr_cards::search();
    }
    /* this should replace the above at a later day.
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
        forward_static_call_array(array($operations,__FUNCTION__),array());
        $js = array();
        $js[] = forward_static_call_array(array($xhr_cards,'my'),array());
        return implode(' ', $js);
    }
    */
    public static function blockIP($_model_id){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
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