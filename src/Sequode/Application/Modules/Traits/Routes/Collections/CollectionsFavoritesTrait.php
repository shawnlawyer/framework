<?php

namespace Sequode\Application\Modules\Traits\Routes\Collections;

use Sequode\Application\Modules\Account\Modeler as AccountModeler;

trait CollectionsFavoritesTrait {

    public static function favorites(){

        extract((static::Module)::variables());

        $collection = static::Method_To_Collection[__FUNCTION__];

        $nodes = [];

        if(!empty(AccountModeler::model()->favorites[$module::Registry_Key])){

            foreach(AccountModeler::model()->favorites[$module::Registry_Key] as $_model_id){

                if($modeler::exists($_model_id,'id')){

                    $nodes[] = '"'. $modeler::model()->id .'":{"id":"'.$modeler::model()->id.'","n":"'.$modeler::model()->name.'"}';

                }

            }

        }

        echo '{'.implode(',', $nodes).'}';

        return;

    }

}