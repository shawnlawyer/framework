<?php

namespace Sequode\Application\Models\ORM;

use Sequode\Model\Database\SQL\ORM;

class BlockedIPs extends ORM {
    public $database_connection     =   'sessions_database';
	public $table                   =	'ip_blacklist';
	public function __construct() {
		parent::__construct();
		return true;
	}
    
	public function create($ip_address){

        $sql = "
            INSERT INTO {$this->table}
            (`id`,`ip_address`,`time`)
            VALUES
            (
            ''
            ,".$this->safedSQLData($ip_address, 'text')."
            ,".time()."
            )";
		$this->database->query($sql);
		$this->id = $this->database->insertId;
		return true;
	}	 
}