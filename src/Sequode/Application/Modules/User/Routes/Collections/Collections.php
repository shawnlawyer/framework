<?php

namespace Sequode\Application\Modules\User\Routes\Collections;

use Sequode\Application\Modules\Session\Store as SessionStore;

use Sequode\Application\Modules\User\Module;

class Collections{
    
    const Module = Module::class;

	public static $merge = false;

	public static $routes = [
		'user_search',
        'users',
        'guests',
        'admins'
    ];
    const Routes = [
		'user_search',
        'users',
        'guests',
        'admins'
    ];

	public static $routes_to_methods = [
		'user_search' => 'search',
		'users' => 'users',
		'guests' => 'guests',
		'admins' => 'admins',

    ];

    public static function search(){

        extract((static::Module)::variables());

        $collection = 'user_search';

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


    public static function guests(){

        extract((static::Module)::variables());

        $_model = new $modeler::$model;

        $where[] = ['field'=>'role_id','operator'=>'=','value'=>101];

        $_model->getAll($where, 'id, name');

        $nodes = [];

        foreach($_model->all as $_object){

            $nodes[] = '"'.$_object->id.'":{"id":"'.$_object->id.'","n":"'.$_object->name.'"}';

        }

        echo  '{';
        echo  "\n";
        if(0 < count($nodes)){
            echo  implode(",\n", $nodes);
        }

        echo  "\n";
        echo  '}';
        return;

	}

    public static function users(){

        extract((static::Module)::variables());

        $_model = new $modeler::$model;

        $where[] = ['field'=>'role_id','operator'=>'=','value'=>100];

        $_model->getAll($where, 'id, name');

        $nodes = [];

        foreach($_model->all as $_object){

            $nodes[] = '"'.$_object->id.'":{"id":"'.$_object->id.'","n":"'.$_object->name.'"}';

        }

        echo  '{';
        echo  "\n";
        if(0 < count($nodes)){
            echo  implode(",\n", $nodes);
        }

        echo  "\n";
        echo  '}';
        return;

	}

    public static function admins(){

        extract((static::Module)::variables());

        $_model = new $modeler::$model;

        $where[] = ['field'=>'role_id','operator'=>'=','value'=>'0'];

        $_model->getAll($where, 'id, name');

        $nodes = [];

        foreach($_model->all as $_object){

            $nodes[] = '"'.$_object->id.'":{"id":"'.$_object->id.'","n":"'.$_object->name.'"}';

        }

        echo  '{';
        echo  "\n";
        if(0 < count($nodes)){
            echo  implode(",\n", $nodes);
        }

        echo  "\n";
        echo  '}';
        return;

	}

}