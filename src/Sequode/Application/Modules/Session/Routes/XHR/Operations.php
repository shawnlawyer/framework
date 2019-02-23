<?php

namespace Sequode\Application\Modules\Session\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;


use Sequode\Application\Modules\Session\Module;
    
class Operations {
    
    public static $module = Module::class;
    
    public static function destroy($_model_id){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        if(
            !($modeler::exists($_model_id,'id')
            && \Sequode\Application\Modules\Account\Authority::isSystemOwner())
        ){ return false; }
        forward_static_call_array([$operations,__FUNCTION__],[]);
        return forward_static_call_array([$xhr_cards,'card'],['search']);
    }
    public static function blockIP($_model_id){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $session_ip = $modeler::model()->ip_address;
        
        if(!(
            $modeler::exists($_model_id,'id')
            && $modeler::model()->ip_address != $session_ip
        )){ return false; }
        \Sequode\Application\Modules\BlockedIP\Modeler::model()->create($modeler::model()->ip_address);
    }
    public static function search($json){
        $_o = json_decode(stripslashes($json));
        $_o = (!is_object($_o) || (trim($_o->search) == '' || empty(trim($_o->search)))) ? (object) null : $_o;
        $collection = 'session_search';
        SessionStore::set($collection, $_o);
        return DOMElementKitJS::fetchCollection($collection);
    }
}