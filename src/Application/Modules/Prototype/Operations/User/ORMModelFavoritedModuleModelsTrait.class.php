<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

trait ORMModelFavoritedModuleModelsTrait {
    
    public static function emptySequodeFavorites($_model = null){
        
        ($_model == null)
            ? forward_static_call_array(array(static::$modeler,'model'),array())
            : forward_static_call_array(array(static::$modeler,'model'),array($_model)) ;
            
        static::$modeler::model()->updateField('[]','sequode_favorites');
        
        return static::$modeler::model();
    }
    
    public static function addToSequodeFavorites($sequode_model = null, $_model = null){
        if($sequode_model != null ){ \Sequode\Application\Modules\Sequode\Modeler::model($sequode_model); }
        
        ($_model == null)
            ? forward_static_call_array(array(static::$modeler,'model'),array())
            : forward_static_call_array(array(static::$modeler,'model'),array($_model)) ;
            
		$palette = json_decode($_model->sequode_favorites);
		$palette[] = \Sequode\Application\Modules\Sequode\Modeler::model()->id;
        
        static::$modeler::model()->updateField(json_encode(array_unique($palette)),'sequode_favorites');
        
        return static::$modeler::model();
    }
    
    public static function removeFromSequodeFavorites($sequode_model = null, $_model = null){
        if($sequode_model != null ){ \Sequode\Application\Modules\Sequode\Modeler::model($sequode_model); }
        
        ($_model == null)
            ? forward_static_call_array(array(static::$modeler,'model'),array())
            : forward_static_call_array(array(static::$modeler,'model'),array($_model)) ;
            
		$palette = json_decode(static::$modeler::model()->sequode_favorites);
        $array = array();
		foreach($palette as $value){
			if(intval($value) != \Sequode\Application\Modules\Sequode\Modeler::model()->id){
				$array[] = $value;
			}
		}
        
        static::$modeler::model()->updateField(json_encode($array),'sequode_favorites');
        
        return static::$modeler::model();
    }
    
}