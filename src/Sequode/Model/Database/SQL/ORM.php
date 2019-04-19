<?php
namespace Sequode\Model\Database\SQL;

use Sequode\Model\Database\ResourceConnection;

class ORM {

    const Table 			    =	'';
    const Database_Connection   =   'system_database';
    const Order_By			    =	'id';
    const Order			        =	'ASC';
    const Normalizations	    = 	[];

    public $all				    =	[];
    public $members			    = 	[];
    public $_members		    = 	[];
    public $results_count;
    public $database;

    public static function jsonToArray($value){

        return ($value !== null) ? json_decode($value, true) : (object) [];

    }

    public static function jsonToObject($value){

        return ($value !== null) ? json_decode($value) : (object) [];

    }

    public static function objectToJson($object){

        return json_encode($object);

    }

    public static function serializedToObject($value){

        return ($value !== null) ? unserialize($value) : (object) [];

    }

    public static function objectToSerialized($object){

        return serialize($object);

    }

    public function __get($member)
    {
        if (isset($this->_members[$member]) && array_key_exists($member, static::Normalizations) && array_key_exists('get', static::Normalizations[$member])){

            return forward_static_call_array([static::class, static::Normalizations[$member]['get']], [$this->_members[$member]]);

        } elseif (isset($this->_members[$member])){

            return $this->_members[$member];

        } elseif (in_array($member, get_class_methods(static::class))){

            return $this->{ $member}();

        } elseif (isset($this->$member)){

            return $this->$member;

        }

        return false;

    }

    public function __set($member, $value){

        if (isset($this->members[$member]) && array_key_exists($member, static::Normalizations) && array_key_exists('set', static::Normalizations[$member])){

            $this->_members[$member] = forward_static_call_array([static::class, static::Normalizations[$member]['set']], [$value]);

        } elseif (isset($this->members[$member])) {

            $this->_members[$member] = $value;

        } else{

            $this->$member = $value;

        }

    }

    // constructor
    public function __construct($value=null, $by='id'){

        $this->members = static::members();

        if($value !== null){

            return $this->exists($value, $by);

        }

        return $this;

    }

    // delete record
    public function delete($id=null, $limit=1) {

        if($id === null && !$this->_members['id']){

            return false;

        }elseif($id === null){

            $id = $this->_members['id'];

        }

        $sql = "
			DELETE FROM ". static::Table ." WHERE id = ".static::escapeValue($id, "int")." LIMIT 1;
		";

        static::database()->query($sql);

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
			FROM ". static::Table ."
			WHERE {$by} = ".static::escapeValue($value, $this->members[$by]['basic_type'])."
			";

        $result = static::database()->query($sql, true);

        if($result){

            foreach($this->members as $member=>$value){

                $this->members[$member]['value'] = $result->$member;
                $this->_members[$member] = $result->$member;

            }

            return $this;

        }

        return false;

    }

    //creates a record
    public static function create($data = []){

        $members = static::members();

        $record = (array_key_exists('id', $members)) ? ['id' => 0] : [];

        foreach ($data as $member => $value){

            if(array_key_exists($member, $members)){

                if(array_key_exists($member, static::Normalizations) && array_key_exists('set', static::Normalizations[$member])){

                    $value = forward_static_call_array([static::class, static::Normalizations[$member]['set']], [$value]);

                }

                $record[$member] = static::escapeValue($value, $members[$member]['basic_type']);

            }

        }

        $sql = "
			INSERT INTO ". static::Table ."
		 	(`" . implode("`,`", array_keys($record)) . "`)
			VALUES
			(" . implode(",", $record) . ")
		 	";

        static::database()->query($sql);

        if(array_key_exists('id', $members)){

            return new static(static::database()->insertId, 'id');

        }

    }

    public function update($data = [], $normalize = true){

        $members = static::members();

        if(!isset($this->_members['id'])){

            return false;

        }

        $record = [];

        foreach ($data as $member => $value){

            if(array_key_exists($member, $members)){

                if($normalize === true && array_key_exists($member, static::Normalizations) && array_key_exists('set', static::Normalizations[$member])){

                    $value = forward_static_call_array([static::class, static::Normalizations[$member]['set']], [$value]);

                }

                $record[$member] = $value;

            }

        }

        $updates = [];

        foreach($record as $member => $value){

            if($this->members[$member]['value'] !== $this->_members[$member]){

                $updates[] = static::setValueStatement(static::escapeValue($value, $members[$member]['basic_type']), $member);

                $this->members[$member]['value'] = $value;

                $this->_members[$member] = $value;

            }

        }

        if($updates === []){

            return $this;

        }

        $sql = "UPDATE ". static::Table ." SET ". PHP_EOL . implode(", " .PHP_EOL, $updates) . " WHERE `id` = ".static::escapeValue($this->_members['id'], "int");

        static::database()->query($sql);

        return $this;

    }

    public function save() {

        $this->all = [];

        if(!isset($this->_members['id'])){

            return false;

        }

        $data = [];

        foreach($this->members as $member=>$value){

            if($this->members[$member]['value'] !== $this->_members[$member]){

                $data[$member] = $this->_members[$member];

            }

        }

        $this->update($data, false);

        return $this;

    }

