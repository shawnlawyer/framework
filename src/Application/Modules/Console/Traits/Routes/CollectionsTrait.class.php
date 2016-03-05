<?php

namespace Sequode\Application\Modules\Console\Traits\Routes;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Model\Application\Routes as ApplicationRoutes;

trait CollectionsTrait {
	
	public static function collections($collection='collections', $key = null){
        
        $module = static::$module;
        
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
        
        $modules = ModuleRegistry::models();
        
        foreach($modules as $module){
            
            if(!empty($module->collections)){
                
                if(isset($collection) && in_array($collection, ApplicationRoutes::routes($module->collections))){
                    
                    forward_static_call_array(array($module->collections, ApplicationRoutes::route($module->collections, $collection)), ($key != null) ? array($key) : array());
					return;
                    
				}
                
            }
            
        }
        
        return;
        
	}
    
}