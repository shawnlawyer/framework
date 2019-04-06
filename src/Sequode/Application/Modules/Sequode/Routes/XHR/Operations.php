<?php

namespace Sequode\Application\Modules\Sequode\Routes\XHR;

use Sequode\Application\Modules\Sequode\Module;
use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\Sequode\Authority as SequodeAuthority;
use Sequode\Application\Modules\Account\Modeler as AccountModeler;

class Operations {
    
    public static $module = Module::class;
    const Module = Module::class;
    
    public static function updateValue($type, $_model_id, $map_key, $json){

        extract((static::Module)::variables());
        $collection = 'sequodes';

        if(!(
            $modeler::exists($_model_id,'id')
            && SequodeAuthority::isSequence()
            && AccountAuthority::canEdit($modeler::model())
        )){ return; }

        $input = json_decode($json);
        if (!is_object($input)){ return; }
        switch($type){
            case 'input':
            case 'property':
                $model_member = $type.'_object_map';
                break;
            default:
                return false;
        }

        $object_map = $modeler::model()->$model_member;
        forward_static_call_array([$operations, __FUNCTION__], [$type, $map_key, rawurldecode($input->location)]);
        if(empty($object_map[$map_key]->Value)){

			return implode(' ',[
			    DOMElementKitJS::fetchCollection($collection, $modeler::model()->id)
            ]);

        }
    }

    public static function updateComponentSettings($type, $member, $member_json, $_model_id, $dom_id='FormsContainer'){

        extract((static::Module)::variables());
        
        if(!(
        $modeler::exists($_model_id,'id')
        && AccountAuthority::canEdit($modeler::model())
        )){ return; }
        $form_member_object = json_decode(stripslashes($member_json));
        if(!is_object($form_member_object)){return;}
        foreach($form_member_object as $key => $value){
            $form_member_object->$key = urldecode($value);
        }
        switch($type){
            case 'input':
            case 'property':
                $model_member = $type.'_form_object';
                break;
            default:
                return;
        }
        $previous_form_object = $modeler::model()->$model_member;
        forward_static_call_array([$operations, __FUNCTION__], [$type, $member, $form_member_object]);
        if($previous_form_object->$member->Component != $form_member_object->Component){
            
            $js = [];
            $js[] = 'new XHRCall({route:"'. $module::xhrCardRoute('componentSettings') .'", inputs:[\''.$type.'\', \''.$member.'\', '.$modeler::model()->id.', \''.$dom_id.'\']});';
			return implode(' ',$js);
            
        }
    }

    public static function cloneSequence($_model_id){

        extract((static::Module)::variables());
        $collection = 'sequodes';
        
        if(!(
            AccountAuthority::canCreate()
            && $modeler::exists($_model_id,'id')
            && SequodeAuthority::isSequence()
            && AccountAuthority::canCopy($modeler::model())
        )){ return; }

        forward_static_call_array([$operations, 'makeSequenceCopy'], [AccountModeler::model()->id]);

        return implode(' ', [
            DOMElementKitJS::fetchCollection($collection, $modeler::model()->id),
            forward_static_call_array([$xhr_cards, 'card'], ['details'])
        ]);

    }

    public static function newSequence(){

        extract((static::Module)::variables());
        $collection = 'sequodes';

        if(!(
            AccountAuthority::canCreate()
        )){ return; }

        forward_static_call_array([$operations, __FUNCTION__], [AccountModeler::model()->id]);

        return implode(' ', [
            DOMElementKitJS::fetchCollection($collection, $modeler::model()->id),
            forward_static_call_array([$xhr_cards, 'card'], ['details'])
        ]);
    }

