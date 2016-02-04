<?php


namespace Sequode\Application\Modules\Site;

class Module {
    public static $package = 'Site';
	public static function model(){
        $_o = (object)  array (
            'context' => 'account',
            'card_objects' => Components\Cards::class,
            'xhr' => (object) array (
                'cards' => Routes\XHR\Cards::class
            )
        );
		return $_o;
	}
}