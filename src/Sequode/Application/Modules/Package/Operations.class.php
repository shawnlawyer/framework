<?php

namespace Sequode\Application\Modules\Package;

use Sequode\Application\Modules\Package\Traits\Operations\ORMModelCreateTrait;
use Sequode\Application\Modules\Package\Traits\Operations\ORMModelDeleteTrait;
use Sequode\Application\Modules\Package\Traits\Operations\ORMModelSetNameTrait;
use Sequode\Application\Modules\Package\Traits\Operations\ORMModelSetPropertiesTrait;
use Sequode\Application\Modules\Package\Traits\Operations\ORMModelExportSequenceSetTrait;

use Sequode\Application\Modules\Token\Traits\Operations\ORMModelGetOwnedModelsTrait;


class Operations {
    
    use ORMModelCreateTrait,
        ORMModelDeleteTrait,
        ORMModelGetOwnedModelsTrait,
        ORMModelSetNameTrait,
        ORMModelSetPropertiesTrait,
        ORMModelExportSequenceSetTrait;

    public static $modeler = Modeler::class;
    
}