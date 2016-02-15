<?php

namespace Sequode\Application\Modules\Sequode;

use Sequode\Application\Modules\Prototype\Operations\Sequencing\ORMModelManageCodeTrait;
use Sequode\Application\Modules\Prototype\Operations\Sequencing\ORMModelManageSequenceGridTrait;
use Sequode\Application\Modules\Prototype\Operations\Sequencing\ORMModelManageSequenceIPODataFlowTrait;
use Sequode\Application\Modules\Prototype\Operations\Sequencing\ORMModelManageSequenceTrait;
use Sequode\Application\Modules\Prototype\Operations\Sequencing\ORMModelSetDescriptionTrait;
use Sequode\Application\Modules\Prototype\Operations\Sequencing\ORMModelSetFormInputComponentSettingsTrait;
use Sequode\Application\Modules\Prototype\Operations\Sequencing\ORMModelSetNameTrait;
use Sequode\Application\Modules\Prototype\Operations\Sequencing\ORMModelSetPropertySwitchesTrait;

class Operations {
    
    use  ORMModelManageCodeTrait.
            ORMModelManageSequenceGridTrait,
            ORMModelManageSequenceIPODataFlowTrait,
            ORMModelManageSequenceTrait,
            ORMModelSetDescriptionTrait,
            ORMModelSetFormInputComponentSettingsTrait,
            ORMModelSetNameTrait,
            ORMModelSetPropertySwitchesTrait;
    
    public static $modeler = Modeler:class;
    public static $kit = Kits\Operations::class;
    
}