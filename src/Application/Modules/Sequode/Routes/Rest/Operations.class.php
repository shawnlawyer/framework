<?php

namespace Sequode\Application\Modules\Sequode\Routes\Rest;

class Operations{
	public static function surfaceMine($sequode_model_id = 0){
		if(!(
        \Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Sequode\Authority::isCode()
        && \Sequode\Application\Modules\Auth\Authority::canEdit()
        )){ return; }
		
		try{
			\Sequode\Application\Modules\Sequode\Operations::buildSequodeCodeNodeOffMineObject();
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
        \Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Auth\Authority::isSystemOwner()
        )){ return; }
        try{
			\Sequode\Application\Modules\Sequode\Operations::regenerateProcessDescriptionNode();
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
        \Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Auth\Authority::canEdit()
        )){ return; }
        
		try{
            \Sequode\Application\Modules\Sequode\Operations::maintenance();
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
            \Sequode\Application\Modules\Auth\Authority::canCreate()
        )){ return; }
        \Sequode\Application\Modules\Sequode\Operations::newSequence(\Sequode\Application\Modules\Auth\Modeler::model()->id);
        $object = (object) null;
        $object->Success = 1;
		$object->Model_Id = \Sequode\Application\Modules\Sequode\Modeler::model()->id;
        echo json_encode($object);
        exit;
	}
	public static function cloneSequence($sequode_model_id = 0){
        if(!(
        \Sequode\Application\Modules\Auth\Authority::canCreate()
        && \Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Sequode\Authority::isSequence()
        && \Sequode\Application\Modules\Auth\Authority::canCopy()
        )){ return; }
		\Sequode\Application\Modules\Sequode\Operations::makeSequenceCopy(\Sequode\Application\Modules\Auth\Modeler::model()->id);
		$object = (object) null;
        $object->Success = 1;
		$object->Model_Id = \Sequode\Application\Modules\Sequode\Modeler::model()->id;
        echo json_encode($object);
        exit;
	}	
	public static function formatSequence($sequode_model_id = 0){
		if(!(
        \Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Sequode\Authority::isSequence()
        && \Sequode\Application\Modules\Auth\Authority::canEdit()
        )){ return; }
		
        \Sequode\Application\Modules\Sequode\Operations::makeDefaultSequencedSequode();
		$object = (object) null;
        $object->Success = 1;
		echo json_encode($object);
        exit;
	}
	public static function deleteSequence($sequode_model_id = 0){
		if(!(
        \Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Sequode\Authority::isSequence()
        && \Sequode\Application\Modules\Auth\Authority::canDelete()
        )){ return; }
        \Sequode\Application\Modules\Sequode\Operations::deleteSequence();
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
	}
	public static function addToSequence($sequode_model_id = 0, $add_sequode_model_id = 0, $position = 0, $position_tuner = null , $grid_modifier = null ){
		if(!(
		\Sequode\Application\Modules\Sequode\Modeler::exists($add_sequode_model_id,'id')
		&& \Sequode\Application\Modules\Auth\Authority::canRun()
		&& \Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
		&& \Sequode\Application\Modules\Auth\Authority::canEdit()
        && \Sequode\Application\Modules\Sequode\Authority::isSequence()
        && !\Sequode\Application\Modules\Sequode\Authority::isFullSequence()
		)){ return; }
		\Sequode\Application\Modules\Sequode\Operations::addToSequence($add_sequode_model_id, $position, $position_tuner, $grid_modifier);
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
	}
	public static function reorderSequence($sequode_model_id = 0, $from_position = 0, $to_position = 0, $position_tuner = null , $grid_modifier = null ){
		if(!(
		\Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Sequode\Authority::isSequence()
		&& \Sequode\Application\Modules\Auth\Authority::canEdit()
		)){ return; }
		\Sequode\Application\Modules\Sequode\Operations::reorderSequence($from_position, $to_position, $position_tuner, $grid_modifier);
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
	}
	public static function removeFromSequence($sequode_model_id = 0, $position = 0){
		if(!(
		\Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Sequode\Authority::isSequence()
		&& \Sequode\Application\Modules\Auth\Authority::canEdit()
		)){ return; }
        \Sequode\Application\Modules\Sequode\Operations::removeFromSequence($position);
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
	}
	public static function modifyGridAreas($sequode_model_id = 0, $position = 0){
		if(!(
		\Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Sequode\Authority::isSequence()
		&& \Sequode\Application\Modules\Auth\Authority::canEdit()
		)){ return; }
        \Sequode\Application\Modules\Sequode\Operations::modifyGridAreas($position);
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
	}
	public static function emptySequence($sequode_model_id = 0){
		if(!(
        \Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Sequode\Authority::isSequence()
        && \Sequode\Application\Modules\Auth\Authority::canEdit()
        )){ return; }
        \Sequode\Application\Modules\Sequode\Operations::emptySequence();
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
	}
	public static function moveGridArea($sequode_model_id = 0, $grid_area_key = 0, $x = 0, $y = 0){
		if(!(
        \Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Sequode\Authority::isSequence()
        && \Sequode\Application\Modules\Auth\Authority::canEdit()
        )){ return; }
        \Sequode\Application\Modules\Sequode\Operations::moveGridArea($grid_area_key, $x, $y);
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
	}
    public static function updateValue($sequode_model_id = 0, $type = false, $map_key = 0, $value = null){
        if(!(
        \Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Sequode\Authority::isSequence()
        && \Sequode\Application\Modules\Auth\Authority::canEdit()
        )){ return; }
        \Sequode\Application\Modules\Sequode\Operations::updateValue($type, $map_key, $value);
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
    }
    public static function addInternalConnection($sequode_model_id = 0 ,$receiver_type = false, $transmitter_key = 0, $receiver_key = 0){
        if(!(
        \Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Sequode\Authority::isSequence()
        && \Sequode\Application\Modules\Auth\Authority::canEdit()
        )){ return; }
        \Sequode\Application\Modules\Sequode\Operations::addInternalConnection($receiver_type, $transmitter_key, $receiver_key);
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
    }
    public static function addExternalConnection($sequode_model_id = 0, $connection_type = false, $transmitter_key = 0, $receiver_key = 0){
        if(!(
        \Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Sequode\Authority::isSequence()
        && \Sequode\Application\Modules\Auth\Authority::canEdit()
        )){ return; }
        \Sequode\Application\Modules\Sequode\Operations::addExternalConnection($connection_type, $transmitter_key, $receiver_key);
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
    }
    public static function removeReceivingConnection($sequode_model_id = 0, $connection_type = false, $restore_key = 0){
        if(!(
        \Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Sequode\Authority::isSequence()
        && \Sequode\Application\Modules\Auth\Authority::canEdit()
        )){ return; }
        \Sequode\Application\Modules\Sequode\Operations::removeReceivingConnection($connection_type, $restore_key);
		$object = (object) null;
        $object->Success = 1;
        echo json_encode($object);
        exit;
    }
    public static function updateTenancy($value = 0, $sequode_model_id = 0){
        if(!(
        \Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Auth\Authority::isSystemOwner()
        )){ return; }
        \Sequode\Application\Modules\Sequode\Operations::updateTenancy($value);
        exit;
    }
    public static function updateSharing($value = 0, $sequode_model_id = 0){
       if(!(
        \Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Auth\Authority::canShare()
        )){ return; }
        \Sequode\Application\Modules\Sequode\Operations::updateSharing($value);
        exit;
    }
    public static function updateCodeSharing($sequode_model_id = 0, $value = 1){
       if(!(
        \Sequode\Application\Modules\Auth\Authority::isSystemOwner()
        && \Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Sequode\Authority::isCode()
        && \Sequode\Application\Modules\Sequode\Authority::isCodingTypeFunction()
        )){ return; }
        \Sequode\Application\Modules\Sequode\Operations::updateSharing($value);
        exit;
    }
    public static function updateName($sequode_model_id = 0, $name=''){
        if(!(
        \Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id')
        && \Sequode\Application\Modules\Auth\Authority::canEdit()
        )){ 
            return;
        }
        $object = (object) null;
        $object->Success = false;
        $name = trim(str_replace('-','_',str_replace(' ','_',urldecode($name))));
        if(strlen($name)==0){
            $object->Error = 'Name cannot be empty';
        }elseif(!eregi("^([A-Za-z0-9_])*$",$name)){
            $object->Error = 'Name can be alphanumeric and contain spaces only';
        }elseif(!\Sequode\Application\Modules\Auth\Authority::canRename($name)){
            $object->Error = 'Name already used';
        }
        if(!isset($object->Error)){
            \Sequode\Application\Modules\Sequode\Modeler::exists($sequode_model_id,'id');
            \Sequode\Application\Modules\Sequode\Operations::updateName($name);
            $object->Success = true;
        }
        echo json_encode($object);
        return;
    }
}