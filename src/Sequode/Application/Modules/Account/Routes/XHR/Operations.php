<?php

namespace Sequode\Application\Modules\Account\Routes\XHR;

use Sequode\Application\Modules\Account\Module;
use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\View\Email\EmailContent;
use Sequode\Controller\Email\Email;
use Sequode\Foundation\Hashes;
use Sequode\Application\Modules\Traits\Routes\XHR\OperationsDialogTrait as XHROperationsDialogTrait;
use Sequode\Application\Modules\Sequode\Modeler as SequodeModeler;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;

class Operations {
    
    const Module = Module::class;

    use XHROperationsDialogTrait;
    /*
	public static $merge = false;
	public static $routes = [
		'addToSequodeFavorites',
        'removeFromSequodeFavorites',
        'emptySequodeFavorites',
		'updatePassword',
		'updateEmail'
	];
    
	public static $routes_to_methods = [
		'addToSequodeFavorites' => 'addToSequodeFavorites',
        'removeFromSequodeFavorites' => 'removeFromSequodeFavorites',
        'emptySequodeFavorites' => 'emptySequodeFavorites',
		'updatePassword' => 'updatePassword',
		'updateEmail' => 'updateEmail'
    ];
    */

    public static $dialogs = [
        'updatePassword',
        'updateEmail'
    ];

    public static function emptySequodeFavorites($confirmed=false){

        extract((static::Module)::variables());
        $collection = 'sequode_favorites';

        if ($confirmed===false){

            return DOMElementKitJS::confirmOperation($module::xhrOperationRoute(__FUNCTION__), null, 'Are you sure you want to empty your sequode favorites?');

        } else {

            forward_static_call_array([$operations, __FUNCTION__], []);

            return implode(' ', [
                DOMElementKitJS::fetchCollection($collection)
            ]);

        }
    }

    public static function addToSequodeFavorites($_model_id){

        extract((static::Module)::variables());
        $collection = 'sequode_favorites';

        if(!(

            SequodeModeler::exists($_model_id,'id')
            && AccountAuthority::canRun(SequodeModeler::model())

        )){ return; }

        forward_static_call_array([$operations, __FUNCTION__], []);

        return implode(' ', [
            DOMElementKitJS::fetchCollection($collection),
            DOMElementKitJS::fetchCollection('sequodes', SequodeModeler::model()->id),
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);

    }

    public static function removeFromSequodeFavorites($_model_id){

        extract((static::Module)::variables());
        $collection = 'sequode_favorites';

        if(!(

            SequodeModeler::exists($_model_id,'id')
            && AccountAuthority::isInSequodeFavorites(SequodeModeler::model())

        )){ return; }

        forward_static_call_array([$operations, __FUNCTION__], []);

        return implode(' ', [
            DOMElementKitJS::fetchCollection($collection),
            DOMElementKitJS::fetchCollection('sequodes', SequodeModeler::model()->id),
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);

    }

    public static function updatePassword($dialog, $dialog_store, $input){

        extract((static::Module)::variables());

        switch($dialog_store->step){
            case 0:
                if(
                    rawurldecode($input->password) == rawurldecode($input->confirm_password)
                    && AccountAuthority::isSecurePassword(rawurldecode($input->password))
                ){
                    $dialog_store->prep->new_secret = rawurldecode($input->password);
                    SessionStore::set($dialog->session_store_key, $dialog_store);
                }
                else
                {
                    return false;
                }
                break;
            case 1:
                if(
                    AccountAuthority::isPassword(rawurldecode($input->password), $modeler::model())
                ){
                    return [$dialog_store->prep->new_secret];
                }
                else
                {
                    return false;
                }
                break;

        }
        return true;
    }
    
    public static function updateEmail($dialog, $dialog_store, $input){

        extract((static::Module)::variables());

        switch($dialog_store->step){
                
            case 0:
                if(
                    !$modeler::exists(rawurldecode($input->email),'email')
                    && AccountAuthority::isAnEmailAddress(rawurldecode($input->email))
                ){

                    $dialog_store->prep->new_email = rawurldecode($input->email);
                    $dialog_store->prep->token = Hashes::generateHash();
                    SessionStore::set($dialog->session_store_key, $dialog_store);

                    if ($_ENV['EMAIL_VERIFICATION'] == true) {
                        $hooks = [
                            "searchStrs" => ['#TOKEN#'],
                            "subjectStrs" => [$dialog_store->prep->token]
                        ];
                        Email::systemSend($dialog_store->prep->new_email,'Verify your email address with sequode.com', EmailContent::render('activation.txt',$hooks));
                    }else{
                        return [$dialog_store->prep->new_email];
                    }
                }
                else
                {
                    return false;
                }
                break;

            case 1:
                if ($_ENV['EMAIL_VERIFICATION'] == true) {
                    if(
                        $dialog_store->prep->token == rawurldecode($input->token)
                    ){
                        return [$dialog_store->prep->new_email];
                    }
                    else
                    {
                        return false;
                    }
                }
                break;

        }
        return true;
    }
}