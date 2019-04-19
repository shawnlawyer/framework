<?php

namespace Sequode\Application\Modules\User\Routes\Collections;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Application\Modules\User\Module;
use Sequode\Application\Modules\Traits\Routes\Collections\CollectionsOwnedTrait;
use Sequode\Application\Modules\Traits\Routes\Collections\CollectionsSearchTrait;
use Sequode\Application\Modules\Traits\Routes\Collections\CollectionsFavoritesTrait;

class Collections{
    use CollectionsOwnedTrait,
        CollectionsSearchTrait,
        CollectionsFavoritesTrait;
    
    const Module = Module::class;

	public static $merge = false;

	public static $routes = [
		'user_search',
        'user_users',
        'user_guests',
        'user_admins',
        'user_favorites'
    ];
    const Routes = [
		'user_search',
        'user_users',
        'user_guests',
        'user_admins',
        'user_favorites'
    ];

	public static $routes_to_methods = [
		'user_search' => 'search',
		'user_users' => 'users',
		'user_guests' => 'guests',
		'user_admins' => 'admins',
		'user_favorites' => 'favorites'
    ];

    const Method_To_Collection = [
        'users' => 'user_users',
        'guests' => 'user_guests',
        'admins' => 'user_admins',
        'search' => 'user_search',
        'favorites' => 'user_favorites',
    ];

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