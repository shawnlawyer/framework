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
    
    public static function updateValue($type, $_model_id, $map_key, $json){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        
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
			$js = [];
            $collection = 'sequodes';
            $js[] = DOMElementKitJS::fetchCollection($collection, $modeler::model()->id);
			return implode(' ',$js);
        }
    }

    public static function updateComponentSettings($type, $member, $member_json, $_model_id, $dom_id='FormsContainer'){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        
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
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        if(!(
        AccountAuthority::canCreate()
        && $modeler::exists($_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canCopy($modeler::model())
        )){ return; }
        forward_static_call_array([$operations, 'makeSequenceCopy'], [AccountModeler::model()->id]);
        $js = [];
        $collection = 'sequodes';
        $js[] = DOMElementKitJS::fetchCollection($collection, $modeler::model()->id);
        $js[] = forward_static_call_array([$xhr_cards, 'card'], ['details']);
        return implode(' ', $js);
    }

    public static function newSequence(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        $cards = $module::model()->xhr->cards;

        if(!(
        AccountAuthority::canCreate()
        )){ return; }
        forward_static_call_array([$operations, __FUNCTION__], [AccountModeler::model()->id]);
        $js = [];
        $collection = 'sequodes';
        $js[] = DOMElementKitJS::fetchCollection($collection, $modeler::model()->id);
        $js[] = forward_static_call_array([$xhr_cards, 'card'], ['details']);
        return implode(' ', $js);
    }

    public static function updateName($_model_id, $json){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && AccountAuthority::canEdit($modeler::model())
        )){ return; }
        $_o = json_decode($json);
        $name = trim(str_replace('-','_',str_replace(' ','_',urldecode($_o->name))));
        if(strlen($name)==0){
            return ' alert(\'Name cannot be empty\');';
        }
        if(!preg_match("/^([A-Za-z0-9_])*$/i",$name)){
            return ' alert(\'Name can be alphanumeric and contain spaces only\');';
        }
        if(!AccountAuthority::canRenameTo($name, $modeler::model())){
            return ' alert(\'Name already exists\');';
        }
        $modeler::exists($_model_id,'id');
        forward_static_call_array([$operations, __FUNCTION__], [$name]);
        $collection = 'sequodes';
        $js[] = DOMElementKitJS::fetchCollection($collection, $modeler::model()->id);
        $js[] = DOMElementKitJS::registryRefreshContext([$modeler::model()->id]);
        return implode(' ', $js);

    }

    public static function deleteSequence($_model_id, $confirmed=false){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        $xhr_cards = $module::model()->xhr->cards;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canDelete($modeler::model())
        )){ return; }
        $sequence = $modeler::model()->sequence;
        $js = [];
        if ($confirmed===false){
			$js[] = 'if(';
			$js[] = 'confirm(\'Are you sure you want to delete this?\')';
			$js[] = '){';
            $js[] = 'new XHRCall({route:"'.$module::xhrOperationRoute(__FUNCTION__).'",inputs:['.$modeler::model()->id.', true]});';
			$js[] = '}';
        }else{
            forward_static_call_array([$operations, __FUNCTION__], []);
            $collection = 'sequodes';
            $js[] = DOMElementKitJS::fetchCollection($collection, $modeler::model()->id);
            $js[] = forward_static_call_array([$xhr_cards, 'card'], ['my']);
        }
        return implode(' ', $js);
    }

    public static function formatSequence($_model_id, $confirmed=false){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        
        if(!(
        
        $modeler::exists($_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::isOwner($modeler::model())
        
        )){ return; }
        
        $js = [];
        if ($confirmed===false){
			$js[] = 'if(';
			$js[] = 'confirm(\'Are you sure you want to format '.$modeler::model()->id.'?\')';
			$js[] = '){';
            $js[] = 'new XHRCall({route:"'. $module::xhrOperationRoute(__FUNCTION__).'",inputs:['.$modeler::model()->id.', true]});';
			$js[] = '}';
        }else{
            forward_static_call_array([$operations, 'makeDefaultSequencedSequode'], []);
            $collection = 'sequodes';
            $js[] = DOMElementKitJS::fetchCollection($collection, $modeler::model()->id);
            $js[] = DOMElementKitJS::registryRefreshContext([$modeler::model()->id]);
        }
        return implode(' ',$js);
    }

	public static function addToSequence($_model_id, $add_model_id, $position=0, $position_tuner = null, $grid_modifier = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        
        if(!(
		$modeler::exists($add_model_id,'id')
		&& AccountAuthority::canRun($modeler::model())
		&& $modeler::exists($_model_id,'id')
		&& AccountAuthority::canEdit($modeler::model())
        && SequodeAuthority::isSequence()
        && !SequodeAuthority::isFullSequence()
		)){ return; }
        forward_static_call_array([$operations, __FUNCTION__], [$add_model_id, $position, $position_tuner, $grid_modifier]);

		return;
	}

	public static function reorderSequence($_model_id, $from_position=0, $to_position=0, $position_tuner = null, $grid_modifier = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        
        if(!(
		$modeler::exists($_model_id,'id')
        && SequodeAuthority::isSequence()
		&& AccountAuthority::canEdit($modeler::model())
		)){ return; }
        forward_static_call_array([$operations, __FUNCTION__], [$from_position, $to_position, $position_tuner, $grid_modifier]);
		return;
	}

	public static function removeFromSequence($_model_id, $position){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        
        if(!(
		$modeler::exists($_model_id,'id')
        && SequodeAuthority::isSequence()
		&& AccountAuthority::canEdit($modeler::model())
		)){ return; }
        forward_static_call_array([$operations, __FUNCTION__], [$position]);
		return;
	}

	public static function modifyGridAreas($_model_id, $position){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        
        if(!(
		$modeler::exists($_model_id,'id')
        && SequodeAuthority::isSequence()
		&& AccountAuthority::canEdit($modeler::model())
		)){ return; }
        forward_static_call_array([$operations, __FUNCTION__], [$position]);
		return;
	}

	public static function emptySequence($_model_id){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canEdit($modeler::model())
        )){ return; }
        forward_static_call_array([$operations, __FUNCTION__], []);
        $collection = 'sequodes';
        $js[] = DOMElementKitJS::fetchCollection($collection, $modeler::model()->id);
        $js[] = DOMElementKitJS::registryRefreshContext([$modeler::model()->id]);
        return implode('',$js);
	}

	public static function moveGridArea($_model_id, $grid_area_key = 0, $x = 0, $y = 0){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canEdit($modeler::model())
        )){ return; }
        forward_static_call_array([$operations, __FUNCTION__], [$grid_area_key, $x, $y]);$js =[];
        $js[] = DOMElementKitJS::registryRefreshContext([$modeler::model()->id]);
        return implode('', $js);
	}

	public static function addInternalConnection($_model_id, $receiver_type = false, $transmitter_key = 0, $receiver_key = 0){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canEdit($modeler::model())
        )){ return; }
        forward_static_call_array([$operations, __FUNCTION__], [$receiver_type, $transmitter_key, $receiver_key]);$js =[];
        $js[] = DOMElementKitJS::registryRefreshContext([$modeler::model()->id]);
        return implode('', $js);
	}

	public static function addExternalConnection($_model_id, $receiver_type = false, $transmitter_key = 0, $receiver_key = 0){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canEdit($modeler::model())
        )){ return; }
        forward_static_call_array([$operations, __FUNCTION__], [$receiver_type, $transmitter_key, $receiver_key]);
		return;
	}

	public static function removeReceivingConnection($_model_id, $connection_type = false, $restore_key = 0){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && SequodeAuthority::isSequence()
        && AccountAuthority::canEdit($modeler::model())
        )){ return; }
        $_o = json_decode($json);
        if (!is_object($_o)){ return; }
        forward_static_call_array([$operations, __FUNCTION__], [$connection_type, $restore_key]);$js =[];
        $js[] = DOMElementKitJS::registryRefreshContext([$modeler::model()->id]);
        return implode('', $js);
	}

	public static function updateSharing($_model_id,$json){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && AccountAuthority::canShare($modeler::model())
        )){ return; }
        $_o = json_decode($json);
        if (!is_object($_o)){ return; }
        forward_static_call_array([$operations, __FUNCTION__], [rawurldecode($_o->sharing)]);
        $js =[];
        $js[] = DOMElementKitJS::registryRefreshContext([$modeler::model()->id]);
        return implode('', $js);
	}

	public static function updateIsPalette($_model_id,$json){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        
        if(!(
            $modeler::exists($_model_id,'id')
            && AccountAuthority::canEdit($modeler::model())
        )){return;}
        $_o = json_decode($json);
        if (!is_object($_o)){ return; }
        forward_static_call_array([$operations, __FUNCTION__], [rawurldecode($_o->palette)]);
        $js =[];
        $js[] = DOMElementKitJS::registryRefreshContext([$modeler::model()->id]);
        return implode('', $js);
	}

	public static function updateIsPackage($_model_id,$json){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        
        if(!(
            $modeler::exists($_model_id,'id')
            && AccountAuthority::canEdit($modeler::model())
        )){return;}
        $_o = json_decode($json);
        if (!is_object($_o)){ return; }
        forward_static_call_array([$operations, __FUNCTION__], [rawurldecode($_o->package)]);
        $js =[];
        $js[] = DOMElementKitJS::registryRefreshContext([$modeler::model()->id]);
        return implode('', $js);
	}

	public static function updateDescription($_model_id, $json){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && AccountAuthority::canEdit($modeler::model())
        )){ return; }
        $_o = json_decode($json);
        if (!is_object($_o)){ return; }
        forward_static_call_array([$operations, __FUNCTION__], [rawurldecode($_o->description)]);
        $js =[];
        $js[] = DOMElementKitJS::registryRefreshContext([$modeler::model()->id]);
		return implode('', $js);
	}

    public static function search($json){
        $_o = json_decode(stripslashes($json));
        $_o = (!is_object($_o) || (trim($_o->search) == '' || empty(trim($_o->search)))) ? (object) null : $_o;
        $collection = 'sequode_search';
        SessionStore::set($collection, $_o);
		$js=[];
        if(SessionStore::get('palette') == $collection){
            $js[] = DOMElementKitJS::fetchCollection('palette');
        }
        $js[] = DOMElementKitJS::fetchCollection($collection);
        return implode('',$js);
    }

    public static function selectPalette($json){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        $_o = json_decode(stripslashes($json));
        if(!is_object($_o) || (trim($_o->palette) == '' || empty(trim($_o->palette)))){
            
            SessionStore::set('palette', false);
            
        }else{
            
            switch($_o->palette){
                
                case 'sequode_search':
                case 'sequode_favorites':
                    SessionStore::set('palette', $_o->palette);
                    break;
                default:
                    if((
                    $modeler::exists($_o->palette, 'id')
                    && AccountAuthority::canView($modeler::model())
                    )){
                    SessionStore::set('palette', $_o->palette);
                    }
                    break;
                    
            }
            
        }
        $js = [];
        $js[]=  DOMElementKitJS::fetchCollection('palette');
        return implode(' ',$js);
    }
}