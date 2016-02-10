<?php

namespace Sequode\Application\Models\ORM;

use Sequode\Model\Database\SQL\ORM;

class Tokens extends ORM {
    public $database_connection     =   'accounts_database';
	public $table 					=	'tokens';
	public function __construct() {
		parent::__construct();
		return true;
	}
	public function create(){
		$sql = "
			INSERT INTO {$this->table}
		 	(`id`,`owner_id`,`name`,`token`)
			VALUES
		 	(''
			,0
			,'New Token'
            ,".$this->safedSQLData('TOK' . sha1(microtime().uniqid(rand(), true)), 'text')."
            )
			";
		$this->database->query($sql);
		$this->id = $this->database->insertId;
		return true;
	}
}