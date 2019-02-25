<?php

namespace Sequode\Application\Modules\Sequode\Routes\Rest;


use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\Sequode\Authority as SequodeAuthority;
use Sequode\Application\Modules\Account\Modeler as AccountModeler;
use Sequode\Application\Modules\Sequode\Modeler as SequodeModeler;
use Sequode\Application\Modules\Sequode\Operations as SequodeOperations;

class Operations{
	public static function surfaceMine($sequode_model_id = 0){
		if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isCode()
        && AccountAuthority::canEdit()
        )){ return; }
		
		try{
			SequodeOperations::buildSequodeCodeNodeOffMineObject();
		}catch(Exception $e){
			exit;
		}
        $object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
	}
	public static function cacheNode($sequode_model_id = 0){
		if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && AccountAuthority::isSystemOwner()
        )){ return; }
        try{
			SequodeOperations::regenerateProcessDescriptionNode();
		}catch(Exception $e){
			exit;
		}
        $object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
	}
	public static function maintenance($sequode_model_id = 0){
        
		if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && AccountAuthority::canEdit()
        )){ return; }
        
		try{
            SequodeOperations::maintenance();
		}catch(Exception $e){
			exit;
		}
        $object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
	}
	public static function newSequence(){
        if(!(
            AccountAuthority::canCreate()
        )){ return; }
        SequodeOperations::newSequence(AccountModeler::model()->id);
        $object = (object) null;
        $object->Success = 1;
		$object->Model_Id = SequodeModeler::model()->id;
        echo json_encode($object);
        exit;
	}
	public static function cloneSequence($sequode_model_id = 0){
        if(!(
        AccountAuthority::canCreate()
        && SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canCopy()
        )){ return; }
		SequodeOperations::makeSequenceCopy(AccountModeler::model()->id);
		$object = (object) null;
        $object->Success = 1;
		$object->Model_Id = SequodeModeler::model()->id;
        echo json_encode($object);
        exit;
	}	
	public static function formatSequence($sequode_model_id = 0){
		if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canEdit()
        )){ return; }
		
        SequodeOperations::makeDefaultSequencedSequode();
		$object = (object) null;
        $object->Success = 1;
		echo json_encode($object);
        exit;
	}
	public static function deleteSequence($sequode_model_id = 0){
		if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canDelete()
        )){ return; }
        SequodeOperations::deleteSequence();
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
	}
	public static function addToSequence($sequode_model_id = 0, $add_sequode_model_id = 0, $position = 0, $position_tuner = null , $grid_modifier = null ){
		if(!(
		SequodeModeler::exists($add_sequode_model_id,'id')
		&& AccountAuthority::canRun()
		&& SequodeModeler::exists($sequode_model_id,'id')
		&& AccountAuthority::canEdit()
        && SequodeAuthority::isSequence()
        && !SequodeAuthority::isFullSequence()
		)){ return; }
		SequodeOperations::addToSequence($add_sequode_model_id, $position, $position_tuner, $grid_modifier);
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
	}
	public static function reorderSequence($sequode_model_id = 0, $from_position = 0, $to_position = 0, $position_tuner = null , $grid_modifier = null ){
		if(!(
		SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
		&& AccountAuthority::canEdit()
		)){ return; }
		SequodeOperations::reorderSequence($from_position, $to_position, $position_tuner, $grid_modifier);
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
	}
	public static function removeFromSequence($sequode_model_id = 0, $position = 0){
		if(!(
		SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
		&& AccountAuthority::canEdit()
		)){ return; }
        SequodeOperations::removeFromSequence($position);
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
	}
	public static function modifyGridAreas($sequode_model_id = 0, $position = 0){
		if(!(
		SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
		&& AccountAuthority::canEdit()
		)){ return; }
        SequodeOperations::modifyGridAreas($position);
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
	}
	public static function emptySequence($sequode_model_id = 0){
		if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canEdit()
        )){ return; }
        SequodeOperations::emptySequence();
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
	}
	public static function moveGridArea($sequode_model_id = 0, $grid_area_key = 0, $x = 0, $y = 0){
		if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canEdit()
        )){ return; }
        SequodeOperations::moveGridArea($grid_area_key, $x, $y);
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
	}
    public static function updateValue($sequode_model_id = 0, $type = false, $map_key = 0, $value = null){
        if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canEdit()
        )){ return; }
        SequodeOperations::updateValue($type, $map_key, $value);
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
    }
    public static function addInternalConnection($sequode_model_id = 0 ,$receiver_type = false, $transmitter_key = 0, $receiver_key = 0){
        if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canEdit()
        )){ return; }
        SequodeOperations::addInternalConnection($receiver_type, $transmitter_key, $receiver_key);
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
    }
    public static function addExternalConnection($sequode_model_id = 0, $connection_type = false, $transmitter_key = 0, $receiver_key = 0){
        if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canEdit()
        )){ return; }
        SequodeOperations::addExternalConnection($connection_type, $transmitter_key, $receiver_key);
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
    }
    public static function removeReceivingConnection($sequode_model_id = 0, $connection_type = false, $restore_key = 0){
        if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canEdit()
        )){ return; }
        SequodeOperations::removeReceivingConnection($connection_type, $restore_key);
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
    }
    public static function updateTenancy($value = 0, $sequode_model_id = 0){
        if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && AccountAuthority::isSystemOwner()
        )){ return; }
        SequodeOperations::updateTenancy($value);
        exit;
    }
    public static function updateSharing($value = 0, $sequode_model_id = 0){
       if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && AccountAuthority::canShare()
        )){ return; }
        SequodeOperations::updateSharing($value);
        exit;
    }
    public static function updateCodeSharing($sequode_model_id = 0, $value = 1){
       if(!(
        AccountAuthority::isSystemOwner()
        && SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isCode()
        && SequodeAuthority::isCodingTypeFunction()
        )){ return; }
        SequodeOperations::updateSharing($value);
        exit;
    }
    public static function updateName($sequode_model_id = 0, $name=''){
        if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && AccountAuthority::canEdit()
        )){ 
            return;
        }
        $object = (object) null;
        $object->Success = false;
        $name = trim(str_replace('-','_',str_replace(' ','_',urldecode($name))));
        if(strlen($name)==0){
            $object->Error = 'Name cannot be empty';
        }elseif(!preg_match("/^([A-Za-z0-9_])*$/i",$name)){
            $object->Error = 'Name can be alphanumeric and contain spaces only';
        }elseif(!AccountAuthority::canRename($name)){
            $object->Error = 'Name already used';
        }
        if(!isset($object->Error)){
            SequodeModeler::exists($sequode_model_id,'id');
            SequodeOperations::updateName($name);
            $object->Success = true;
        }
        echo json_encode($object);
        return;
    }
}