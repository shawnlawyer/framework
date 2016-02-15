<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

trait ORMModelLoadSignedInTrait {
	
    public static function load(){
    
        $modeler = static::$modeler;
        
        if(\Sequode\Application\Modules\Session\Modeler::isCookieValid() && \Sequode\Application\Modules\Session\Modeler::exists(\Sequode\Application\Modules\Session\Modeler::model()->session_id, 'session_id')){
            $modeler::exists(\Sequode\Application\Modules\Session\Modeler::get('user_id'),'id');
        }
    
    }
    
}