<?php

namespace Sequode\Application\Modules\Session\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Application\Modules\Session\Module;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\BlockedIP\Modeler as BlockedIPModeler;

class Operations {
    
    public static $module = Module::class;
    
    public static function destroy($_model_id, $confirmed=false){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        if(
            !($modeler::exists($_model_id,'id')
            && AccountAuthority::isSystemOwner())
        ){ return false; }

        $js = [];
        if ($confirmed===false){
            $js[] = 'if(';
            $js[] = 'confirm(\'Are you sure you want to destroy this?\')';
            $js[] = '){';
            $js[] = 'new XHRCall({route:"' . $module::xhrOperationRoute(__FUNCTION__) . '", inputs:['.$modeler::model()->id.', true]});';
            $js[] = '}';
        }else{
            forward_static_call_array([$operations, __FUNCTION__], []);
            $collection = 'session_search';
            $js[] = DOMElementKitJS::fetchCollection($collection, $_model_id);
            $js[] = forward_static_call_array([$xhr_cards, 'card'], ['search']);
        }
        return implode(' ', $js);
    }
    public static function blockIP($_model_id){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $session_ip = $modeler::model()->ip_address;
        
        if(!(
            $modeler::exists($_model_id,'id')
            && $modeler::model()->ip_address != $session_ip
        )){ return false; }
        BlockedIPModeler::model()->create([
            'ip_address' => $modeler::model()->ip_address,
        ]);
    }
    public static function search($json){
        $_o = json_decode(stripslashes($json));
        $_o = (!is_object($_o) || (trim($_o->search) == '' || empty(trim($_o->search)))) ? (object) null : $_o;
        $collection = 'session_search';
        SessionStore::set($collection, $_o);
        return DOMElementKitJS::fetchCollection($collection);
    }
}