<?php

namespace Sequode\Application\Modules\Sequode;

use Sequode\Application\Modules\Sequode\Traits\Operations\ORMModelManageCodeTrait;
use Sequode\Application\Modules\Sequode\Traits\Operations\ORMModelManageSequenceGridTrait;
use Sequode\Application\Modules\Sequode\Traits\Operations\ORMModelManageSequenceIPODataFlowTrait;
use Sequode\Application\Modules\Sequode\Traits\Operations\ORMModelManageSequenceTrait;
use Sequode\Application\Modules\Sequode\Traits\Operations\ORMModelSetDescriptionTrait;
use Sequode\Application\Modules\Sequode\Traits\Operations\ORMModelSetFormInputComponentSettingsTrait;
use Sequode\Application\Modules\Sequode\Traits\Operations\ORMModelSetNameTrait;
use Sequode\Application\Modules\Sequode\Traits\Operations\ORMModelSetPropertySwitchesTrait;

class Operations {
    
    use  ORMModelManageCodeTrait,
            ORMModelManageSequenceGridTrait,
            ORMModelManageSequenceIPODataFlowTrait,
            ORMModelManageSequenceTrait,
            ORMModelSetDescriptionTrait,
            ORMModelSetFormInputComponentSettingsTrait,
            ORMModelSetNameTrait,
            ORMModelSetPropertySwitchesTrait;
    
    public static $modeler = Modeler::class;
    public static $kit = Kits\Operations::class;
    
}