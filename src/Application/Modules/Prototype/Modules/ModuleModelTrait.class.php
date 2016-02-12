<?php

namespace Sequode\Application\Modules\Prototype\Modules;

use Sequode\Model\Module\Registry as ModuleRegistry;

trait ModuleModelTrait {
    public static function getModuleModel(){
        return ModuleRegistry::model(static::$package);
    }
}