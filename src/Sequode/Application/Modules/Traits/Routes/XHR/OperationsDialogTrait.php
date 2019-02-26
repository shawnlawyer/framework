<?php

namespace Sequode\Application\Modules\Traits\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;

trait OperationsDialogTrait {

    public static function dialog($method, $json=null){
        $error = false;
        $_a = [];
        $module = static::$module;
        $dialog = forward_static_call_array([$module::model()->components->dialogs, $method], []);

        if(!SessionStore::is($dialog->session_store_key)){ return; }

        $xhr_cards = $module::model()->xhr->cards;
        $operations = $module::model()->operations;

        if($json != null){
            $input = json_decode(rawurldecode($json));
            if(isset($input->reset)){
                SessionStore::set($dialog->session_store_key, $dialog->session_store_setup);
                if(!method_exists($xhr_cards, $method) && in_array('Sequode\\Component\\Card\\Traits\\CardsTrait', class_uses($xhr_cards, true))) {
                    return forward_static_call_array([$xhr_cards, 'card'], [$method]);
                }else{
                    return forward_static_call_array([$xhr_cards, $method], []);
                }
            }
        }

        $dialog_store = SessionStore::get($dialog->session_store_key);
        $dialog_step = $dialog->steps[$dialog_store->step];


        if(isset($dialog_step->prep) && $dialog_step->prep == true){

            if(isset($dialog_step->required_members)){

                foreach($dialog_step->required_members as $m){
                    if(!isset($input->$m)){ return;}
                }
            }
            $_a = forward_static_call_array([static::class, $method], [$dialog, $dialog_store, $input]);
            if($_a == false){
                $error = true;
            }
        }
        if(!$error && isset($dialog_step->operation)){
            if(!(forward_static_call_array([$operations, $dialog_step->operation], $_a))){
                $error = true;
            }
        }
        if(!$error) {
            $dialog_store->step++;
            SessionStore::set($dialog->session_store_key, $dialog_store);
            if(isset($dialog->complete) && $dialog_store->step == count($dialog->steps)) {
                return ($dialog->complete)([]);
            }else{
                if(!method_exists($xhr_cards, $method) && in_array('Sequode\\Component\\Card\\Traits\\CardsTrait', class_uses($xhr_cards, true))) {
                    return forward_static_call_array([$xhr_cards, 'card'], [$method]);
                }else{
                    return forward_static_call_array([$xhr_cards, $method], []);
                }
            }
        }
    }
}