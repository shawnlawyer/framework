<?php

namespace Sequode\Application\Modules\Account;

class Module {
    public static $package = 'Account';
	public static function model(){
        $_o = (object)  array (
            'context' => 'account',
            'modeler' => \Sequode\Application\Modules\Account\Modeler::class,
            'components' => (object) array (
                'forms' => Components\Forms::class,
                'cards' => Components\Cards::class,
            },
            'operations' => Operations::class,
            'xhr' => (object) array (
                'operations' => Routes\XHR\Operations::class,
                'cards' => Routes\XHR\Cards::class
            )
        );
        $_o->xhr->dialogs = array(
            'updatePassword' => array(
                'session_store_key' => 'update_password',
                'session_store_setup' => (object) array('step'=>0, 'prep'=> (object) null),
                'card'=> 'updatePassword',
                'steps' => array(
                    (object) array(
                        'forms'=> array('updatePassword'),
                        'content'=> (object)  array(
                            'head' => 'New Password',
                            'body' => 'A password must be at least 8 characters long and contain at least 1 capital letter (A), 1 lowercase letter (a), 1 number (1) and one symbol character(!).'
                        ),
                        'prep' => true,
                        'required_members' => array('password','confirm_password')
                    ),
                    (object) array(
                        'forms'=> array('password'),
                        'content'=> (object) array(
                            'head' => 'Current Password',
                            'body' => 'Enter your current password.'
                        ),
                        'prep' => true,
                        'operation' => 'updatePassword'
                    ),
                    (object) array(
                        'content'=> (object) array(
                            'head' => 'Password Updated',
                            'body' => 'Be sure to use the new password the next time you login.'
                        )
                    )
                )
            ),
            'updateEmail' => array(
                'session_store_key' => 'update_email',
                'session_store_setup' => (object) array('step'=>0, 'prep'=> (object) null),
                'card'=> 'updateEmail',
                'steps' => array(
                    (object) array(
                        'forms'=> array('updateEmail'),
                        'content'=> (object)  array(                            
                            'head' => 'Change Email Address',
                            'body' => 'Enter the new email address to begin.'
                        ),
                        'prep' => true,
                        'required_members' => array('email')
                    ),
                    (object) array(
                        'forms'=> array('verify'),
                        'content'=> (object) array(
                            'head' => 'Email Verification',
                            'body' => 'An email has been sent to you containing a verification token. <br/><br/>Copy and Paste the token to verify your email address.'
                        ),
                        'prep' => true,
                        'required_members' => array('token'),
                        'operation' => 'updateEmail'
                    ),
                    (object) array(
                        'content'=> (object) array(
                            'head' => 'Email Updated',
                            'body' => 'Be sure to use the new email the next time you login.'
                        )
                    )
                )
            )
        );
		return $_o;
	}
}