<?php

namespace Sequode\Application\Modules\Account;

use Sequode\Application\Modules\Prototype\Operations\User\ORMModelOwnedModuleModelsTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelSetNameTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelSetPasswordTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelFavoritedModuleModelsTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelSetEmailTrait;

class Operations {
    
    use  ORMModelOwnedModuleModelsTrait,
            ORMModelSetNameTrait,
            ORMModelSetPasswordTrait,
            ORMModelSetEmailTrait,
            ORMModelFavoritedModuleModelsTrait;
    
    public static $modeler = Modeler::class;
    
}