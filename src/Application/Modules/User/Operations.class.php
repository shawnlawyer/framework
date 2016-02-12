<?php
namespace Sequode\Application\Modules\User;

use Sequode\Application\Modules\Prototype\Operations\User\ORMModelCreateTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelSetNameTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelSetEmailTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelSetPasswordTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelDeleteTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelSetLastSignIn;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelSetActiveTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelFavoritedModuleModelTrait;

use Sequode\Application\Modules\Prototype\Operations\User\ORMModelUpdateRoleTrait;

class Operations {
   
    use ORMModelCreateTrait,
           ORMModelSetNameTrait,
           ORMModelSetEmailTrait,
           ORMModelSetPasswordTrait,
           ORMModelDeleteTrait,
           ORMModelSetLastSignIn,
           ORMModelSetActiveTrait,
           ORMModelFavoritedModuleModelTrait,
           ORMModelSetRoleTrait;
    
    public static $modeler = Modeler::class;
    
}