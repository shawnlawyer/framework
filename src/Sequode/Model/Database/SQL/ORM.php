<?php
namespace Sequode\Model\Database\SQL;

use Sequode\Model\Database\ResourceConnection;

class ORM {
    //Setting object members
    public $table 			=	'';
    public $orderBy			=	'id';
    public $order			=	'ASC';
    public $all				=	[];
    public $members			= 	[];
    public $next_id;
    public $previous_id;
    public $results_count;
    private $_members		= 	[];
    public $relationships	= 	[];
    public $database_connection = 'system_database';
    public $database;
    public const normalizations		= 	[];

    public static function jsonToObject($value){
        return json_decode($value);
    }

    public static function objectToJson($object){
        return json_encode($object);
    }

    public function __get($member)
    {
        if (isset($this->_members[$member]) && array_key_exists($member, self::normalizations) && array_key_exists('get', self::normalizations[$member])) {
            //    echo "Getting {$member}," . PHP_EOL . " normalize is on." . PHP_EOL;
            return  forward_static_call_array([self, self::normalizations[$member]['get']], [$this->_members[$member]]);
        } elseif (isset($this->_members[$member])) {
            //    echo "Getting {$member}," . PHP_EOL . " normalize is off." . PHP_EOL;
            return $this->_members[$member];
        } elseif (isset($this->$member)) {
            //    echo "Getting {$member}," . PHP_EOL . " normalize is off." . PHP_EOL;
            return $this->$member;
        }
        return false;
    }

    public function __set($member, $value){
        if (isset($this->members[$member]) && $this->normalize && array_key_exists($member, self::normalizations) && array_key_exists('set', self::normalizations[$member])) {
            //    echo "Setting {$member}," . PHP_EOL . " normalize is on." . PHP_EOL;
            $this->_members[$member] = forward_static_call_array([self, self::normalizations[$member]['get']], [$value]);
        } elseif (isset($this->members[$member])) {
            //    echo "Setting {$member}," . PHP_EOL . " normalize is off." . PHP_EOL;
            $this->_members[$member] = $value;
        } else{
            $this->$member = $value;;
        }
    }
    //object constructor
    public function __construct($value=null, $by='id', $lookupMembers=true){
        $this->database = ResourceConnection::model()->{$this->database_connection};
        if($lookupMembers === true)
        {
            $this->setMembers();
        }
        if($value !== true)
        {
            return $this->exists($value, $by);
        }
        return $this;
    }
    //standard delete record
    public function delete($id, $limit=1){
        $sql = "
			DELETE FROM {$this->table} WHERE id = ".$this->safedSQLData($id, "int")." LIMIT 1;
			";
        $this->database->query($sql);
        return true;
    }
    //attempts to bring the full object into existence using a value and field name
    public function exists($value, $by='id') {
        $this->all = [];
        if (!isset($this->members[$by]) || intval($this->members[$by]['unique']) === 0){
            return false;
        }
        $sql = "
			SELECT *
			FROM {$this->table}
			WHERE {$by} = ".$this->safedSQLData($value, $this->members[$by]['saveType'])."
			";

        $result = $this->database->query($sql, true);
        if($result){

            foreach($this->members as $member=>$value){
                //$this->$member = $result->$member;
                $this->members[$member]['value'] = $result->$member;
                $this->_members[$member] = $result->$member;
            }

            //$this->id				=	$row->id;
            //$this->parameters		=	$this->getRelationship(0);
            return $this;
        }
        return false;
    }
    //creates a record
    public function create(){
        $this->all = [];
        $sql = "
			INSERT INTO {$this->table}
		 	(`id`)
			VALUES
		 	(0)
			";
        $this->database->query($sql);
        $this->_members['id'] = $this->database->insertId;
        return $this;
    }
    public function saveChangedMembers(){
        if(count($this->all)!=0){return false;}
        if(trim($id) != '' && !$this->exists($id,'id')){return false;}
        if(!$this->id){return false;}
        foreach($this->members as $member=>$value){
            if($value !== $loop_value['value'] && $member != 'id'){
                $this->updateField($value,$member);
            }
        }
    }
    //updates a field of a record
    public function updateField($value, $member){
        $this->all = [];
        $save_type = (isset($this->members[$member]) && isset($this->members[$member]['saveType'])) ? $this->members[$member]['saveType'] : 'quoted';
        switch($save_type){
            case 'int':
                $sql = "
					UPDATE {$this->table}
					SET
					`{$member}` = ".$this->safedSQLData($value, "int")."
					WHERE id = ".$this->safedSQLData($this->_members['id'], "int")."
				";
                break;
            case 'quoted':
            default:
                $sql = "
					UPDATE {$this->table}
					SET
					`{$member}` = ".$this->safedSQLData($value, "text")."
					WHERE id = ".$this->safedSQLData($this->_members['id'], "int")."
				";
                break;
        }
        if($this->database->query($sql)){
            $this->_members[$member] = $value;
            return $this;
        }else{
            return false;
        }
    }
    // establish objects relationship
    function getRelationship($key=0){
        $this->all = [];
        if(!$this->_members['id']){return false;}
        $object = new $this->object_relationships[$key];
        $object->getAll($this->id);
        if(count($object->all) > 0){
            return $object->all;
        }else{
            return [];
        }
    }

