<?php

namespace Sequode\Application\Modules\Auth\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Application\Modules\Auth\Module;
use Sequode\Application\Modules\Traits\Routes\XHR\OperationsDialogTrait as XHROperationsDialogTrait;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;

class Operations {

    use XHROperationsDialogTrait;

    const Module = Module::class;

    public static $dialogs = ['login'];

    public static function login($dialog, $dialog_store, $input) {

        extract((static::Module)::variables());

        switch ($dialog_store->step) {
            case 0:
                if (
                    (
                        $modeler::exists(rawurldecode($input->login), 'email')
                        || $modeler::exists(rawurldecode($input->login), 'name')
                    )
                    && AccountAuthority::isActive($modeler::model())
                ) {
                    $dialog_store->prep->owner_id = $modeler::model()->id;
                    SessionStore::set($dialog->session_store_key, $dialog_store);
                } else {
                    return false;
                }
                break;
            case 1:
                if (
                    $modeler::exists($dialog_store->prep->owner_id, 'id')
                    && AccountAuthority::isPassword(rawurldecode($input->secret), $modeler::model())
                ) {
                    return  [$modeler::model()];
                } else {
                    return false;
                }
                break;
        }
        return true;
    }
}