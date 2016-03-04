<?php
namespace Sequode\Application\Modules\AuthConsole;

class Module {
    public static $registry_key = 'Console';
	public static function model(){
        
        $_o = (object)  array (
            'context' => 'console',
            'assets' => (object) array( 
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
                    'js/auth-main.js'
                    'js/XHR.js',
                    'js/AuthConsole.js',
                    'js/AuthConsoleRegistry.js'
                ),
            ),
            'components' => (object) array (
                'cards' => Components\Cards::class
            ),
            'routes' =>  array(
                Routes\Routes::class
            ),
            'xhr' => (object) array (
                'cards' => Routes\XHR\Cards::class
            )
        );
		
        return $_o;
	}
    
}