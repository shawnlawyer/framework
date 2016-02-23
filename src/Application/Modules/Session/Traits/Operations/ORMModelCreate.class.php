<?php

namespace Sequode\Application\Modules\Session\Traits\Operations;

use Sequode\Foundation\Hashes;

trait ORMModelCreate {
    
	public static function create($ip_address = null){
        
        $modeler = static::$modeler;
        
        $ip_address = ($ip_address == null)
            ? explode(',',((!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"]))[0]
            : $ip_address ;
        
        $modeler::model()->create();
        $modeler::model()->exists($modeler::model()->id, 'id');
        $modeler::model()->updateField(Hashes::uniqueHash(), 'session_id');
        $modeler::model()->updateField(serialize(array()), 'session_data');
        $modeler::model()->updateField(time(), 'session_start');
        $modeler::model()->updateField($ip_address, 'ip_address');
        $modeler::model()->updateField('anon', 'username');
        
        return $modeler::model();
        
    }
    
}