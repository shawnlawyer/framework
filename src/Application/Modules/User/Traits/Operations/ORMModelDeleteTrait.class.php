<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

trait ORMModelDeleteTrait {
    
    public static function delete($_model = null){
        
        $modeler = static::$modeler;
        
        ($_model == null)
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model)) ;
            
               
        $sequodes_model = self::getSequodesModelOfAllSequencedSequodes($_model);
        foreach($sequodes_model->all as $object){
            $sequodes_model->delete($object->id);
        }
        
        $modeler::model()->delete($_model->id);
        return $modeler::model();
    }
}