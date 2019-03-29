<?php

namespace Sequode\Application\Modules\FormInput;

use Sequode\Model\Database\SQL\ORM;

class Model extends ORM {

    const Database_Connection     =   'system_database';

    const Table 				    =	'components';

    const Normalizations = [
        'component_object' => [
            'get' => 'jsonToObject',
            'set' => 'objectToJson'
        ],
        'component_form_object' => [
            'get' => 'jsonToObject',
            'set' => 'objectToJson'
        ]
    ];
    
}
