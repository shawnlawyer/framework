<?php

namespace Sequode\Application\Modules\Traits\Routes\Collections;

use Sequode\Application\Modules\Account\Modeler as AccountModeler;
use Sequode\Application\Modules\Session\Store as SessionStore;

trait CollectionsOwnedTrait {

    public static function owned(){

        extract((static::Module)::variables());

        $_model = new $modeler::$model;

        $where = [];

        $where[] = ['field'=>'owner_id','operator'=>'=','value'=> AccountModeler::model()->id];

        $_model->getAll($where, 'id,name');

        $nodes = [];

        foreach($_model->all as $object){

            $nodes[] = '"'.$object->id.'":{"id":"'.$object->id.'","n":"'.$object->name.'"}';

        }

        echo '{'.implode(',', $nodes).'}';

        return;

	}

}