    public function getAll($where='', $fields='*', $limit='', $singleArray=false){
        $sql = "SELECT ".$fields." FROM {$this->table} ";
        if($where != '' && !is_array($where)){
            return false;
        }else if(is_array($where)){
            $temp_sql = '';
            for($i=0;$i<count($where);$i++){
                $field = '';
                $type = '';
                $operator = '=';
                $value = '';
                if(isset($this->members[$where[$i]['field']]['saveType'])){
                    $field = $where[$i]['field'];
                    $type = $this->members[$where[$i]['field']]['saveType'];
                    $value = $where[$i]['value'];
                    if(isset($where[$i]['operator'])){
                        $operator = $where[$i]['operator'];
                    }
                    if($temp_sql != ''){
                        $temp_sql .= ' AND ';
                    }
                    $temp_sql .= $this->statementWhereClauseSnippit($field,$type,$operator,$value);
                }
            }
        }
        if($temp_sql != ''){
            $sql .= 'WHERE '.$temp_sql .' ';
        }
        $sql .= "ORDER BY {$this->orderBy} {$this->order}";
        if($limit != ''){
            $sql .= ' LIMIT '.$limit .'';
        }
        $results = $this->database->query($sql);
        $this->all = [];
        if($results){
            if (strpos(',', $fields) === false && $fields != '*' && $singleArray == true) {
                while ($row = $results->fetch_object()) {
                    $this->all[] = $row->$fields;
                }
            } else {
                while ($row = $results->fetch_object()) {
                    $this->all[] = $row;
                }
            }
        }
        return $results ? true : false;
    }

    public function getCount($where='',$fields='*',$singleArray=false,$limit=''){
        $sql = "SELECT COUNT(".$fields.") as results_count FROM {$this->table} ";
        if($where != '' && !is_array($where)){
            return false;
        }else if(is_array($where)){
            $temp_sql = '';
            for($i=0;$i<count($where);$i++){
                $field = '';
                $type = '';
                $operator = '=';
                $value = '';
                if(isset($this->members[$where[$i]['field']]['saveType'])){
                    $field = $where[$i]['field'];
                    $type = $this->members[$where[$i]['field']]['saveType'];
                    $value = $where[$i]['value'];
                    if(isset($where[$i]['operator'])){
                        $operator = $where[$i]['operator'];
                    }
                    if($temp_sql != ''){
                        $temp_sql .= ' AND ';
                    }
                    $temp_sql .= $this->statementWhereClauseSnippit($field,$type,$operator,$value);
                }
            }
        }
        if($temp_sql != ''){
            $sql .= 'WHERE '.$temp_sql .' ';
        }
        $sql .= "ORDER BY {$this->orderBy} {$this->order}";
        if($limit != ''){
            $sql .= ' LIMIT '.$limit .'';
        }
        $results = $this->database->query($sql, true);

        $this->results_count = $results->results_count;

        return $results ? true : false;
    }

