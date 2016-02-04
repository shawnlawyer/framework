<?php
namespace Sequode\Application\Modules\Authed;

class Module {
    public static $package = 'Authed';
	public static function model(){
        $_o = (object)  array (
            'context' => 'auth',
            'modeler' => \Sequode\Application\Modules\Auth\Modeler::class,
            'card_objects' => Components\Cards::class,
            'operations' => Operations::class,
            'xhr' => (object) array (
                'operations' => Routes\XHR\Operations::class
            )
        );
		return $_o;
	}
}