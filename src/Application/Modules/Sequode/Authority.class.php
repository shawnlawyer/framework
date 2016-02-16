<?php

namespace Sequode\Application\Modules\Sequode;

class Authority {
    
    public static $modeler = Modeler::class;
    
    public static function isSequence($sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return ($sequode_model->usage_type == 1) ? true : false;
    }
    public static function isEmptySequence($sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return (count(json_decode($sequode_model->sequence)) == 0) ? true : false;
    }
    public static function isFullSequence($sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return (!(count(json_decode($sequode_model->sequence)) <= 33)) ? true : false;
    }
    public static function isCode($sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return ($sequode_model->usage_type == 0) ? true : false;
    }
    public static function isCodingTypeFunction($sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return ($sequode_model->coding_type == 1) ? true : false;
    }
    public static function isCodingTypeMethod($sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return ($sequode_model->coding_type == 2) ? true : false;
    }
    public static function hasInputsForm($sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return ($sequode_model->input_object != '{}') ? true : false;
    }
    public static function hasPropertiesForm($sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return ($sequode_model->propeties_object != '{}') ? true : false;
    }
    public static function isTenacyDedicated($sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return ($sequode_model->safe == 1) ? false : true;
    }
    public static function isShared($sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return ($sequode_model->shared == 1) ? true : false;
    }
    public static function isPalette($sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return ($sequode_model->palette == 1) ? true : false;
    }
    public static function isPackage($sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return ($sequode_model->package == 1) ? true : false;
    }
}