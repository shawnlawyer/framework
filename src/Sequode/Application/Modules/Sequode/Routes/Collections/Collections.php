<?php

namespace Sequode\Application\Modules\Sequode\Routes\Collections;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Application\Modules\Sequode\Module;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\Account\Modeler as AccountModeler;

class Collections{
    public static $module = Module::class;
	public static $merge = false;
	public static $routes = [
		'sequodes',
		'my_sequodes',
		'sequode_search',
		'sequode_favorites',
		'palette',
    ];
	public static $routes_to_methods = [
		'sequodes' => 'main',
		'my_sequodes' => 'owned',
		'sequode_search' => 'search',
		'sequode_favorites' => 'favorited',
		'palette' => 'palette',
    ];
	public static function main($key = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        $_model = new $modeler::$model;
        
        if($key == null){
            
            if(AccountAuthority::isSystemOwner()){
                
                $where = [];
                $_model->getAll($where,'id, process_description_node');
                $nodes = [];
                foreach($_model->all as $object){
                    $nodes[] = '"' . $object->id . '":' . $object->process_description_node;
                }
                
            }else{
                
                $nodes = [];
                $where = [];
                $where[] = ['field'=>'owner_id','operator'=>'!=','value'=>AccountModeler::model()->id];
                $where[] = ['field'=>'shared','operator'=>'=','value'=>'1'];
                $_model->getAll($where,'id, process_description_node');
                foreach($_model->all as $object){
                    $nodes[] = '"' . $object->id . '":' . $object->process_description_node;
                }
                $where = [];
                $where[] = ['field'=>'owner_id','operator'=>'=','value'=>AccountModeler::model()->id];
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
        && AccountAuthority::canView($modeler::model())
        ){
            echo json_encode($modeler::model()->process_description_node);
            return;
            
        }else{
            
            echo  '{}';
            return;
            
        }
	}
	public static function search(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        $finder = $module::model()->finder;
        $collection = 'sequode_search';
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
	public static function owned(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        $_model = new $modeler::$model;
        $where = [];
        $where[] = ['field'=>'owner_id','operator'=>'=','value'=>AccountModeler::model()->id];
        $_model->getAll($where,'id,name');
        $nodes = [];
        foreach($_model->all as $object){
            $nodes[] = '"'.$object->id.'":{"id":"'.$object->id.'","n":"'.$object->name.'"}';
        }
        echo '{'.implode(',', $nodes).'}';
        
        return;
        
	}
	public static function favorited(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;

        $collection = 'sequode_favorites';
        $nodes = [];
        if(!empty(AccountModeler::model()->$collection)){
            $_model_ids = AccountModeler::model()->$collection;
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
        
        $module = static::$module;
        $finder = $module::model()->finder;
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
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        if(SessionStore::get('palette') == 'sequode_search'){
            self::search();
        }elseif(SessionStore::get('palette') == 'sequode_favorites'){
            self::favorited();
        }elseif(SessionStore::is('palette')){
            $sequode_model = new $modeler::$model;
            $sequode_model->exists(SessionStore::get('palette'),'id');
            $sequence = array_unique($sequode_model->sequence);
            $nodes = [];
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