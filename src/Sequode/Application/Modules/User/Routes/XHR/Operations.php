<?php

namespace Sequode\Application\Modules\User\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

use Sequode\Application\Modules\User\Module;

use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\Role\Modeler as RoleModeler;

class Operations {

    public static $module = Module::class;
    const Module = Module::class;

    public static function newUser(){

        extract((static::Module)::variables());
        $collection = $module::model()->context;
        
        if(!(
        AccountAuthority::isSystemOwner()
        )){ return; }

        forward_static_call_array([$operations, __FUNCTION__], []);

        return implode(' ', [
            DOMElementKitJS::fetchCollection($collection, $modeler::model()->id),
            forward_static_call_array([$xhr_cards, 'card'], ['details'])
        ]);

    }

    public static function newGuest(){

        extract((static::Module)::variables());
        $collection = $module::model()->context;
        
        if(!(
        AccountAuthority::isSystemOwner()
        )){ return; }

        forward_static_call_array([$operations, __FUNCTION__], []);

        return implode(' ', [
            DOMElementKitJS::fetchCollection($collection, $modeler::model()->id),
            forward_static_call_array([$xhr_cards, 'card'], ['details'])
        ]);

    }

    public static function delete($_model_id, $confirmed=false){

        extract((static::Module)::variables());
        $collection = 'users';

        if(!(
        AccountAuthority::isSystemOwner()
        && $modeler::exists($_model_id,'id')
        )){return;}

        if ($confirmed===false){

            return DOMElementKitJS::confirmOperation($module::xhrOperationRoute(__FUNCTION__), $modeler::model()->id);

        }else{

            forward_static_call_array([$operations, __FUNCTION__], []);

            return implode(' ', [
                DOMElementKitJS::fetchCollection($collection, $modeler::model()->id),
                forward_static_call_array([$xhr_cards, 'card'], ['search'])
            ]);

        }

    }

    public static function loginAs($_model_id){

        extract((static::Module)::variables());

        if(!(
            AccountAuthority::isSystemOwner()
            && $modeler::exists($_model_id,'id')
        )){return;}

        forward_static_call_array([$operations, 'login'], []);

        return implode(' ', [
            'new Console();'
        ]);

    }

    public static function updatePassword($_model_id, $json){

        extract((static::Module)::variables());
        $collection = $module::model()->context;

        $input = json_decode(rawurldecode($json));
        if(!(
            AccountAuthority::isSystemOwner()
            && $modeler::exists($_model_id,'id')
        )){return;}

        forward_static_call_array([$operations, __FUNCTION__], [$input->password]);

        return implode(' ', [
            DOMElementKitJS::fetchCollection($collection, $modeler::model()->id),
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);

    }

    public static function updateRole($_model_id, $json){

        extract((static::Module)::variables());
        $collection = $module::model()->context;

        $input = json_decode(rawurldecode($json));
        if(!(
            AccountAuthority::isSystemOwner()
            && $modeler::exists($_model_id,'id')
            && RoleModeler::exists($input->role,'id')
        )){return;}

        forward_static_call_array([$operations, __FUNCTION__], []);

        return implode(' ', [
            DOMElementKitJS::fetchCollection($collection, $modeler::model()->id),
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);

    }

    public static function updateActive($_model_id, $json){

        extract((static::Module)::variables());
        $collection = $module::model()->context;

        $input = json_decode(rawurldecode($json));
        if(!(
            AccountAuthority::isSystemOwner()
            && $modeler::exists($_model_id,'id')
        )){return;}

        forward_static_call_array([$operations, __FUNCTION__], [$input->active]);

        return implode(' ', [
            DOMElementKitJS::fetchCollection($collection, $modeler::model()->id),
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);

    }

    public static function updateName($_model_id, $json){

        extract((static::Module)::variables());
        $collection = $module::model()->context;
        
        if(!(
            $modeler::exists($_model_id,'id')
        )){ return; }

        $input = json_decode($json);
        $name = trim(str_replace('-','_',str_replace(' ','_',urldecode($input->name))));
        if(strlen($name)==0){
            return ' alert(\'Name cannot be empty\');';
        }

        if(!preg_match("/^([A-Za-z0-9_])*$/i",$name)){
            return ' alert(\'Name can be alphanumeric and contain spaces only\');';
        }
        
        if($modeler::exists($name,'name') && $modeler::model()->id != $_model_id){
            return ' alert(\'Name already exists\');';
        }elseif($modeler::exists($name,'name') && $modeler::model()->id == $_model_id){
            return;
        }
        
        $modeler::exists($_model_id, 'id');

        forward_static_call_array([$operations, __FUNCTION__], [$name]);

        return implode(' ', [
            DOMElementKitJS::fetchCollection($collection, $modeler::model()->id),
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);
        
    }

    public static function search($json){

        extract((static::Module)::variables());
        $collection = 'user_search';

        $input = @json_decode(stripslashes($json));
        $input = (!is_object($input) || (trim($input->search) == '' || empty(trim($input->search)))) ? (object) null : $input;

        SessionStore::set($collection, $input);

        return implode(' ', [
            DOMElementKitJS::fetchCollection($collection)
        ]);

    }
}