    public function getAll($where='', $fields='*', $limit='', $singleArray=false){

        $sql = "SELECT ".$fields." FROM ". static::Table ." ";

        if($where != '' && !is_array($where)){

            return false;

        }else if(is_array($where)){

            $temp_sql = '';

            for($i=0;$i<count($where);$i++){

                $field = '';
                $type = '';
                $operator = '=';
                $value = '';

                if(isset($this->members[$where[$i]['field']]['save_type'])){

                    $field = $where[$i]['field'];
                    $type = $this->members[$where[$i]['field']]['save_type'];
                    $value = $where[$i]['value'];

                    if(isset($where[$i]['operator'])){

                        $operator = $where[$i]['operator'];

                    }

                    if($temp_sql != ''){

                        $temp_sql .= ' AND ';

                    }

                    $temp_sql .= static::whereClause($field,$type,$operator,$value);
                }


            }

        }

        if($temp_sql != ''){

            $sql .= 'WHERE '.$temp_sql .' ';

        }

        $sql .= "ORDER BY ".static::Order_By." ".static::Order." ";

        if($limit != ''){

            $sql .= ' LIMIT '.$limit .'';

        }

        $results = static::database()->query($sql);
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

    public function getCount($where='', $fields='*', $singleArray=false, $limit=''){

        $sql = "SELECT COUNT(".$fields.") as results_count FROM ". static::Table ." ";

        if($where != '' && !is_array($where)){

            return false;

        }elseif(is_array($where)){

            $temp_sql = '';
            for($i=0;$i<count($where);$i++){

                $field = '';
                $type = '';
                $operator = '=';
                $value = '';
                if(isset($this->members[$where[$i]['field']]['save_type'])){

                    $field = $where[$i]['field'];
                    $type = $this->members[$where[$i]['field']]['save_type'];
                    $value = $where[$i]['value'];

                    if(isset($where[$i]['operator'])){

                        $operator = $where[$i]['operator'];

                    }

                    if($temp_sql != ''){

                        $temp_sql .= ' AND ';

                    }
                    $temp_sql .= static::whereClause($field,$type,$operator,$value);
                }
            }
        }

        if($temp_sql != ''){
            $sql .= 'WHERE '.$temp_sql .' ';
        }

        $sql .= "ORDER BY ". static::Order_By ." ". static::Order ." ";
        if($limit != ''){
            $sql .= ' LIMIT '.$limit .'';
        }

        $results = static::database()->query($sql, true);

        $this->results_count = $results->results_count;

        return $results ? true : false;

    }

    public function setValueStatement($value, $member) {

        return "`{$member}` = {$value}";

    }

    public static function whereClause($field, $type, $operator, $value){

        $where_operator = static::whereClauseOperator($operator, $type);

        $where_value = (strpos(strtolower($where_operator), 'like') === false)
            ? static::escapeValue($value, $type)
            : static::escapeValue(static::whereClauseValue($operator, $value), $type);

        return "`{$field}` {$where_operator} {$where_value}";

    }

    public static function whereClauseOperator($operator, $type){

        switch($operator){

            case '<':
            case '>':
            case '<=':
            case '>=':
                return ($type === 'int' || $type === 'float')
                    ? $operator
                    : false
                    ;

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

    public static function whereClauseValue($operator, $value){

        switch ($operator){

            case '%=%':
            case '%!=%':
                return '%' . $value . '%';

            case '=%':
            case '!=%':
                return $value . '%';

            case '%=':
            case '%!=':
                return '%' . $value;

            default:
                return $value;

        }

    }

    public static function escapeValue($input, $type){

        switch ($type){
            case 'int':
                return (!empty($input)) ? "'".intval($input)."'" : "'0'";
                break;
            case 'float':
                return (!empty($input)) ? "'".floatval($input)."'" : "'0.0'";
                break;
            default:
                return (!empty($input)) ? "'" . ((get_magic_quotes_gpc()) ? $input : static::database()->connection->real_escape_string($input)) . "'" : "'NULL'";
                break;
        }

    }

    public static function members($refresh = false){

        static $members;

        if(empty($members) || $refresh === true){
            $sql = "SHOW COLUMNS FROM ". static::Table ." ";

            $results = static::database()->query($sql);

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

                    $members[$row->Field]['save_type'] = static::memberBasicType($type);
                    $members[$row->Field]['basic_type'] = static::memberBasicType($type);
                    $members[$row->Field]['type'] = $type;
                    $members[$row->Field]['length'] = $length;
                    $members[$row->Field]['default'] = $row->Default;
                    $members[$row->Field]['unique'] = ($row->Key == 'UNI' || $row->Key == 'PRI') ? 1 : 0;
                    $members[$row->Field]['blank'] = ($row->Null == 'YES') ? 1 : 0;
                    $members[$row->Field]['key'] = (!empty($row->Key)) ? 1 : 0;
                    $members[$row->Field]['extra'] = $row->Extra;

                }

            }

        }

        return $members;

    }

    public static function memberBasicType($type){

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

    public static function database(){

        return ResourceConnection::model()->{static::Database_Connection};

    }
}