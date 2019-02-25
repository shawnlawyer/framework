<?php

namespace Sequode\Application\Modules\Package\Routes\XHR;

use Sequode\View\Module\Card as ModuleCard;
use Sequode\Component\Card\Kit\JS as CardKitJS;
use Sequode\Application\Modules\Package\Module;
use Sequode\Component\Card\Traits\CardsTrait;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;

class Cards {

    use CardsTrait;

    public static $module = Module::class;

    public static $merge = true;

    public static $routes = [
        'details',
        'search',
        'my'
    ];

    public static function details($_model_id=0){
        $module = static::$module;
        $modeler = $module::model()->modeler;
        if(!(
        $modeler::exists($_model_id,'id')
        && (AccountAuthority::isOwner( $modeler::model() )
        || AccountAuthority::isSystemOwner())
        )){return false;}
    }
}