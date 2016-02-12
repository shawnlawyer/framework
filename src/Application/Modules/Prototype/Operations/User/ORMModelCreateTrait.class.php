<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

trait ORMModelCreateTrait {
    
    
    public static function newUser(){
        
        static::$modeler::model()->create(substr(Hashes::uniqueHash(),0,15), Hashes::uniqueHash(), substr(Hashes::uniqueHash(),0,15));
        static::$modeler::exists($modeler::model()->id, 'id');
        static::$modeler::model()->updateField('[]','sequode_favorites');
        static::$modeler::model()->updateField('100','role_id');
        static::$modeler::model()->updateField('33','allowed_sequode_count');
        static::$modeler::model()->updateField('1','active');
        
        return static::$modeler::model();
    }
    
    public static function newGuest(){
        
        static::$modeler::model()->create(substr(Hashes::uniqueHash(),0,15), Hashes::uniqueHash(), substr(Hashes::uniqueHash(),0,15));
        static::$modeler::exists($modeler::model()->id, 'id');
        static::$modeler::model()->updateField('[]','sequode_favorites');
        static::$modeler::model()->updateField('101','role_id');
        static::$modeler::model()->updateField('5','allowed_sequode_count');
        static::$modeler::model()->updateField('1','active');
        
        return static::$modeler::model();
    }
    
}