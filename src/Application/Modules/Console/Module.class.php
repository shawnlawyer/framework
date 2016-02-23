<?php
namespace Sequode\Application\Modules\Console;

class Module {
    public static $registry_key = 'Console';
	public static function model(){
        $_o = (object)  array (
            'context' => 'console',
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