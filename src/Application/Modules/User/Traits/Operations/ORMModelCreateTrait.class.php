<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Foundation\Hashes;

trait ORMModelCreateTrait {
    
    
    public static function newUser(){
        
        $modeler = static::$modeler;
        
        $modeler::model()->create(substr(Hashes::uniqueHash(),0,15), Hashes::uniqueHash(), substr(Hashes::uniqueHash(),0,15));
        $modeler::exists($modeler::model()->id, 'id');
        $modeler::model()->updateField('[]','sequode_favorites');
        $modeler::model()->updateField('100','role_id');
        $modeler::model()->updateField('33','allowed_sequode_count');
        $modeler::model()->updateField('1','active');
        
        return static::$modeler::model();
    }
    
    public static function newGuest(){
        
        $modeler = static::$modeler;
        
        $modeler::model()->create(substr(Hashes::uniqueHash(),0,15), Hashes::uniqueHash(), substr(Hashes::uniqueHash(),0,15));
        $modeler::exists($modeler::model()->id, 'id');
        $modeler::model()->updateField('[]','sequode_favorites');
        $modeler::model()->updateField('101','role_id');
        $modeler::model()->updateField('5','allowed_sequode_count');
        $modeler::model()->updateField('1','active');
        
        return static::$modeler::model();
    }
    
}