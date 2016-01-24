<?php

namespace Sequode\Component\DOMElement\Kit;

use Sequode\Component\FormInput\FormInput;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;

class JS {
    
	public static function setValueJS($dom_id, $value){
        $js[] = 'document.getElementById(\''.$dom_id.'\').value = decodeURIComponent(\'';
        $js[] = rawurlencode($value);
        $js[] = '\');';
        return implode('',$js);
    }
	public static function addEventListenerJS($dom_id, $event, $event_js){
        $js[] = '$(\'#'.$dom_id.'\').on(\''.$event.'\',(function(event) {';
        $js[] = $event_js;
        $js[] = '}));';
        return implode('',$js);
    }
    public static function placeForm($form, $dom_id){
        $html = $js = array();
        if(count($form) == 1){
            foreach($form as $key => $object){
                if(isset($object->html)){
                    $html[] = $object->html;
                }
            }
        }else{
            $html[] = CardKitHTML::contentRowDivider();
            foreach($form as $key => $object){
                if(isset($object->html)){
                    $html[] = $object->html;
                    $html[] = CardKitHTML::contentRowDivider();
                }
            }
        }
        $js[] = self::addIntoDom($dom_id,implode('',$html),'replace');
        foreach($form as $key => $object){
            if(isset($object->js)){
                $js[] = $object->js;
            }
        }
        $js[] = '$(\'.focus-input\').focus();';
        $js[] = '$(\'.focus-input\').select();';    
        return implode(' ',$js);
    }
    public static function googleAnalytics(){
        $js = array();
        $js[] = '(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){';
        $js[] = '(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),';
        $js[] = 'm=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)';
        $js[] = '})(window,document,\'script\',\'//www.google-analytics.com/analytics.js\',\'ga\');';
        $js[] = 'ga(\'create\', \'UA-55113318-1\', \'auto\');';
        $js[] = 'ga(\'send\', \'pageview\');';
        return implode('',$js);
	}
    public static function forceSSL(){
        $js = array();
        $js[] = 'if(document.location.protocol != "https:"){';
        $js[] = 'document.location = document.URL.replace(/^http:/i, "https:");';
        $js[] = '}';
        return implode('',$js);
	}
    public static function jsQuotedValue($value=''){
        return '\''. $value .'\'';
	}
	public static function documentEventOff($event){
		return '$(document).off(\''.$event.'\');';
	}
    public static function onTapEvents($dom_id,$javascript){
        $js = array();
        $js[] = '$(\'#'.$dom_id.'\').on("click touch", function(){';
        $js[] = $javascript;
        $js[] = '});';
        return implode('',$js);
	}
    public static function onTapEventsXHRCall($dom_id, $xhr_call_object){
        return self::onTapEvents($dom_id,self::xhrCall($xhr_call_object));
	}
	public static function xhrCallObject($route='', $inputs=null, $done_callback=false){
        $object = (object) null;
        $object->route = $route;
        $object->inputs = ($inputs == null) ? array() : $inputs;
        $object->done_callback = $done_callback;
		return $object;
	}
	public static function xhrCall($call_object){
        if(is_array($call_object) && isset($call_object['route'])){
            $call_object = (object) $call_object;
        }
        $js = array();
        $js[] = 'new SQDE_XHRCall({';
        $js[] = 'route:\''. $call_object->route .'\'';
        if(is_array($call_object->inputs) && count($call_object->inputs) != 0){
            $js[] = ',inputs:['. implode(',',$call_object->inputs) .']';
        }
        if($call_object->done_callback){
            $js[] = ',done_callback:'. $call_object->done_callback;
        }
        $js[] = '});';
        return implode('',$js);
	}
    public static function loadComponentHere($call_object, $contents='', $icon = 'atom'){
        $html = $js = array();
        $dom_id = FormInput::uniqueHash();
        
        $html[] = '<span id="'.$dom_id.'c">';
        $html[] = '<span class="automagic-card-text-button" id="'.$dom_id.'b">';
        $html[] = $contents;
        if($icon != null){
            $html[] = ' <div class="load-here-icon '.$icon.'-icon-background"></div>'; 
        }
        $html[] = '</span>';
        $html[] = '</span>';
        $call_object->inputs = array_merge($call_object->inputs, array(self::jsQuotedValue($dom_id.'c')));
        $js[] = '$(\'#'.$dom_id.'b\').on("click touchend", function(){';
        $js[] = self::xhrCall($call_object);
        $js[] = '});';
        
        $components_object = (object) null;
        $components_object->dom_id = $dom_id;
        $components_object->html = implode('',$html);
        $components_object->js = implode('',$js);
        return $components_object;
	}
    public static function fetchCollection($collection, $key=null){
        return ($key == null) ? 'registry.fetch({collection:\''.$collection.'\'});' : 'registry.fetch({collection:\''.$collection.'\', key:'.$key.'});';
	}
    public static function addIntoDom($element,$code,$mode='replace'){
		$stream = ' ';
		switch($mode){
			case 'append':
				$stream .= 'document.getElementById(\''.$element.'\').innerHTML = document.getElementById(\''.$element.'\').innerHTML + \''.self::formatForJS($code).'\';';
			break;
			case 'prepend':
				$stream .= 'document.getElementById(\''.$element.'\').innerHTML = \''.self::formatForJS($code).'\' + document.getElementById(\''.$element.'\').innerHTML;';
			break;
			case 'replace':
			default:
			
            $stream .= 'document.getElementById(\''.$element.'\').innerHTML = \''.self::formatForJS($code).'\';';
			break;
		}
		return $stream;
	}
	public static function removeInDom($element,$depth=0){
		$stream .= " ";
		$stream .= ' $(\''.$element.'\').parentNode.removeChild(document.getElementById(\''.$element.'\'));';
		return self::depth($stream,$depth);
	}
    public static function formatForJS($input){
		return str_replace("\r",'\r',str_replace("\n",'\n',addslashes($input)));
	}
    /*
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
	public static function registeyTimeout($variable_name, $javascript, $milliseconds=0){
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
    
    
    */
}