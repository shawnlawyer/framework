<?php

namespace Sequode\Application\Modules\Register\Components;

use Sequode\Application\Modules\Register\Module;

class Dialogs {
    
    public static $module = Module::class;
    
    public static function signup(){

        $_o = (object) [
            'session_store_key' => 'signup',
            'session_store_setup' => (object) ['step'=>0, 'prep'=> (object) null],
            'card'=> 'signup',
            'steps' => []
        ];

        $_o->steps[] = (object) [
            'forms' => ['email'],
            'content' => (object) [
                'head' => 'Register Email Address',
                'body' => 'Enter an email address to begin.'
            ],
            'prep' => true,
            'required_members' => ['email']
        ];

        $_o->steps[] = (object) [
            'forms' => ['password'],
            'content' => (object) [
                'head' => 'Create Password',
                'body' => 'A password must be at least 8 characters long and contain at least 1 capital letter (A), 1 lowercase letter (a), 1 number (1) and one symbol character(!).'
            ],
            'prep' => true,
            'required_members' => ['password', 'confirm_password']
        ];

        if($_ENV['EMAIL_VERIFICATION'] == 0) {

            $_o->steps[] = (object)[
                'forms' => ['terms', 'acceptTerms'],
                'content' => (object)[
                    'head' => 'Terms &amp; Conditions of Use',
                    'body' => ''
                ],
                'prep' => true,
                'required_members' => ['accept'],
                'operation' => 'signup'
            ];

            $_o->steps[] = (object) [
                'content' => (object) [
                    'head' => 'Registration Complete!',
                    'body' => 'You can now login.'
                ]
            ];

        }else{

            $_o->steps[] = (object)[
                'forms' => ['terms', 'acceptTerms'],
                'content' => (object)[
                    'head' => 'Terms &amp; Conditions of Use',
                    'body' => ''
                ],
                'prep' => true,
                'required_members' => ['accept']
            ];

            $_o->steps[] = (object)[
                'forms' => ['verify'],
                'content' => (object)[
                    'head' => 'Email Verification',
                    'body' => 'An email has been sent to you containing a verification token. <br/><br/>Copy and Paste the token to verify your email address.'
                ],
                'prep' => true,
                'required_members' => ['token'],
                'operation' => 'signup'
            ];

            $_o->steps[] = (object) [
                'content' => (object) [
                    'head' => 'Registration Complete!',
                    'body' => 'Email address has been verified. You can now login.'
                ]
            ];
        }

        return $_o;

    }

}