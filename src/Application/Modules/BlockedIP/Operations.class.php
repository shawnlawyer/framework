<?php

namespace Sequode\Application\Modules\BlockedIP;

use Sequode\Model\Module\Registry as ModuleRegistry;

class Operations {
    public static $package = 'BlockedIP'; 
    public static function create($_session_model){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $modeler::model()->create($_session_model->ip_address);
        return $modeler::model();
    }
    public static function delete($_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $modeler::model()->delete($modeler::model()->id);
        return $modeler::model();
    }
}