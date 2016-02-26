<?php
namespace Sequode\Component\Form;

use Sequode\Patterns\Mason;
use Sequode\Component\FormInput\FormInput as FormInputComponent;

class Form extends Mason {
    public static $mason = 'form';
    public static $collection_replacement_hook = '[%COLLECTION_JS%]';
	public static function domIds($form_object){
        $dom_ids = array();
        if(is_object($form_object)){
            foreach($form_object as $loop_member => $loop_value){
                $dom_ids[] = FormInputComponent::uniqueHash();
            }
        }
        return $dom_ids;
	}
	public static function xhrCallRoute($context, $channel, $route){        
        return $channel .'/'. $context .'/'. $route;
	}
	public static function xhrCall($route, $inputs){
        $js = array();
        $js[] = 'new XHRCall({';
        $js[] = 'route:\''. $route .'\'';
        if(is_array($inputs) && count($inputs) != 0){
            $js[] = ',inputs:['. implode(',',$inputs) .']';
        }
        $js[] = '});';
        return implode('',$js);
	}
	public static function jsQuotedValue($value=''){
        return '\''. $value .'\'';
	}
	public static function collectValues($form_object, $dom_ids){
        $js = array();
        $js[] = "''";
        if(is_object($form_object)){
            $replacement_object = (object) null;
            $i = 0;
            foreach($form_object as $loop_member => $loop_value){
                $replacement_object->$loop_member = $dom_ids[$i];
                $i++;
            }
            $js = array();
            $js[] = '(function(){';
            $js[] = 'var d = decodeURIComponent(\''.rawurlencode(json_encode($replacement_object)).'\');';
            foreach($dom_ids as $dom_id){
                $js[] =  'd = d.replace(\''.$dom_id.'\', encodeURIComponent(document.getElementById(\''.$dom_id.'\').value));';
            }
            $js[] = 'return d;';
            $js[] = '}())';
        }
        return implode(' ',$js);
	}
	public static function registerTimeout($variable_name, $javascript, $milliseconds=0){
        $js = array();
        $js[] = 'registry.timeout(\''.$variable_name.'\', function(){';
        $js[] = $javascript;
        $js[] = 'registry.timeouts[\''.$variable_name.'\'] = null; },'.$milliseconds.');';
        return implode(' ',$js);
	}
	public static function enterPressed($javascript){
        $js = array();
        $js[] = 'if (event.keyCode == 13){';
        $js[] = $javascript;
        $js[] = '}';
        return implode(' ',$js);
	}
	public static function attachComponentObjectEvents($component_object, $js_events_object){
        foreach($js_events_object as $member => $value){
            $component_object->$member = $value;
        }
		return $component_object;
	}
	public static function components($form_object, $dom_ids, $js_events_array){
		$components_array = array();
		$i = $j = 0;
        foreach($form_object as $member => $component_object){
			$component_object = self::attachComponentObjectEvents($component_object,$js_events_array[$j]);
			$component_object->Dom_Id = $dom_ids[$i];
			$component_object->Value = $form_object->$member->Value;
			$components_array[] = FormInputComponent::render($component_object);
			$i++;
            if(count($js_events_array) > 1){
                $j++;
            }
		}
		return $components_array;
	}
    public static function render($form_object){
        $timeout_var_name = FormInputComponent::uniqueHash();
		$dom_ids = self::domIds($form_object->components);
        $js_event = (object) null;
        $submit_js = '';
        if($form_object->submit_js != null){
            $submit_js = str_replace(static::$collection_replacement_hook, self::collectValues($form_object->components,$dom_ids), $form_object->submit_js);    
        }else{
            $submit_js = str_replace(static::$collection_replacement_hook, self::collectValues($form_object->components,$dom_ids), self::xhrCall($form_object->submit_xhr_call_route, $form_object->submit_xhr_call_parameters));
        }
        $event_js = array();
        if($form_object->auto_submit_time != null){
            $event_js[] = self::registerTimeout($timeout_var_name, $submit_js, $form_object->auto_submit_time);
            $js_event->Value_Changed = implode(' ',$event_js);
        }
        $event_js = array();
        if($form_object->submit_on_enter == true){
            $event_js[] = self::enterPressed(self::registerTimeout($timeout_var_name, $submit_js));
            $js_event->On_Key_Up = implode(' ',$event_js);
        }
        $components_array = self::components($form_object->components, $dom_ids, array($js_event));
        
        if($form_object->submit_button != null){
            FormInputComponent::exists('button','name');
            $button_component = json_decode(FormInputComponent::model()->component_object);
            $button_component->Value = $form_object->submit_button;
            $button_component->CSS_Class = 'btn';
            $button_component->On_Click = self::registerTimeout($timeout_var_name, $submit_js);
            $components_array[] = FormInputComponent::render($button_component);
        }
		return $components_array;
	}
    public static function formObject($object_class, $object_method, $xhr_library, $parameters = null){
        $form_object = (object) null;
        $form_object->components = forward_static_call_array(array($object_class,$object_method),($parameters == null) ? array() : $parameters);
        $form_object->submit_js = null;
        $form_object->submit_button = null;
        $form_object->submit_on_enter = true;
        $form_object->auto_submit_time = null;
        $form_object->submit_xhr_call_route = $xhr_library .'/'. $object_method;
        $form_object->submit_xhr_call_parameters = array(static::$collection_replacement_hook);
        return $form_object;
	}
    
    public static function formObject2($object_class, $object_method, $parameters = null, $xhr_route = null){
        $form_object = (object) null;
        $form_object->components = forward_static_call_array(array($object_class,$object_method),($parameters == null) ? array() : $parameters);
        $form_object->submit_js = null;
        $form_object->submit_button = null;
        $form_object->submit_on_enter = true;
        $form_object->auto_submit_time = null;
        $form_object->submit_xhr_call_route = $xhr_route;
        $form_object->submit_xhr_call_parameters = array(static::$collection_replacement_hook);
        return $form_object;
	}
}