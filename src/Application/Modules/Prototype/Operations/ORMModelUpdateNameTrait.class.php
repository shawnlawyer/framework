<?php

namespace Sequode\Application\Modules\Prototype\Operations;

trait ORMModelUpdateNameTrait {
    public static function delete($_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $modeler::model()->delete($modeler::model()->id);
        return $modeler::model();
    }
}