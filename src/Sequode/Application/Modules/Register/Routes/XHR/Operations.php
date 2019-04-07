<?php

namespace Sequode\Application\Modules\Register\Routes\XHR;

use Sequode\Application\Modules\Register\Module;
use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\View\Email\EmailContent;
use Sequode\Controller\Email\Email;
use Sequode\Foundation\Hashes;
use Sequode\Application\Modules\Traits\Routes\XHR\OperationsDialogTrait as XHROperationsDialogTrait;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;

class Operations {

    use XHROperationsDialogTrait;
    
    public static $module = Module::class;
    const Module = Module::class;

    public static $dialogs = ['signup'];

    public static function signup($dialog, $dialog_store, $input){

        extract((static::Module)::variables());

        switch ($dialog_store->step) {
            case 0:
                if (
                    !$modeler::exists(rawurldecode($input->email), 'email')
                    && AccountAuthority::isAnEmailAddress(rawurldecode($input->email))
                ) {
                    $dialog_store->prep->email = rawurldecode($input->email);
                    $dialog_store->prep->token = Hashes::generateHash();
                    SessionStore::set($dialog->session_store_key, $dialog_store);
                } else {

                    SessionStore::set('error', 'Login exists!');
                    return false;
                }
                break;
            case 1:
                if (
                    rawurldecode($input->password) == rawurldecode($input->confirm_password)
                    && AccountAuthority::isSecurePassword(rawurldecode($input->password))
                ) {
                    $dialog_store->prep->password = rawurldecode($input->password);
                    SessionStore::set($dialog->session_store_key, $dialog_store);
                } else {
                    return false;
                }
                break;
            case 2:
                if (
                    rawurldecode($input->accept) == 1
                ) {
                    if ($_ENV['EMAIL_VERIFICATION'] == true) {
                        $hooks = [
                            "searchStrs" => ['#TOKEN#'],
                            "subjectStrs" => [$dialog_store->prep->token]
                        ];
                        Email::systemSend($dialog_store->prep->email, 'Verify your email address with sequode.com', EmailContent::render('activation.txt', $hooks));
                    } else {
                        if (
                        !$modeler::exists($dialog_store->prep->email, 'email')
                        ) {
                            return [$dialog_store->prep->email, $dialog_store->prep->password];
                        } else {
                            return false;
                        }
                    }
                } else {
                    return false;
                }
                break;
            case 3:
                if ($_ENV['EMAIL_VERIFICATION'] == true) {
                    if (
                        !$modeler::exists($dialog_store->prep->email, 'email')
                        && $dialog_store->prep->token == trim(rawurldecode($input->token))
                    ) {
                        return [$dialog_store->prep->email, $dialog_store->prep->password];
                    } else {
                        return false;
                    }
                }
                break;
        }
        return true;
    }
}