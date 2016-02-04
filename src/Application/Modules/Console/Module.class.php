<?php
namespace Sequode\Application\Modules\Console;

class Module {
    public static $package = 'Console';
	public static function model(){
        $_o = (object)  array (
            'context' => 'console',
            'card_objects' => Components\Cards::class,
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