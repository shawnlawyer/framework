<?php

namespace Sequode\Application\Modules\Console\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Card as ModuleCard;

use Sequode\Component\Card\Kit\JS as CardKitJS;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

class Cards {
    public static $package = 'Console';
    public static function index($dom_id = 'CardsContainer'){
        return CardKitJS::placeCard(ModuleCard::render(self::$package,__FUNCTION__,array(\Sequode\Application\Modules\Account\Modeler::model())), $dom_id);
    }
    public static function menus($dom_id = 'MenusContainer'){
        $html = $js = array();
        $modules = ModuleRegistry::models();
        $i = count($modules);
        foreach($modules as $module => $model){
            if(isset($model->components->cards)){
                if(in_array('menu',get_class_methods($model->components->cards))){
                    $i--;
					$card = ModuleCard::render($module,'menu');
                    $html[] = CardKitHTML::menuCardHidingContainer($card->html,$i);
                    $js[] = $card->js;
				}
            }
        }
        return DOMElementKitJS::addIntoDom($dom_id, implode('',$html), 'replace'). implode(' ',$js);
    }
}