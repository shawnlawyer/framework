<?php

namespace Sequode\Application\Modules\Token\Routes\XHR;

use Sequode\Application\Modules\Token\Module;
use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Application\Modules\Account\Modeler as AccountModeler;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;

class Operations {

    const Module = Module::class;
    
    public static function newToken(){

        extract((static::Module)::variables());
        $collection = 'tokens';
        
        forward_static_call_array([$operations, __FUNCTION__], [AccountModeler::model()->id]);

        return implode(' ', [
            DOMElementKitJS::fetchCollection($collection, $modeler::model()->id),
            forward_static_call_array([$xhr_cards, 'card'], ['details'])
        ]);

    }

    public static function updateName($_model_id, $json){

        extract((static::Module)::variables());
        $collection = 'tokens';

        if(!(
            $modeler::exists($_model_id,'id')
            && (AccountAuthority::isOwner( $modeler::model() )
            || AccountAuthority::isSystemOwner())
        )){ return; }

        $input = json_decode($json);
        $name = trim(str_replace('-','_',str_replace(' ','_',urldecode($input->name))));
        if(strlen($name) < 2){
            return ' alert(\'Token name should be more than 1 character long.\');';
        }
        if(!preg_match("/^([A-Za-z_])*$/i",substr($name,0,1))){
            return ' alert(\'Token name should start with a letter or underscore\');';
        }
        if(!preg_match("/^([A-Za-z0-9_])*$/i",$name)){
            return ' alert(\'Token name must be alphanumeric and all spaces will convert to underscore.\');';
        }

        forward_static_call_array([$operations, __FUNCTION__] , [$name]);

        return implode(' ', [
            DOMElementKitJS::fetchCollection($collection, $modeler::model()->id),
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);

    }
    public static function delete($_model_id, $confirmed=false){

        extract((static::Module)::variables());

        if(!(
            $modeler::exists($_model_id,'id')
            && (AccountAuthority::isOwner( $modeler::model() )
            || AccountAuthority::isSystemOwner())
        )){ return; }


        if ($confirmed===false){

            return DOMElementKitJS::confirmOperation($module::xhrOperationRoute(__FUNCTION__), $modeler::model()->id);

        }else{

            forward_static_call_array([$operations, __FUNCTION__], []);

            return implode(' ', [
                forward_static_call_array([$xhr_cards, 'card'], ['tokens'])
            ]);

        }

    }
    public static function search($json){

        $collection = 'token_search';

        $input = json_decode(stripslashes($json));
        $input = (!is_object($input) || (trim($input->search) == '' || empty(trim($input->search)))) ? (object) null : $input;

        SessionStore::set($collection, $input);

        return implode(' ',[
            DOMElementKitJS::fetchCollection($collection)
        ]);
        
    }
}