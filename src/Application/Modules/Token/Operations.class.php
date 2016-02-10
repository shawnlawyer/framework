<?php

namespace Sequode\Application\Modules\Token;

use Sequode\Model\Module\Registry as ModuleRegistry;

class Operations {
    
    use Sequode\Application\Modules\Prototype\Operations\ORMModelDeleteTrait;
    
    public static $package = 'Token';
	//public static function uniqueHash($prefix=''){
    //    return $prefix.openssl_digest(uniqid(rand(), true).microtime(), 'sha512');
	//}
	public static function uniqueHash($prefix='SQDE'){
		return $prefix.sha1(microtime().uniqid(rand(), true));
	}
    public static function getModel($value = null, $by = null, $owner_id = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $_model = new $modeler::$model;
        switch($by){
            case 'id':
            case 'name':
                break;
            default:
                $by = 'id';
                break;
        }
        if($value != null){
            $where[] = array('field'=>$by,'operator'=>'=','value'=>$value);
        }
        if($owner_id != null){
            $where[] = array('field'=>'owner_id','operator'=>'=','value'=>$owner_id);
        }
        $_model->getAll($where,'id',false,1);
        $id = false;
        foreach($_model->all as $key => $object){
            $id = $object->id;
			break;
        }
        if($id != false){
            $modeler::exists($id,'id');
            return $modeler::model();
        }
        return false;   
	}
    public static function updateName($name, $_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $modeler::model()->updateField(str_replace(" ","_",$name),'name');
        return $modeler::model();
    }
}