<?php

namespace Sequode\Application\Modules\Sequode\Routes\Collections;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Model\Module\Registry as ModuleRegistry;

class Collections{
    public static $registry_key = 'Sequode';
	public static $merge = true;
	public static $routes = array(
		'sequodes',
		'my_sequodes',
		'sequode_search',
		'sequode_favorites'
	);
	public static $routes_to_methods = array(
		'sequodes' => 'main',
		'my_sequodes' => 'owned',
		'sequode_search' => 'search',
		'sequode_favorites' => 'favorited'
	);
	public static function main($key = null){
        $modeler = ModuleRegistry::model(static::$registry_key)->modeler;
        $_model = new $modeler::$model;
        if($key == null){
            if(\Sequode\Application\Modules\Account\Authority::isSystemOwner()){
                $where = array();
                $_model->getAll($where,'id, process_description_node');
                $nodes = array();
                foreach($_model->all as $object){
                    $nodes[] = '"' . $object->id . '":' . $object->process_description_node;
                }
            }else{
                $nodes = array();
                $where = array();
                $where[] = array('field'=>'owner_id','operator'=>'!=','value'=>\Sequode\Application\Modules\Account\Modeler::model()->id);
                $where[] = array('field'=>'shared','operator'=>'=','value'=>'1');
                $_model->getAll($where,'id, process_description_node');
                foreach($_model->all as $object){
                    $nodes[] = '"' . $object->id . '":' . $object->process_description_node;
                }
                $where = array();
                $where[] = array('field'=>'owner_id','operator'=>'=','value'=>\Sequode\Application\Modules\Account\Modeler::model()->id);
                $_model->getAll($where,'id, process_description_node');
                foreach($_model->all as $object){
                    $nodes[] = '"' . $object->id . '":' . $object->process_description_node;
                }
            }
            echo  '{';
            echo  "\n";
            if(0 < count($nodes)){
                echo  implode(",\n", $nodes);
            }
            
            echo  "\n";
            echo  '}';
            return;
        }elseif(
        $modeler::exists($key,'id')
        && \Sequode\Application\Modules\Account\Authority::canView()
        ){
            echo $modeler::model()->process_description_node;
            return;
        }else{
            echo  '{}';
            return;
        }
	}
	public static function search(){
        $finder = ModuleRegistry::model(static::$registry_key)->finder;
        $collection = 'sequode_search';
        $nodes = array();
        if(SessionStore::is($collection)){
            $_array = $finder::search(SessionStore::get($collection));
            foreach($_array as $_object){
                $nodes[] = '"'.$_object->id.'":{"id":"'.$_object->id.'","n":"'.$_object->name.'"}';
            }
        }
        echo '{'.implode(',', $nodes).'}';
        return;
	}
	public static function owned(){
        $modeler = ModuleRegistry::model(static::$registry_key)->modeler;
        $_model = new $modeler::$model;
        $where = array();
        $where[] = array('field'=>'owner_id','operator'=>'=','value'=>\Sequode\Application\Modules\Account\Modeler::model()->id);
        $_model->getAll($where,'id,name');
        $nodes = array();
        foreach($_model->all as $object){
            $nodes[] = '"'.$object->id.'":{"id":"'.$object->id.'","n":"'.$object->name.'"}';
        }
        echo '{'.implode(',', $nodes).'}';
        return;
	}
	public static function favorited(){
        $modeler = ModuleRegistry::model(static::$registry_key)->modeler;
        $collection = 'sequode_favorites';
        $nodes = array();
        if(!empty(\Sequode\Application\Modules\Account\Modeler::model()->$collection)){
            $_model_ids = json_decode(\Sequode\Application\Modules\Account\Modeler::model()->$collection);
            foreach($_model_ids as $_model_id){
                if($modeler::exists($_model_id,'id')){
                    $nodes[] = '"'. $modeler::model()->id .'":{"id":"'.$modeler::model()->id.'","n":"'.$modeler::model()->name.'"}';
                }
            }
        }
        echo '{'.implode(',', $nodes).'}';
        return;
	}
    /*
	public static function palette(){
        $finder = ModuleRegistry::model(static::$registry_key)->finder;
        if(in_array(SessionStore::get(__FUNCTION__),static::$routes)){
            $method = static::$routes_to_methods[static::$routes];
            self::$method();
            return;
        }
        $nodes = array();
        $collection = __FUNCTION__;
        if(SessionStore::is($collection)){
            $_array = SessionStore::get($collection);
            foreach($_array as $_object){
                $nodes[] = '"'.$_object->id.'":{"id":"'.$_object->id.'","n":"'.$_object->name.'"}';
            }
        }
        echo '{'.implode(',', $nodes).'}';
        return;
	}
    */
	public static function palette(){
        if(SessionStore::get('palette') == 'sequode_search'){
            self::search();
        }elseif(SessionStore::get('palette') == 'sequode_favorites'){
            self::favorited();
        }elseif(SessionStore::is('palette')){
            $sequode_model = new \Sequode\Application\Modules\Sequode\Modeler::$model;
            $sequode_model->exists(SessionStore::get('palette'),'id');
            $sequence = array_unique(json_decode($sequode_model->sequence));
            $nodes = array();
            foreach($sequence as $id){
                $sequode_model->exists($id,'id');
                $nodes[] = '"'.$sequode_model->id.'":{"id":"'.$sequode_model->id.'","n":"'.$sequode_model->name.'"}';
            }
            echo '{'.implode(',', $nodes).'}';
        }else{
            echo '{}';
        }
	}
}