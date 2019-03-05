<?php

namespace Sequode\Application\Modules\Sequode;


class Authority {
    
    public static $modeler = Modeler::class;
    
    public static function isSequence($_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return ($_model->usage_type == 1) ? true : false;
    }
    public static function isEmptySequence($_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return (count($_model->sequence) == 0) ? true : false;
    }
    public static function isFullSequence($_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return (!(count($_model->sequence) <= 33)) ? true : false;
    }
    public static function isCode($_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return ($_model->usage_type == 0) ? true : false;
    }
    public static function isCodingTypeFunction($_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return ($_model->coding_type == 1) ? true : false;
    }
    public static function isCodingTypeMethod($_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return ($_model->coding_type == 2) ? true : false;
    }
    public static function hasInputsForm($_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return ($_model->input_object != (object) null) ? true : false;
    }
    public static function hasPropertiesForm($_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return ($_model->propeties_object != (object) null) ? true : false;
    }
    public static function isTenacyDedicated($_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return ($_model->safe == 1) ? false : true;
    }
    public static function isShared($_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return ($_model->shared == 1) ? true : false;
    }
    public static function isPalette($_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return ($_model->palette == 1) ? true : false;
    }
    public static function isPackage($_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return ($_model->package == 1) ? true : false;
    }
}