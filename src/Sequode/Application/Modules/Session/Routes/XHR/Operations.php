<?php

namespace Sequode\Application\Modules\Session\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Application\Modules\Session\Module;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\BlockedIP\Modeler as BlockedIPModeler;

class Operations {
    
    public static $module = Module::class;
    const Module = Module::class;

    public static function destroy($_model_id, $confirmed=false){

        extract((static::Module)::variables());
        $collection = 'session_search';

        if(

            !($modeler::exists($_model_id,'id')
            && AccountAuthority::isSystemOwner())

        ){ return false; }

        if ($confirmed===false){

            return DOMElementKitJS::confirmOperation($module::xhrOperationRoute(__FUNCTION__), $modeler::model()->id);

        }else{

            forward_static_call_array([$operations, __FUNCTION__], []);

            return implode(' ', [
                DOMElementKitJS::fetchCollection($collection, $_model_id),
                forward_static_call_array([$xhr_cards, 'card'], ['search'])
            ]);

        }

    }

    public static function blockIP($_model_id){

        extract((static::Module)::variables());

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

        $collection = 'session_search';

        $input = json_decode(stripslashes($json));

        $input = (!is_object($input) || (trim($input->search) == '' || empty(trim($input->search)))) ? (object) null : $input;

        SessionStore::set($collection, $input);

        return DOMElementKitJS::fetchCollection($collection);

    }
}