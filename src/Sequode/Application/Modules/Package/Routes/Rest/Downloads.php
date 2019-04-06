<?php

namespace Sequode\Application\Modules\Package\Routes\Rest;

use Sequode\Application\Modules\Package\Module;
use Sequode\Application\Modules\Sequode\Modeler as SequodeModeler;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;

class Downloads{

    public static $module = Module::class;
    const Module = Module::class;

	public static function source($_model_token){
        extract((static::Module)::variables());

        if(!(
            $modeler::exists($_model_token,'token')
         && SequodeModeler::exists($modeler::model()->sequode_id, 'id')
        && (AccountAuthority::isOwner($modeler::model()) || AccountAuthority::isSystemOwner())
        && (AccountAuthority::isOwner(SequodeModeler::model()) || AccountAuthority::isSystemOwner())
		 )){ return; }
        
        header('Content-Type: text/plain',true);
        //header('Content-Disposition: attachment; filename="' . $modeler::model()->token . '.class.php"');
        echo forward_static_call_array([$operations, __FUNCTION__],[$modeler::model()]);
    }
}