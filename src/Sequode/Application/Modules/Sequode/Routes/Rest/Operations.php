<?php

namespace Sequode\Application\Modules\Sequode\Routes\Rest;


use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\Sequode\Authority as SequodeAuthority;
use Sequode\Application\Modules\Account\Modeler as AccountModeler;
use Sequode\Application\Modules\Sequode\Modeler as SequodeModeler;
use Sequode\Application\Modules\Sequode\Operations as SequodeOperations;
use Exception;
class Operations{
	public static function surfaceMine($sequode_model_id = 0){
		if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isCode()
        && AccountAuthority::canEdit(SequodeModeler::model())
        )){ return; }
		
		try{
			SequodeOperations::buildSequodeCodeNodeOffMineObject();
		}catch(Exception $e){
			exit;
		}
        echo json_encode((object) ["Success" => 1]);
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
        echo json_encode((object) ["Success" => 1]);
        exit;
	}
	public static function maintenance($sequode_model_id = 0){
        
		if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && AccountAuthority::canEdit(SequodeModeler::model())
        )){ return; }
        
		try{
            SequodeOperations::maintenance();
		}catch(Exception $e){
			exit;
		}
        echo json_encode((object) ["Success" => 1]);
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
        && AccountAuthority::canCopy(SequodeModeler::model())
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
        && AccountAuthority::canEdit(SequodeModeler::model())
        )){ return; }
		
        SequodeOperations::makeDefaultSequencedSequode();

        echo json_encode((object) ["Success" => 1]);
        exit;
	}
	public static function deleteSequence($sequode_model_id = 0){
		if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canDelete(SequodeModeler::model())
        )){ return; }
        SequodeOperations::deleteSequence();

        echo json_encode((object) ["Success" => 1]);
        exit;
	}
	public static function addToSequence($sequode_model_id = 0, $add_sequode_model_id = 0, $position = 0, $position_tuner = null , $grid_modifier = null ){
		if(!(
		SequodeModeler::exists($add_sequode_model_id,'id')
		&& AccountAuthority::canRun(SequodeModeler::model())
		&& SequodeModeler::exists($sequode_model_id,'id')
		&& AccountAuthority::canEdit(SequodeModeler::model())
        && SequodeAuthority::isSequence()
        && !SequodeAuthority::isFullSequence()
		)){ return; }
		SequodeOperations::addToSequence($add_sequode_model_id, $position, $position_tuner, $grid_modifier);

        echo json_encode((object) ["Success" => 1]);
        exit;
	}
	public static function reorderSequence($sequode_model_id = 0, $from_position = 0, $to_position = 0, $position_tuner = null , $grid_modifier = null ){
		if(!(
		SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
		&& AccountAuthority::canEdit(SequodeModeler::model())
		)){ return; }
		SequodeOperations::reorderSequence($from_position, $to_position, $position_tuner, $grid_modifier);

        echo json_encode((object) ["Success" => 1]);
        exit;
	}
	public static function removeFromSequence($sequode_model_id = 0, $position = 0){
		if(!(
		SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
		&& AccountAuthority::canEdit(SequodeModeler::model())
		)){ return; }
        SequodeOperations::removeFromSequence($position);

        echo json_encode((object) ["Success" => 1]);
        exit;
	}
	public static function modifyGridAreas($sequode_model_id = 0, $position = 0){
		if(!(
		SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
		&& AccountAuthority::canEdit(SequodeModeler::model())
		)){ return; }
        SequodeOperations::modifyGridAreas($position);

        echo json_encode((object) ["Success" => 1]);
        exit;
	}
	public static function emptySequence($sequode_model_id = 0){
		if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canEdit(SequodeModeler::model())
        )){ return; }
        SequodeOperations::emptySequence();

        echo json_encode((object) ["Success" => 1]);
        exit;
	}
	public static function moveGridArea($sequode_model_id = 0, $grid_area_key = 0, $x = 0, $y = 0){
		if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canEdit(SequodeModeler::model())
        )){ return; }
        SequodeOperations::moveGridArea($grid_area_key, $x, $y);

        echo json_encode((object) ["Success" => 1]);
        exit;
	}
    public static function updateValue($sequode_model_id = 0, $type = false, $map_key = 0, $value = null){
        if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canEdit(SequodeModeler::model())
        )){ return; }
        SequodeOperations::updateValue($type, $map_key, $value);

        echo json_encode((object) ["Success" => 1]);
        exit;
    }
    public static function addInternalConnection($sequode_model_id = 0 ,$receiver_type = false, $transmitter_key = 0, $receiver_key = 0){
        if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canEdit(SequodeModeler::model())
        )){ return; }
        SequodeOperations::addInternalConnection($receiver_type, $transmitter_key, $receiver_key);

        echo json_encode((object) ["Success" => 1]);
        exit;
    }
    public static function addExternalConnection($sequode_model_id = 0, $connection_type = false, $transmitter_key = 0, $receiver_key = 0){
        if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canEdit(SequodeModeler::model())
        )){ return; }
        SequodeOperations::addExternalConnection($connection_type, $transmitter_key, $receiver_key);

        echo json_encode((object) ["Success" => 1]);
        exit;
    }
    public static function removeReceivingConnection($sequode_model_id = 0, $connection_type = false, $restore_key = 0){
        if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canEdit(SequodeModeler::model())
        )){ return; }
        SequodeOperations::removeReceivingConnection($connection_type, $restore_key);

        echo json_encode((object) ["Success" => 1]);
        exit;
    }
    public static function updateTenancy($value = 0, $sequode_model_id = 0){
        if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && AccountAuthority::isSystemOwner()
        )){ return; }
        SequodeOperations::updateTenancy($value);
        echo json_encode((object) ["Success" => 1]);
        exit;
    }
    public static function updateSharing($value = 0, $sequode_model_id = 0){
       if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && AccountAuthority::canShare(SequodeModeler::model())
        )){ return; }
        SequodeOperations::updateSharing($value);
        echo json_encode((object) ["Success" => 1]);
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
        echo json_encode((object) ["Success" => 1]);
        exit;;
    }
    public static function updateName($sequode_model_id = 0, $name=''){
        if(!(
        SequodeModeler::exists($sequode_model_id,'id')
        && AccountAuthority::canEdit(SequodeModeler::model())
        )){ 
            return;
        }
        $object = (object) null;
        $object->Success = false;
        $name = trim(str_replace('-','_', str_replace(' ','_',urldecode($name))));
        if(strlen($name)==0){
            $object->Error = 'Name cannot be empty';
        }elseif(!preg_match("/^([A-Za-z0-9_])*$/i",$name)){
            $object->Error = 'Name can be alphanumeric and contain spaces only';
        }elseif(!AccountAuthority::canRenameTo($name, SequodeModeler::model())){
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