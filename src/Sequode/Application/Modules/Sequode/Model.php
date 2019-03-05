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

    public const normalizations = [
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
        ]
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
}