<?php
namespace Sequode\Controller\Database;
class MySQL {
	
	private $_connection;
	public $insertId;
	public $dbName;
	
	public function __construct($dbHost='',$dbUser='',$dbPass='',$dbName='') {
		$this->_connection = mysql_connect($dbHost, $dbUser, $dbPass);
		$this->dbName = $dbName;
		mysql_select_db($this->dbName, $this->_connection);
 	}
	public function query($sql, $returnAsObject=false) {
		mysql_select_db($this->dbName, $this->_connection);
		$query = mysql_query($sql, $this->_connection);
		if(mysql_error()) {
		    return false;
  		}
		$this->insertId = mysql_insert_id();
		if(!$returnAsObject){
			return $query;
		}else{
			return mysql_fetch_object($query);
		}
    }
}