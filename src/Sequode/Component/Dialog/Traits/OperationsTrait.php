<?php

namespace Sequode\Component\Dialog\Traits;

use Sequode\Application\Modules\Session\Store as SessionStore;

trait OperationsTrait {

    public static function dialog($method, $json=null){
        $error = false;
        $_a = [];
        $module = static::$module;
        $dialogs = $module::model()->components->dialogs;
        $dialog = forward_static_call_array([$dialogs, $method], []);

        if(!SessionStore::is($dialog->session_store_key)){ return; }

        $xhr_cards = $module::model()->xhr->cards;
        $operations = $module::model()->operations;

        if($json != null){
            $input = json_decode(rawurldecode($json));
            if(isset($input->reset)){
                SessionStore::set($dialog->session_store_key, $dialog->session_store_setup);
                return forward_static_call_array([$xhr_cards, $method], []);
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

        if(!$error){
            $dialog_store->step++;
            SessionStore::set($dialog->session_store_key, $dialog_store);
            return forward_static_call_array([$xhr_cards, $method], []);
        }
    }
}