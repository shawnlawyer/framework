<?php

namespace Sequode\Application\Modules\User;

use Sequode\Model\Database\SQL\ORM;

class Model extends ORM {

    const Database_Connection     =   'accounts_database';

    const Table                   =	'users';

    const Normalizations = [
        'favorites' =>
            [
                'get' => 'jsonToArray',
                'set' => 'objectToJson'
            ]
    ];

}