<?php

namespace Sequode\Application\Modules\Console\Traits\Routes;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Model\Application\Module\Routeables as Routeables;

trait CollectionsTrait {
	
	public static function collections($collection='collections', $key = null){

        extract((static::Module)::variables());
        
        if ($collection == 'collections' && isset($module::model()->assets->boot_collections)){
            
            $collections = $module::model()->assets->boot_collections;
            echo '{';
            echo  "\n";
            foreach($collections as $loop_key => $collection){
                if($loop_key != 0){
                    echo ",\n";
                }
                echo '"'.$collection.'":';
                echo self::collections($collection);
            }
            echo  "\n";
            echo '}';
            
            return;
            
        }
        
        $modules = ModuleRegistry::modules();
        
        foreach($modules as $module){
            
            if(!empty($module::model()->collections)){
                
                if(isset($collection) && in_array($collection, Routeables::routes($module::model()->collections))){

                    forward_static_call_array([$module::model()->collections, Routeables::route($module::model()->collections, $collection)], ($key != null) ? [$key] : []);
					return;
                    
				}
                
            }
            
        }
        
        return;
        
	}
    
}