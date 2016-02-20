<?php

namespace Sequode\Application\Modules\Site;

class Module {
    public static function model(){
        $_o = (object)  array (
            'context' => 'site',
            'components' => (object) array (
                'cards' => Components\Cards::class,
            ),
            'xhr' => (object) array (
                'cards' => Routes\XHR\Cards::class
            )
        );
		return $_o;
	}
}