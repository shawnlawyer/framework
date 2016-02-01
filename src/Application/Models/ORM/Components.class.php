<?php

namespace Sequode\Application\Models\ORM;

use Sequode\Model\Database\SQL\ORM;

class Components extends ORM {
    public $database_connection     =   'system_database';
	public $table 				    =	'components';
	public function __construct() {
		parent::__construct();
		return true;
	}
	public function create($name, $printable_name, $usage_type){
		$sql = "
			INSERT INTO {$this->table}
			(`id`,`name`,`printable_name`,`usage_type`)
			VALUES
		 	(''
			,".$this->database->safeData($name, "text")."
			,".$this->database->safeData($printable_name, "text")."
			";
		$this->database->query($sql);
		$this->id = $this->database->insertId;
		return true;
	}
}