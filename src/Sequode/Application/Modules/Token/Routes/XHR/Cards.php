<?php

namespace Sequode\Application\Modules\Token\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Card as ModuleCard;
use Sequode\Component\Card\Kit\JS as CardKitJS;

use Sequode\Application\Modules\Token\Module;

use Sequode\Component\Card\Traits\CardsTrait;

class Cards {

    use CardsTrait;
    
    public static $module = Module::class;
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
        && (\Sequode\Application\Modules\Account\Authority::isOwner( $modeler::model() )
        || \Sequode\Application\Modules\Account\Authority::isSystemOwner())
        )){return false;}
    }
}