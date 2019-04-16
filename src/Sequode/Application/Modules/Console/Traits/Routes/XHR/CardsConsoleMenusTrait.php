<?php

namespace Sequode\Application\Modules\Console\Traits\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Card as ModuleCard;

use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;


trait CardsConsoleMenusTrait {
	
    public static function menus($dom_id = 'MenusContainer'){

        $modules = ModuleRegistry::modules();
        
        $html = $js = [];
        $i = count($modules);
        foreach($modules as $module){
            $model = $module::model();
            
            if(isset($model->components->cards)){
                
                if(in_array('menu', get_class_methods($model->components->cards))){
                    $i--;
					$card = ModuleCard::render($module::Registry_Key, 'menu', [], [ModuleCard::Modifier_No_Context]);
                    $html[] = CardKitHTML::menuCardHidingContainer($card->html,$i);
                    $js[] = $card->js;
                    
				}
                
            }
            
        }
        return DOMElementKitJS::addIntoDom($dom_id, implode('',$html), 'replace'). implode(' ',$js);
        
    }
    
}

