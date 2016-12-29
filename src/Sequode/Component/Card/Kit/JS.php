<?php
namespace Sequode\Component\Card\Kit;

use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;

class JS {
    
    public static function placeCard($card, $dom_id = 'CardsContainer'){
        $html = $js = array();
        if($dom_id == 'CardsContainer'){
            $html[] = CardKitHTML::divider();
        }
        $html[] = $card->html;
        $js[] = DOMElementKitJS::addIntoDom($dom_id, implode(' ',$html), 'replace');
        $js[] = $card->js;
        return implode(' ',$js);
    }
    public static function placeDeck($deck, $dom_id = 'CardsContainer', $clear=true, $divide=true, $shim=true){
        $html = $js = array();
        if($divide != false){
            $html[] = CardKitHTML::divider(($shim != false) ? false : true);
        }
        foreach($deck as $card){
            if(isset($card->html)){
                $html[] = $card->html;
            }
        }
        $js[] = DOMElementKitJS::addIntoDom($dom_id, implode(($shim != false) ? CardKitHTML::shim(false,false) : '',$html), ($clear != false) ? 'replace' : 'append');
        foreach($deck as $card){
            if(isset($card->js)){
                $js[] = $card->js;
            }
        }
        return implode(' ',$js);
    }
}