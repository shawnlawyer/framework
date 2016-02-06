<?php

namespace Sequode\Application\Modules\Session;

class Module {
    public static $package = 'Session'; 
	public static function model(){
        $model = (object) null;;
        $model->context = 'session';
        $model->collections = Routes\Collections\Collections::class;
        $model->modeler = 'SQDE_Session';
        $model->finder = Collections::class;
        $model->card_objects = Components\Cards::class;
        $model->form_objects = Components\Forms::class;
        $model->xhr = (object) null;
        $model->xhr->operations = Routes\XHR\Operations::class;
        $model->xhr->forms = Routes\XHR\Forms::class;
        $model->xhr->cards = Routes\XHR\Cards::class;
		return $model;
	}
}