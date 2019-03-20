<?php

namespace Sequode\Application\Modules\Sequode;

use Sequode\Model\Database\SQL\ORM;

class Model extends ORM {

    public $database_connection     =   'sequodes_database';

	public $table 					=	'sequodes';

	public $usage_types				=	[
	    0 => 'code',
        1 => 'sequence',
        2 => 'set'
    ];

	public $coding_types			=	[
	    0 => 'structured',
        1 => 'object_oriented'
    ];

	public function __construct() {
		parent::__construct();
		return true;
	}

    const normalizations = [
        'sequence' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
        'detail' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
        'grid_areas' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
        'input_object' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
        'input_object_detail' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
        'input_object_map' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
        'input_form_object' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
        'output_object' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
        'output_object_detail' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
        'output_object_map' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
        'property_object' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
        'property_object_detail' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
        'property_object_map' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
        'property_form_object' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
        'mine_object' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
        'default_input_object_map' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
        'default_property_object_map' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
        'default_output_object_map' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
        'process_description_node' => [
                'get' => 'jsonToObject',
                'set' => 'objectToJson'
        ],
    ];

    public function create($name = '', $printable_name = '', $usage_type = '', $version = ''){
		$sql = "
			INSERT INTO {$this->table}
		 	(`id`,`name`,`printable_name`,`usage_type`,`version`)
			VALUES
		 	(0
			,".$this->safedSQLData($name, 'text')."
			,".$this->safedSQLData($printable_name, 'text')."
			,".$this->safedSQLData($usage_type, 'text')."
			,".$this->safedSQLData($version, 'text').")
			";
        $this->database->query($sql);
        $this->_members['id'] = $this->database->insertId;
        $this->exists($this->database->insertId, 'id');
        return $this;
	}
        public function deep_sequence($id = null)
    {
        $id = ($id) ?: $this->id;
        $used_ids = [];
        $used_ids[] = $id;
        $model = (new static)->exists($id);

        if($model->usage_type > 0 ){
            $sequence = $model->sequence;
            foreach(array_unique($sequence) as $loop_id){
                if(!in_array($loop_id, $used_ids)){
                    $used_ids[] = $loop_id;
                    $loop_model = (new static)->exists($loop_id);
                    if($loop_model->usage_type > 0){
                        $used_ids = array_unique(array_merge($used_ids, $this->deep_sequence($loop_id)));
                    }
                }
            }
        }
        $new_array = [];
        foreach((array)$used_ids as $value){
            $new_array[] = $value;
        }
        sort($new_array);
        return $new_array;
    }
}