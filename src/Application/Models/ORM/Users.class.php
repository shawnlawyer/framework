<?php

namespace Sequode\Application\Models\ORM;

use Sequode\Model\Database\SQL\ORM;

class Users extends ORM {
    public $database_connection     =   'accounts_database';
	public $table                   =	'users';
    public function __construct() {
		parent::__construct();
		return true;
    }
	public function create($username, $password, $email){

        $sql = "
            INSERT INTO {$this->table}
            (`id`,`role_id`,`username`,`password`,`email`,`sign_up_date`,`sequode_favorites`,`activation_token`)
            VALUES
            (
            ''
            ,100
            ,".$this->safedSQLData($username, 'text')."
            ,".$this->safedSQLData($password, 'text')."
            ,".$this->safedSQLData($email, 'text')."
            ,".time()."
            ,'[]'
            ,".$this->safedSQLData(SQDE_Session::uniqueHash(), 'text')."
            )";
		$this->database->query($sql);
		$this->id = $this->database->insertId;
		return true;
	}	 
}