    public function getPreviousId(){
        if(!$this->__get('id')){return false;}
        $sql = "SELECT id as previous_id FROM {$this->table} where  AND id < {$this->id} LIMIT 1;";
        $results = $this->database->query($sql, true);
        $this->previous_id = $results->previous_id;

        return $results ? true : false;
    }
    public function getNextId(){
        if(!$this->__get('id')){return false;}
        $sql = "SELECT id as next_id FROM {$this->table} where  AND id > {$this->id} LIMIT 1;";
        $results = $this->database->query($sql, true);
        $this->next_id = $results->next_id;

        return $results ? true : false;
    }
    public function statementWhereClauseSnippit($field,$type,$operator,$value){
        if(strpos(strtolower($this->statementWhereClauseOperator($operator, $type)), 'like') === false){
            $output = '`'.$field.'` '. $this->statementWhereClauseOperator($operator, $type) .' '. $this->safedSQLData($value, $type);
        }else{
            $output = '`'.$field.'` '. $this->statementWhereClauseOperator($operator, $type) .' '. $this->safedSQLData($this->statementWhereClauseOperatorWildcard($operator, $value), $type);
        }
        return $output;
    }
    public function statementWhereClauseOperator($operator,$type){
        switch($operator){
            case '<':
            case '>':
            case '<=':
            case '>=':
                if($type != 'int' || $type != 'float'){
                    return false;
                }else{
                    return $operator;
                }
            case '!=':
            case '=':
                return $operator;
            case '%=%':
            case '=%':
            case '%=':
                return 'LIKE';
            case '%!=%':
            case '!=%':
            case '!%=':
                return 'NOT LIKE';
            default:
                return false;
        }
    }
    public function statementWhereClauseOperatorWildcard($operator,$input){
        switch ($operator){
            case '%=%':
            case '%!=%':
                return '%'.$input.'%';
            case '=%':
            case '!=%':
                return $input.'%';
            case '%=':
            case '%!=':
                return '%'.$input;
            default:
                return $input;
        }
    }
    public function safedSQLData($input,$type,$localsource=false){
        $input = ($localsource) ? $input : ((get_magic_quotes_gpc()) ? $input : $this->database->connection->real_escape_string($input));
        switch (strtolower($type)){
            case 'int':
                $output = ($input != "") ? intval($input) : "NULL";
                break;
            case 'float':
                $output = ($input != "") ? "'" . floatval($input) . "'" : "NULL";
                break;
            default:
                $output = ($input != "") ? "'" . $input . "'" : "NULL";
                break;
        }
        return $output;
    }
    public function setMembers(){
        $this->members = $this->getMembers();
    }
    public function getMembers(){
        $sql = "SHOW COLUMNS FROM {$this->table}";
        $results = $this->database->query($sql);
        $raw_members = [];
        $members = [];
        if($results){
            while($object = $results->fetch_object()){
                $raw_members[$object->Field] = $object;
            }
            $members = [];
            foreach($raw_members as $row){
                $members[$row->Field] = [];
                if(strpos($row->Type, '(') === false){
                    $type = $row->Type;
                    if(strpos($row->Type, 'long') === false){
                        $length=1024;
                    }else{
                        $length=10240000000;
                    }
                }else{
                    list($type, $length) = sscanf(trim(str_replace(['(',')'], ' ', $row->Type)), "%s %d");
                }
                $members[$row->Field]['saveType'] = $this->memberBasicType($type);
                $members[$row->Field]['basictype'] = $this->memberBasicType($type);
                $members[$row->Field]['type'] = $type;
                $members[$row->Field]['length'] = $length;
                $members[$row->Field]['default'] = $row->Default;
                $members[$row->Field]['unique'] = ($row->Key == 'UNI' || $row->Key == 'PRI') ? 1 : 0;
                $members[$row->Field]['blank'] = ($row->Null == 'YES') ? 1 : 0;
                $members[$row->Field]['key'] = (!empty($row->Key)) ? 1 : 0;
                $members[$row->Field]['extra'] = $row->Extra;
            }
        }
        return $members;
    }
    public function memberBasicType($type){
        switch(strtolower($type)){
            case 'tinyint':
            case 'smallint':
            case 'mediumint':
            case 'bigint':
            case 'int':
                return 'int';
            case 'double':
            case 'decimal':
            case 'float':
                return 'float';
            default:
                return 'text';
        }
    }

    public function modelObject(){
        $object = (object) null;
        foreach($this->members as $member=>$value){
            $object->$member = $this->__get($member);
        }
        return $object;
    }
}