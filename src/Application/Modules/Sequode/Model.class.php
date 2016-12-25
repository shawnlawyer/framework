<?php

namespace Sequode\Application\Modules\Sequode;

use Sequode\Model\Database\SQL\ORM;

class Model extends ORM {
    public $database_connection     =   'sequodes_database';
	public $table 					=	'sequodes';
	public $usage_types				=	array(0=>'code',1=>'sequence',2=>'set');
	public $coding_types			=	array(0=>'structured',1=>'object_oriented');
	public function __construct() {
		parent::__construct();
		return true;
	}
	public function create($name, $printable_name, $usage_type, $version){
		$sql = "
			INSERT INTO {$this->table}
		 	(`id`,`name`,`printable_name`,`usage_type`,`version`)
			VALUES
		 	(''
			,".$this->safedSQLData($name, 'text')."
			,".$this->safedSQLData($printable_name, 'text')."
			,".$this->safedSQLData($usage_type, 'text')."
			,".$this->safedSQLData($version, 'text').")
			";
		$this->database->query($sql);
		$this->id = $this->database->insertId;
		return true;
	}
}