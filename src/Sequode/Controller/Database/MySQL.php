<?php
namespace Sequode\Controller\Database;
use mysqli;

class MySQL {
	
	public $connection;
	public $insertId;
	public $dbName;
	
	public function __construct($dbHost='',$dbUser='',$dbPass='',$dbName='') {
		$this->connection = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
        $this->dbName = $dbName;
 	}
	public function query($sql, $returnAsObject=false) {
        $this->connection->select_db($this->dbName);
		$result = $this->connection->query($sql);

		if($this->connection->error) {
		    if ($_ENV['APP_ENV'] == 'local-dev'){
		        die($sql . "\n" . $this->connection->error);
            }else {
                return false;
            }
  		}
		$this->insertId = $this->connection->insert_id;
		if(!$returnAsObject){
			return $result;
		}else{
			return $result->fetch_object();
		}
    }
}