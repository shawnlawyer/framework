<?php

namespace Sequode\Application\Modules\Account;

use Sequode\Application\Modules\User\Traits\Operations\ORMModelOwnedModuleModelsTrait;
use Sequode\Application\Modules\User\Traits\Operations\ORMModelSetNameTrait;
use Sequode\Application\Modules\User\Traits\Operations\ORMModelSetPasswordTrait;
use Sequode\Application\Modules\User\Traits\Operations\ORMModelFavoritedModuleModelsTrait;
use Sequode\Application\Modules\User\Traits\Operations\ORMModelSetEmailTrait;

use Sequode\Application\Modules\User\Modeler;

class Operations {
    
    use  ORMModelOwnedModuleModelsTrait,
            ORMModelSetNameTrait,
            ORMModelSetPasswordTrait,
            ORMModelSetEmailTrait,
            ORMModelFavoritedModuleModelsTrait;
    
    public static $modeler = Modeler::class;
    
}