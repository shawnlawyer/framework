<?php

namespace Sequode\Application\Modules\Auth\Components;

use Sequode\Application\Modules\Session\Store as SessionStore;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\Card\CardKit as CardKit;
use Sequode\Component\Form\Form as FormComponent;

use Sequode\Application\Modules\Auth\Module;

class Dialogs {
    
    public static $module = Module::class;
    
    public static function login(){
        
        $_o = (object) array(
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
            ),
            'complete' => function(){
                $console_module = ModuleRegistry::model()['Console'];
                forward_static_call_array([$console_module::model()->routes['http'], 'js'], [false]);
            }
        );
		return $_o; 
    }  
}