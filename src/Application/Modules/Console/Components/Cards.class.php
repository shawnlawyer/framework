<?php

namespace Sequode\Application\Modules\Console\Components;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Card as ModuleCard;
use Sequode\Component\Card\CardKit as CardKit;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;

use Sequode\Application\Modules\Console\Module;

class Cards {
    
    public static $module = Module::class;
    
    public static function index(){
        return self::tiles(\Sequode\Application\Modules\Account\Modeler::model());
    }
    public static function tiles($user_model=null){
        
        $module = static::$module;
        if($user_model == null ){ $user_model = \Sequode\Application\Modules\Account\Modeler::model(); }
        $_o = (object) null;
        $_o->size = 'fullscreen';
        $_o->head = 'Sequode';
        $_o->icon_background = 'sequode-icon-background';
        $_o->body = array();
        $modules = ModuleRegistry::modules();
        $cards = array();
        foreach($modules as $registry_key => $module){
            if(!empty($module::model()->components->cards)){
                $class = $module::model()->components->cards;
                if(!empty($class::$tiles) && is_array($class::$tiles)){
                    foreach($class::$tiles as $card){
                        $cards[] = ModuleCard::render($module::$registry_key, $card, array($user_model));
                    }
                    
                }
                
            }
            
        }
        
        $html = $js = array();
        $html[] = CardKitHTML::divider(true);
        $html[] = '<div class="fitBlock alignCenter">';
        foreach($cards as $key => $card){
            if($key != 0){
                $html[] = CardKitHTML::shim();
            }
            $html[] = $card->html;
            $js[] = $card->js;
        }
        $html[] = '</div>';
        $_o->body[] = (object) array('html' => implode('',$html),'js' => implode('',$js));
        return $_o;
    }
    
}