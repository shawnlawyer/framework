<?php

namespace Sequode\Application\Models\ORM;

use Sequode\Model\Database\SQL\ORM;

class Roles extends ORM {
    public $database_connection     =   'accounts_database';
	public $table                   =	'roles';
	public function __construct() {
		parent::__construct();
		return true;
	}
}