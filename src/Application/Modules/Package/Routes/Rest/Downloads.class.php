<?php

namespace Sequode\Application\Modules\Package\Routes\Rest;

class Downloads{
	public static function source($_model_token){
        if(!(
		\Sequode\Application\Modules\Package\Modeler::exists($_model_token,'token')
        && \Sequode\Application\Modules\Sequode\Modeler::exists(\Sequode\Application\Modules\Package\Modeler::model()->sequode_id,'id')
        && (\Sequode\Application\Modules\Account\Authority::isOwner(\Sequode\Application\Modules\Package\Modeler::model()) || \Sequode\Application\Modules\Account\Authority::isSystemOwner())
        && (\Sequode\Application\Modules\Account\Authority::isOwner(\Sequode\Application\Modules\Sequode\Modeler::model()) || \Sequode\Application\Modules\Account\Authority::isSystemOwner())
		)){ return; }
        
        header('Content-Type: text/plain',true);
        header('Content-Disposition: attachment; filename="'.\Sequode\Application\Modules\Package\Modeler::model()->name.'.class.php"');
        echo \Sequode\Application\Modules\Package\Operations::download();
    }
}