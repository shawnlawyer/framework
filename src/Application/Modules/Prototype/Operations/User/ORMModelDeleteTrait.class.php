<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

use Sequode\Model\Module\Registry as ModuleRegistry;

trait ORMModelDeleteTrait {
    
    public static function delete($_model = null){
        
        
        $modeler = static::$modeler;
        
        ($_model == null)
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model)) ;
            
        if(!($_model->id != 1 && $_model->id != 2 && $_model->role_id > 99)){
            return \SQDE_User::model($_model);
        }
        $sequodes_model = self::getSequodesModelOfAllSequencedSequodes($_model);
        foreach($sequodes_model->all as $object){
            $sequodes_model->delete($object->id);
        }
        
        $modeler::model()->delete($_model->id);
        return $modeler::model();
    }
}