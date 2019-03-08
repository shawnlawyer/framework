<?php

namespace Sequode\Application\Modules\Auth\Components;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Application\Modules\Auth\Module;

class Dialogs {
    
    public static $module = Module::class;
    
    public static function login(){
        
        $_o = (object) [
            'session_store_key' => 'login',
            'session_store_setup' => (object) ['step'=>0, 'prep'=> (object) null],
            'card'=> 'login',
            'steps' => [
                (object) [
                    'forms'=> ['login'],
                    'content'=> (object) [
                        'head' => 'Login',
                        'body' => 'Enter your email address / login key'
                    ],
                    'prep' => true,
                    'required_members' => ['login']
                ],
                (object) [
                    'forms'=> ['secret'],
                    'content'=> (object) [
                        'head' => 'Login Secret',
                        'body' => 'Enter your password / secret key'
                    ],
                    'prep' => true,
                    'required_members' => ['secret'],
                    'operation' => 'login'
                ]
            ],
            'complete' => function(){
                $console_module = ModuleRegistry::model()['Console'];
                forward_static_call_array([$console_module::model()->routes['http'], 'js'], [false]);
            }
        ];
		return $_o; 
    }  
}