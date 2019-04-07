<?php

namespace Sequode\Application\Modules\Account\Components;

use Sequode\Application\Modules\Account\Module;

class Dialogs {
    
    const Module = Module::class;
    
    public static function updatePassword(){
        $_o = (object) [
            'session_store_key' => 'update_password',
            'session_store_setup' => (object) ['step'=>0, 'prep'=> (object) null],
            'card'=> 'updatePassword',
            'steps' => [
                (object) [
                    'forms'=> ['updatePassword'],
                    'content'=> (object)  [
                        'head' => 'New Password',
                        'body' => 'A password must be at least 8 characters long and contain at least 1 capital letter (A), 1 lowercase letter (a), 1 number (1) and one symbol character(!).'
                    ],
                    'prep' => true,
                    'required_members' => ['password','confirm_password']
                ],
                (object) [
                    'forms'=> ['password'],
                    'content'=> (object) [
                        'head' => 'Current Password',
                        'body' => 'Enter your current password.'
                    ],
                    'prep' => true,
                    'operation' => 'updatePassword'
                ],
                (object) [
                    'content'=> (object) [
                        'head' => 'Password Updated',
                        'body' => 'Be sure to use the new password the next time you login.'
                    ]
                ]
            ]
        ];
        
        return $_o;
        
    }
    
    public static function updateEmail(){
        
        $_o = (object) [
            'session_store_key' => 'update_email',
            'session_store_setup' => (object) ['step'=>0, 'prep'=> (object) null],
            'card'=> 'updateEmail',
            'steps' => []
        ];

        if ($_ENV['EMAIL_VERIFICATION'] == true) {

            $_o->steps[] = (object) [
                'forms'=> ['updateEmail'],
                'content'=> (object)  [
                    'head' => 'Change Email Address',
                    'body' => 'Enter the new email address to begin.'
                ],
                'prep' => true,
                'required_members' => ['email']
            ];
            $_o->steps[] = (object)[
                'forms' => ['verify'],
                'content' => (object)[
                    'head' => 'Email Verification',
                    'body' => 'An email has been sent to you containing a verification token. <br/><br/>Copy and Paste the token to verify your email address.'
                ],
                'prep' => true,
                'required_members' => ['token'],
                'operation' => 'updateEmail'
            ];
        }else{
            $_o->steps[] = (object) [
                'forms'=> ['updateEmail'],
                'content'=> (object)  [
                    'head' => 'Change Email Address',
                    'body' => 'Enter the new email address.'
                ],
                'prep' => true,
                'required_members' => ['email'],
                'operation' => 'updateEmail'
            ];
        }
        $_o->steps[] = (object) [
            'content'=> (object) [
                'head' => 'Email Updated',
                'body' => 'Be sure to use the new email the next time you login.'
            ],
        ];
        return $_o;
        
    }
    
}