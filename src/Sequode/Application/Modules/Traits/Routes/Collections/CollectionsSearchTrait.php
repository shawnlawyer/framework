<?php

namespace Sequode\Application\Modules\Traits\Routes\Collections;

use Sequode\Application\Modules\Session\Store as SessionStore;

trait CollectionsSearchTrait {


    public static function search(){

        extract((static::Module)::variables());

        $collection = static::Method_To_Collection[__FUNCTION__];

        $nodes = [];

        if(SessionStore::is($collection)){

            $_array = $finder::search(SessionStore::get($collection));

            foreach($_array as $_object){

                $nodes[] = '"'.$_object->id.'":{"id":"'.$_object->id.'","n":"'.$_object->name.'"}';

            }

        }

        echo '{'.implode(',', $nodes).'}';

        return;

    }

}