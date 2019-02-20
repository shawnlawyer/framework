<?php

namespace Sequode\Application\Modules\Auth\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Model\Module\Registry as ModuleRegistry;

use Sequode\Application\Modules\Auth\Module;

use Sequode\Component\Dialog\Traits\OperationsTrait;

class Operations
{

    use OperationsTrait;

    public static $module = Module::class;

    public static $dialogs = ['login'];

    public static function login($dialog, $dialog_store, $input)
    {
        $module = static::$module;
        $modeler = $module::model()->modeler;

        switch ($dialog_store->step) {
            case 0:
                if (
                    (
                        $modeler::exists(rawurldecode($input->login), 'email')
                        || $modeler::exists(rawurldecode($input->login), 'name')
                    )
                    && \Sequode\Application\Modules\Account\Authority::isActive($modeler::model())
                ) {
                    $dialog_store->prep->user_id = $modeler::model()->id;
                    SessionStore::set($dialog->session_store_key, $dialog_store);
                } else {
                    return false;
                }
                break;
            case 1:
                if (
                    $modeler::exists($dialog_store->prep->user_id, 'id')
                    && \Sequode\Application\Modules\Account\Authority::isPassword(rawurldecode($input->secret), $modeler::model())
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