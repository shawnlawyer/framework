<?php

namespace Sequode\Application\Modules\User;

use Sequode\Model\Database\SQL\ORM;

class Model extends ORM {
    public $database_connection     =   'accounts_database';
	public $table                   =	'users';
    public function __construct() {
		parent::__construct();
		return true;
    }
    
	public function create($name = '', $password = '', $email = ''){

        $sql = "
            INSERT INTO {$this->table}
            (`id`,`role_id`,`name`,`password`,`email`,`sign_up_date`,`sequode_favorites`,`activation_token`)
            VALUES
            (
            0
            ,100
            ,".$this->safedSQLData($name, 'text')."
            ,".$this->safedSQLData($password, 'text')."
            ,".$this->safedSQLData($email, 'text')."
            ,".time()."
            ,'[]'
            ,".$this->safedSQLData(sha1(substr(md5(uniqid(rand(), true)), 0, 25)), 'text')."
            )";
            
		$this->database->query($sql);
		$this->id = $this->database->insertId;
        
		return true;
        
	}
}