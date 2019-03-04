<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Application\Modules\Sequode\Modeler as SequodeModeler;

trait ORMModelFavoritedModuleModelsTrait {
    
    public static function emptySequodeFavorites($_model = null){
        
        $modeler = static::$modeler;
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
        $modeler::model()->sequode_favorites = [];
        $modeler::model()>save();
        return $modeler::model();
    }
    
    public static function addToSequodeFavorites($sequode_model = null, $_model = null){

        $modeler = static::$modeler;
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        if($sequode_model != null ){ SequodeModeler::model($sequode_model); }
		$palette = $modeler::model()->sequode_favorites;
		$palette[] = SequodeModeler::model()->id;

        $modeler::model()->sequode_favorites = array_unique($palette);
        $modeler::model()->save();

        return $modeler::model();
    }
    
    public static function removeFromSequodeFavorites($sequode_model = null, $_model = null){

        $modeler = static::$modeler;
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        if($sequode_model != null ){ SequodeModeler::model($sequode_model); }

        $palette = $modeler::model()->sequode_favorites;
        $array = [];
		foreach($palette as $value){
			if(intval($value) != SequodeModeler::model()->id){
				$array[] = $value;
			}
		}

        $modeler::model()->sequode_favorites = $array;
        $modeler::model()->save();

        return $modeler::model();
    }
    
}