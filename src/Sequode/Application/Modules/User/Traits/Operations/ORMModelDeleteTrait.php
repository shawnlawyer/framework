<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Model\Module\Registry as ModuleRegistry;

trait ORMModelDeleteTrait {

    public static function delete($_model = null){

        $modeler = static::$modeler;

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $modules = [
            'Sequode',
            'Package',
            'Token'
        ];
        foreach($modules as $module_key){
            $module = ModuleRegistry::module($module_key);
            $operations = $module::model()->operations;
            $model = $operations::getOwnedModels($modeler::model(), 'id');
            foreach($model->all as $object){
                $model->delete($object->id);
            }
        }
        $modeler::model()->delete($modeler::model()->id);
        return $modeler::model();
    }
}