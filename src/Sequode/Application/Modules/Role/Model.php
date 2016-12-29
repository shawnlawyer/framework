<?php

namespace Sequode\Application\Modules\Role;

use Sequode\Model\Database\SQL\ORM;

class Model extends ORM {
    public $database_connection     =   'accounts_database';
	public $table                   =	'roles';
	public function __construct() {
		parent::__construct();
		return true;
	}
}