    public static function updateName($_model_id, $json){

        extract((static::Module)::variables());
        $collection = 'sequodes';

        if(!(
            $modeler::exists($_model_id,'id')
            && AccountAuthority::canEdit($modeler::model())
        )){ return; }

        $input = json_decode($json);
        $name = trim(str_replace('-','_',str_replace(' ','_',urldecode($input->name))));

        if(strlen($name)==0){
            return ' alert(\'Name cannot be empty\');';
        }
        if(!preg_match("/^([A-Za-z0-9_])*$/i",$name)){
            return ' alert(\'Name can be alphanumeric and contain spaces only\');';
        }
        if(!AccountAuthority::canRenameTo($name, $modeler::model())){
            return ' alert(\'Name already exists\');';
        }

        $modeler::exists($_model_id, 'id');
        forward_static_call_array([$operations, __FUNCTION__], [$name]);

        return implode(' ', [
            DOMElementKitJS::fetchCollection($collection, $modeler::model()->id),
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);

    }

    public static function deleteSequence($_model_id, $confirmed=false){

        extract((static::Module)::variables());
        $collection = 'sequodes';

        if(!(
            $modeler::exists($_model_id,'id')
            && SequodeAuthority::isSequence()
            && AccountAuthority::canDelete($modeler::model())
        )){ return; }

        if ($confirmed===false){

            return DOMElementKitJS::confirmOperation($module::xhrOperationRoute(__FUNCTION__), $modeler::model()->id);

        }else{

            forward_static_call_array([$operations, __FUNCTION__], []);

            return implode(' ', [
                DOMElementKitJS::fetchCollection($collection, $modeler::model()->id),
                forward_static_call_array([$xhr_cards, 'card'], ['my'])
            ]);

        }

    }

    public static function formatSequence($_model_id, $confirmed=false){

        extract((static::Module)::variables());
        $collection = 'sequodes';

        if(!(
            $modeler::exists($_model_id,'id')
            && SequodeAuthority::isSequence()
            && AccountAuthority::isOwner($modeler::model())
        )){ return; }

        if ($confirmed===false){

            return DOMElementKitJS::confirmOperation($module::xhrOperationRoute(__FUNCTION__), $modeler::model()->id);

        }else{

            forward_static_call_array([$operations, 'makeDefaultSequencedSequode'], []);

            return implode(' ',[
                DOMElementKitJS::fetchCollection($collection, $modeler::model()->id),
                DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
            ]);

        }

    }

	public static function addToSequence($_model_id, $add_model_id, $position=0, $position_tuner = null, $grid_modifier = null){

        extract((static::Module)::variables());
        
        if(!(
            $modeler::exists($add_model_id,'id')
            && AccountAuthority::canRun($modeler::model())
            && $modeler::exists($_model_id,'id')
            && AccountAuthority::canEdit($modeler::model())
            && SequodeAuthority::isSequence()
            && !SequodeAuthority::isFullSequence()
		)){ return; }

        forward_static_call_array([$operations, __FUNCTION__], [$add_model_id, $position, $position_tuner, $grid_modifier]);

        return implode('', [
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);

	}

	public static function reorderSequence($_model_id, $from_position=0, $to_position=0, $position_tuner = null, $grid_modifier = null){

        extract((static::Module)::variables());
        
        if(!(
            $modeler::exists($_model_id,'id')
            && SequodeAuthority::isSequence()
            && AccountAuthority::canEdit($modeler::model())
		)){ return; }

        forward_static_call_array([$operations, __FUNCTION__], [$from_position, $to_position, $position_tuner, $grid_modifier]);

        return implode('', [
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);

	}

	public static function removeFromSequence($_model_id, $position){

        extract((static::Module)::variables());
        
        if(!(
            $modeler::exists($_model_id,'id')
            && SequodeAuthority::isSequence()
            && AccountAuthority::canEdit($modeler::model())
		)){ return; }

        forward_static_call_array([$operations, __FUNCTION__], [$position]);

        return implode('', [
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);

	}

	public static function modifyGridAreas($_model_id, $position){

        extract((static::Module)::variables());
        
        if(!(
		    $modeler::exists($_model_id,'id')
            && SequodeAuthority::isSequence()
            && AccountAuthority::canEdit($modeler::model())
		)){ return; }

        forward_static_call_array([$operations, __FUNCTION__], [$position]);

        return implode('', [
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);
	}

	public static function emptySequence($_model_id){

        extract((static::Module)::variables());
        $collection = 'sequodes';

        if(!(
            $modeler::exists($_model_id,'id')
            && SequodeAuthority::isSequence()
            && AccountAuthority::canEdit($modeler::model())
        )){ return; }

        forward_static_call_array([$operations, __FUNCTION__], []);

        return implode('', [
            DOMElementKitJS::fetchCollection($collection, $modeler::model()->id),
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);

	}

	public static function moveGridArea($_model_id, $grid_area_key = 0, $x = 0, $y = 0){

        extract((static::Module)::variables());
        
        if(!(
            $modeler::exists($_model_id,'id')
            && SequodeAuthority::isSequence()
            && AccountAuthority::canEdit($modeler::model())
        )){ return; }

        forward_static_call_array([$operations, __FUNCTION__], [$grid_area_key, $x, $y]);$js =[];

        return implode('', [
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);
	}

	public static function addInternalConnection($_model_id, $receiver_type = false, $transmitter_key = 0, $receiver_key = 0){

        extract((static::Module)::variables());
        
        if(!(
            $modeler::exists($_model_id,'id')
            && SequodeAuthority::isSequence()
            && AccountAuthority::canEdit($modeler::model())
        )){ return; }

        forward_static_call_array([$operations, __FUNCTION__], [$receiver_type, $transmitter_key, $receiver_key]);$js =[];

        return implode('', [
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);
	}

	public static function addExternalConnection($_model_id, $receiver_type = false, $transmitter_key = 0, $receiver_key = 0){

        extract((static::Module)::variables());
        
        if(!(
            $modeler::exists($_model_id,'id')
            && SequodeAuthority::isSequence()
            && AccountAuthority::canEdit($modeler::model())
        )){ return; }

        forward_static_call_array([$operations, __FUNCTION__], [$receiver_type, $transmitter_key, $receiver_key]);

        return implode('', [
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);
	}

	public static function removeReceivingConnection($_model_id, $connection_type = false, $restore_key = 0){

        extract((static::Module)::variables());
        
        if(!(
            $modeler::exists($_model_id,'id')
            && SequodeAuthority::isSequence()
            && AccountAuthority::canEdit($modeler::model())
        )){ return; }

        forward_static_call_array([$operations, __FUNCTION__], [$connection_type, $restore_key]);

        return implode('', [
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);

	}

	public static function updateSharing($_model_id, $json){

        extract((static::Module)::variables());
        
        if(!(
            $modeler::exists($_model_id,'id')
            && AccountAuthority::canShare($modeler::model())
        )){ return; }

        $input = json_decode($json);
        if (!is_object($input)){ return; }

        forward_static_call_array([$operations, __FUNCTION__], [rawurldecode($input->sharing)]);

        return implode('', [
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);
	}

	public static function updateIsPalette($_model_id,$json){

        extract((static::Module)::variables());
        
        if(!(
            $modeler::exists($_model_id,'id')
            && AccountAuthority::canEdit($modeler::model())
        )){return;}

        $input = json_decode($json);
        if (!is_object($input)){ return; }

        forward_static_call_array([$operations, __FUNCTION__], [rawurldecode($input->palette)]);

        return implode('', [
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);

	}

	public static function updateIsPackage($_model_id, $json){

        extract((static::Module)::variables());
        
        if(!(
            $modeler::exists($_model_id,'id')
            && AccountAuthority::canEdit($modeler::model())
        )){return;}

        $input = json_decode($json);
        if (!is_object($input)){ return; }

        forward_static_call_array([$operations, __FUNCTION__], [rawurldecode($input->package)]);

        return implode('', [
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);

	}

	public static function updateDescription($_model_id, $json){

        extract((static::Module)::variables());
        
        if(!(
            $modeler::exists($_model_id,'id')
            && AccountAuthority::canEdit($modeler::model())
        )){ return; }

        $input = json_decode($json);
        if (!is_object($input)){ return; }

        forward_static_call_array([$operations, __FUNCTION__], [rawurldecode($input->description)]);

        return implode('', [
            DOMElementKitJS::registryRefreshContext([$modeler::model()->id])
        ]);

	}

    public static function search($json){

        $collection = 'sequode_search';

        $input = json_decode(stripslashes($json));
        $input = (!is_object($input) || (trim($input->search) == '' || empty(trim($input->search)))) ? (object) null : $input;

        SessionStore::set($collection, $input); //potential security issue where this could be set to something large, let's filter this

		$js=[];
        if(SessionStore::get('palette') == $collection){
            $js[] = DOMElementKitJS::fetchCollection('palette');
        }
        $js[] = DOMElementKitJS::fetchCollection($collection);

        return implode('', $js);

    }

    public static function selectPalette($json){

        extract((static::Module)::variables());
        
        $input = json_decode(stripslashes($json));
        if(!is_object($input) || (trim($input->palette) == '' || empty(trim($input->palette)))){
            
            SessionStore::set('palette', false);
            
        }else{
            
            switch($input->palette){
                
                case 'sequode_search':
                case 'sequode_favorites':
                    SessionStore::set('palette', $input->palette);
                    break;

                default:
                    if((
                        $modeler::exists($input->palette, 'id')
                        && AccountAuthority::canView($modeler::model())
                    )){
                        SessionStore::set('palette', $input->palette);
                    }
                    break;
                    
            }
            
        }

        return implode(' ', [
            DOMElementKitJS::fetchCollection('palette')
        ]);

    }

}