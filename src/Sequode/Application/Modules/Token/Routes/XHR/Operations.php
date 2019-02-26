<?php

namespace Sequode\Application\Modules\Token\Routes\XHR;

use Sequode\Application\Modules\Token\Module;
use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Application\Modules\Account\Modeler as AccountModeler;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;

class Operations {

    public static $module = Module::class;
    
    public static function newToken(){   
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        forward_static_call_array([$operations, __FUNCTION__], [AccountModeler::model()->id]);
        $js = [];
        $collection = 'tokens';
        $js[] = DOMElementKitJS::fetchCollection($collection, $modeler::model()->id);
        $js[] = forward_static_call_array([$xhr_cards, 'card'], ['details']);
        return implode(' ', $js);
    }
    public static function updateName($_model_id, $json){ 
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && (AccountAuthority::isOwner( $modeler::model() )
        || AccountAuthority::isSystemOwner())
        )){ return; }
        $_o = json_decode($json);
        $name = trim(str_replace('-','_',str_replace(' ','_',urldecode($_o->name))));
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
        $js = [];
        $collection = 'tokens';
        $js[] = DOMElementKitJS::fetchCollection($collection, $modeler::model()->id);
        $js[] = forward_static_call_array([$xhr_cards, 'card'], ['details']);
        return implode(' ', $js);
    }
    public static function delete($_model_id, $confirmed=false){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && (AccountAuthority::isOwner( $modeler::model() )
        || AccountAuthority::isSystemOwner())
        )){ return; }

        $js = [];
        if ($confirmed===false){
            $js[] = 'if(';
            $js[] = 'confirm(\'Are you sure you want to delete this?\')';
            $js[] = '){';
            $js[] = 'new XHRCall({route:"operations/token/delete", inputs:['.$modeler::model()->id.', true]});';
            $js[] = '}';
        }else{
            forward_static_call_array([$operations, __FUNCTION__], []);
            $collection = 'tokens';
            $js[] = DOMElementKitJS::fetchCollection($collection, $modeler::model()->id);
            $js[] = forward_static_call_array([$xhr_cards, 'card'], ['my']);
        }
        return implode(' ', $js);
        
    }
    public static function search($json){
        
        $_o = json_decode(stripslashes($json));
        $_o = (!is_object($_o) || (trim($_o->search) == '' || empty(trim($_o->search)))) ? (object) null : $_o;
        $collection = 'token_search';
        SessionStore::set($collection, $_o);
		$js = [];
        $js[] = DOMElementKitJS::fetchCollection($collection);
        return implode(' ',$js);
        
    }
}