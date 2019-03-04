<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Foundation\Hashes;

trait ORMModelCreateTrait {
    
    
    public static function newUser(){
        
        $modeler = static::$modeler;
        
        $modeler::model()->create(substr(Hashes::uniqueHash(),0,15), Hashes::uniqueHash(), substr(Hashes::uniqueHash(),0,15));
        $modeler::model()->sequode_favorites = [];
        $modeler::model()->role_id = 100;
        $modeler::model()->allowed_sequode_count = 33;
        $modeler::model()->active = 1;
        $modeler::model()->save();

        return $modeler::model();
        
    }
    
    public static function newGuest(){
        
        $modeler = static::$modeler;
        
        $modeler::model()->create(substr(Hashes::uniqueHash(),0,15), Hashes::uniqueHash(), substr(Hashes::uniqueHash(),0,15));
        $modeler::model()->sequode_favorites = [];
        $modeler::model()->role_id = 101;
        $modeler::model()->allowed_sequode_count = 5;
        $modeler::model()->active = 1;
        $modeler::model()->save();
        
        return $modeler::model();
        
    }
    
}