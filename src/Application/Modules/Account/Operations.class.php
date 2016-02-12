<?php

namespace Sequode\Application\Modules\Account;

use Sequode\Application\Modules\Prototype\Operations\User\ORMModelOwnedModuleModelsTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelSetPasswordTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelFavoritedModuleModelTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelSetEmailTrait;

use Sequode\Foundation\Hashes;

class Operations {
    
    use  ORMModelOwnedModuleModelsTrait,
            ORMModelSetNameTrait,
            ORMModelSetPasswordTrait,
            ORMModelSetEmailTrait,
            ORMModelFavoritedModuleModelTrait;
    
    public static $package = 'Account';
    public static $modeler = Module::$modeler;
    
}