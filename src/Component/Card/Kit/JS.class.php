<?php
namespace Sequode\Component\Card\Kit;
class JS {
    
    public static function placeCard($card, $dom_id = 'CardsContainer'){
        $html = $js = array();
        if($dom_id == 'CardsContainer'){
            $html[] = \Sequode\Component\Card\Kit\HTML::divider();
        }
        $html[] = $card->html;
        $js[] = \Sequode\Component\DOMElement\Kit\JS::addIntoDom($dom_id, implode(' ',$html), 'replace');
        $js[] = $card->js;
        return implode(' ',$js);
    }
    public static function placeDeck($deck, $dom_id = 'CardsContainer', $clear=true, $divide=true, $shim=true){
        $html = $js = array();
        if($divide != false){
            $html[] = \Sequode\Component\Card\Kit\HTML::divider(($shim != false) ? false : true);
        }
        foreach($deck as $card){
            if(isset($card->html)){
                $html[] = $card->html;
            }
        }
        $js[] = \Sequode\Component\DOMElement\Kit\JS::addIntoDom($dom_id, implode(($shim != false) ? \Sequode\Component\Card\Kit\HTML::shim(false,false) : '',$html), ($clear != false) ? 'replace' : 'append');
        foreach($deck as $card){
            if(isset($card->js)){
                $js[] = $card->js;
            }
        }
        return implode(' ',$js);
    }
}