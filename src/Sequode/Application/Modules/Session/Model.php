<?php

namespace Sequode\Application\Modules\Session;

use Sequode\Model\Database\SQL\ORM;

class Model extends ORM {

    public $database_connection     =   'sessions_database';

	public $table                   =	'sessions';

    const normalizations = [
        'session_data' =>
            [
                'get' => 'serializedToObject',
                'set' => 'objectToSerialized'
            ]
    ];

	public function __construct() {
		parent::__construct();
		return true;
	}
}