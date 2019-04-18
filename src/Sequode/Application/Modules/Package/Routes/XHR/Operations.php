<?php

namespace Sequode\Application\Modules\Package\Routes\XHR;

use Sequode\Application\Modules\Package\Module;
use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\Sequode\Authority as SequodeAuthority;
use Sequode\Application\Modules\Sequode\Modeler as SequodeModeler;
use Sequode\Application\Modules\Account\Modeler as AccountModeler;

class Operations {
    
    const Module = Module::class;
    
    public static function newPackage(){

        extract((static::Module)::variables());
        $collection = 'packages';
        
        forward_static_call_array([$operations, __FUNCTION__], [AccountModeler::model()->id]);

        return implode(' ', [
            DOMElementKitJS::fetchCollection($collection, $modeler::model()->id),
            forward_static_call_array([$xhr_cards, 'card'], ['details'])
        ]);
        
    }
    
	public static function updatePackageSequode($_model_id, $json){

        extract((static::Module)::variables());
        $collection = 'packages';
        
        $input = json_decode($json);
        if(!(
            $modeler::exists($_model_id,'id')
            && SequodeModeler::exists($input->sequode,'id')
            && SequodeAuthority::isPackage(SequodeModeler::model())
            && ( AccountAuthority::isOwner($modeler::model()) || AccountAuthority::isSystemOwner() )
        )){ return; }
        
        forward_static_call_array([$operations, __FUNCTION__], [$input->sequode]);
        
        return implode(' ', [
            DOMElementKitJS::fetchCollection($collection, $modeler::model()->id),
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);
        
	}
    
    public static function updateName($_model_id, $json){

        extract((static::Module)::variables());
        $collection = 'packages';
        
        if(!(
            $modeler::exists($_model_id,'id')
            && (AccountAuthority::isOwner( $modeler::model() )
            || AccountAuthority::isSystemOwner())
        )){ return; }

        $input = json_decode($json);
        $name = trim(str_replace('-', '_', str_replace(' ', '_', urldecode($input->name))));
        if(strlen($name) < 2){
            return ' alert(\'Package name should be more than 1 character long.\');';
        }
        if(!preg_match("/^([A-Za-z_])*$/i", substr($name,0,1))){
            return ' alert(\'Package name should start with a letter or underscore\');';
        }
        if(!preg_match("/^([A-Za-z0-9_])*$/i", $name)){
            return ' alert(\'Package name must be alphanumeric and all spaces will convert to underscore.\');';
        }

        forward_static_call_array([$operations, __FUNCTION__], [$name]);

        return implode(' ', [
            DOMElementKitJS::fetchCollection($collection, $modeler::model()->id),
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);
        
    }
    
    public static function delete($_model_id, $confirmed=false){

        extract((static::Module)::variables());
        $collection = 'packages';

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
                forward_static_call_array([$xhr_cards, 'card'], ['packages'])]
            );

        }

    }
    
    public static function search($json){

        extract((static::Module)::variables());
        $collection = 'package_search';

        $input = json_decode(stripslashes($json));
        $input = (!is_object($input) || (trim($input->search) == '' || empty(trim($input->search)))) ? (object) null : $input;

        SessionStore::set($collection, $input);

        return implode(' ',[
            DOMElementKitJS::fetchCollection($collection)
        ]);
        
    }
    
}