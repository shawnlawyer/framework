<?php

namespace Sequode\Application\Modules\Account;

use Sequode\Application\Modules\Prototype\Operations\User\ORMModelOwnedModuleModelsTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelSetNameTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelSetPasswordTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelFavoritedModuleModelTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelSetEmailTrait;

class Operations {
    
    use  ORMModelOwnedModuleModelsTrait,
            ORMModelSetNameTrait,
            ORMModelSetPasswordTrait,
            ORMModelSetEmailTrait,
            ORMModelFavoritedModuleModelTrait;
    
    public static $modeler = Modeler::class;
    
}