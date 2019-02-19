<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Application\Modules\Sequode\Modeler as SequodeModeler;

trait ORMModelFavoritedModuleModelsTrait {
    
    public static function emptySequodeFavorites($_model = null){
        
        $modeler = static::$modeler;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        $modeler::model()->updateField('[]','sequode_favorites');
        return $modeler::model();
    }
    
    public static function addToSequodeFavorites($sequode_model = null, $_model = null){
        
        $modeler = static::$modeler;
        
        if($sequode_model != null ){ SequodeModeler::model($sequode_model); }
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
		$palette = json_decode($modeler::model()->sequode_favorites);
		$palette[] = SequodeModeler::model()->id;
        
        $modeler::model()->updateField(json_encode(array_unique($palette)),'sequode_favorites');
        
        return $modeler::model();
    }
    
    public static function removeFromSequodeFavorites($sequode_model = null, $_model = null){
        
        $modeler = static::$modeler;
        
        if($sequode_model != null ){ SequodeModeler::model($sequode_model); }
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
		$palette = json_decode($modeler::model()->sequode_favorites);
        $array = array();
		foreach($palette as $value){
			if(intval($value) != SequodeModeler::model()->id){
				$array[] = $value;
			}
		}
        
        $modeler::model()->updateField(json_encode($array),'sequode_favorites');
        
        return $modeler::model();
    }
    
}