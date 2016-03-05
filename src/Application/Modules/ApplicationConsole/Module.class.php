<?php

namespace Sequode\Application\Modules\ApplicationConsole;

class Module {
    public static $registry_key = 'Console';
	public static function model(){
        
        $_o = (object)  array (
            'context' => 'console',
            'assets' => (object) array(
            
                'boot_collections' => array(
                    'my_sequodes',
                    'sequode_favorites',
                    'palette',
                    'sequodes',
                    'tokens',
                    'packages'
                ),
                
                'css' => array(
                    'css/automagic_cards.css',
                    'css/automagic_content.css',
                    'css/automagic_data.css',
                    'css/automagic_forms.css',
                    'css/automagic_layout.css',
                    'css/btn.css',
                    'css/containers.css',
                    'css/globals.css',
                    'css/icons.css',
                    'css/shortcuts.css',
                    'css/text.css',
                    'css/sequode.css',
                    'css/client.css'
                ),
                
                'javascript' => array(
                
                    'js/Configuration.js',
                    'js/SymbolsKit.js',
                    'js/BaseKit.js',
                    'js/EventsKit.js',
                    'js/ShapesKit.js',
                    'js/CardsKit.js',
                    'js/Cards.js',
                    'js/XHR.js',
                    'js/Model.js',
                    'js/ModelEnds.js',
                    'js/Sequencer.js',
                    'js/SequencerPalette.js',
                    'js/CollectionCards.js',
                    'js/SequodeConsoleRegistry.js',
                    'js/SequodeConsole.js',
                    'js/sequode-main.js'
                ),
            
            ),
            
            'components' => (object) array (
                'cards' => Components\Cards::class
            ),
            
            'routes' =>  array(
                'http' => Routes\Routes::class
            ),
            
            'xhr' => (object) array (
                'cards' => Routes\XHR\Cards::class
            )

            );
		
        return $_o;
	}
    
}