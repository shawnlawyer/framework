<?php

namespace Sequode\Application\Modules\Auth;

class Module {
    public static $package = 'Auth';
	public static function model(){
        $_o = (object)  array (
            'context' => 'auth',
            'modeler' => Modeler::class,
            'card_objects' => Components\Cards::class,
            'form_objects' => Components\Forms::class,
            'operations' => Operations::class
            'xhr' => (object) array (
                'operations' => Routes\XHR\Operations::class,
                'cards' => Routes\XHR\Cards::class
            )
        );
        $_o->xhr->dialogs = array(
            'login' => array(
                'session_store_key' => 'login',
                'session_store_setup' => (object) array('step'=>0, 'prep'=> (object) null),
                'card'=> 'login',
                'steps' => array(
                    (object) array(
                        'forms'=> array('login'),
                        'content'=> (object) array(
                            'head' => 'Login',
                            'body' => 'Enter your email address / login key'
                        ),
                        'prep' => true,
                        'required_members' => array('login')
                    ),
                    (object) array(
                        'forms'=> array('secret'),
                        'content'=> (object) array(
                            'head' => 'Login Secret',
                            'body' => 'Enter your password / secret key'
                        ),
                        'prep' => true,
                        'required_members' => array('secret'),
                        'operation' => 'login'
                    )
                )
            )
        );
		return $_o;
	}
}