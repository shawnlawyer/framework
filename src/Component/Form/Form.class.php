<?php
namespace Sequode\Component\Form;

use Sequode\Patterns\Mason;


use Sequode\Component\FormInput\FormInput as FormInputComponent;
use Sequode\Application\Modules\FormInput\Modeler as FormInputModeler;

class Form extends Mason {
    
    public static $mason = 'form';
    
    public static $collection_replacement_hook = '[%COLLECTION_JS%]';
    
	public static function domIds($_i){
        $dom_ids = array();
        if(is_object($_i)){
            foreach($_i as $loop_member => $loop_value){
                $dom_ids[] = FormInputComponent::uniqueHash();
            }
        }
        return $dom_ids;
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
    
	public static function xhrCallRoute($context, $channel, $route){        
        return $channel .'/'. $context .'/'. $route;
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
    
	public static function renderFormInputs($form_object, $dom_ids, $js_events_array){
        
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
    
    public static function render($_i){
        
        $timeout_var_name = FormInputComponent::uniqueHash();
		$dom_ids = self::domIds($_i->form_inputs);
        $js_event = (object) null;
        $submit_js = '';
        if($_i->submit_js != null){
            $submit_js = str_replace(static::$collection_replacement_hook, self::collectValues($_i->form_inputs,$dom_ids), $_i->submit_js);    
        }else{
            $submit_js = str_replace(static::$collection_replacement_hook, self::collectValues($_i->form_inputs,$dom_ids), self::xhrCall($_i->submit_xhr_call_route, $_i->submit_xhr_call_parameters));
        }
        $event_js = array();
        if($_i->auto_submit_time != null){
            $event_js[] = self::registerTimeout($timeout_var_name, $submit_js, $_i->auto_submit_time);
            $js_event->Value_Changed = implode(' ',$event_js);
        }
        $event_js = array();
        if($_i->submit_on_enter == true){
            $event_js[] = self::enterPressed(self::registerTimeout($timeout_var_name, $submit_js));
            $js_event->On_Key_Up = implode(' ',$event_js);
        }
        $components_array = self::renderFormInputs($_i->form_inputs, $dom_ids, array($js_event));
        
        if($_i->submit_button != null){
            FormInputModeler::exists('button','name');
            $button_component = json_decode(FormInputModeler::model()->component_object);
            $button_component->Value = $_i->submit_button;
            $button_component->CSS_Class = 'btn';
            $button_component->On_Click = self::registerTimeout($timeout_var_name, $submit_js);
            $components_array[] = FormInputModeler::render($button_component);
        }
		return $components_array;
	}
    
    public static function formInputs($class, $method, $parameters = null){
        
        return forward_static_call_array(array($class, $method),($parameters == null) ? array() : $parameters);
        
	}
    
    public static function formObject2($object_class, $object_method, $parameters = null){
        
        $_o = (object) null;
        $_o->form_inputs = forward_static_call_array(array($object_class, $object_method),($parameters == null) ? array() : $parameters);
        $_o->submit_js = null;
        $_o->submit_button = null;
        $_o->submit_on_enter = true;
        $_o->auto_submit_time = null;
        $_o->submit_xhr_call_route = '';
        $_o->submit_xhr_call_parameters = array(static::$collection_replacement_hook);
                
        return $_o;
	}
    
    public static function formObject($_i = null){
        
        $_o = (object) array(
            'form_inputs' => array(),
            'submit_js' => null,
            'submit_button' => null,
            'submit_on_enter' => true,
            'auto_submit_time' => null,
            'submit_xhr_call_route' => '',
            'submit_xhr_call_parameters' => array(static::$collection_replacement_hook),
        );
        
        if(is_object($_i)){
            
            foreach($_o as $member => $value ){
                
                if(!isset($_i->$member)){
                    
                    $_o->$member = $_i->$member;
                    
                }
                
            }
            
        }
        
        return $_o;
	}
}