<?php
class SQDE_SequodePackage {
    public static $package = 'Sequode'; 
	public static function model(){
        $model = (object) null;
        $model->context = 'sequode';
        $model->collections = 'SQDE_SequodeCollections';
        $model->modeler = 'SQDE_Sequode';
        $model->finder = 'SQDE_SequodeFinder';
        $model->card_objects = 'SQDE_SequodeCardObjects';
        $model->form_objects = 'SQDE_SequodeFormObjects';
        $model->operations = 'SQDE_SequodeOperations';
        $model->operations_kit = 'SQDE_SequodeOperationsKit';
        $model->xhr = (object) null;
        $model->xhr->operations = 'SQDE_SequodeOperationsXHR';
        $model->xhr->forms = 'SQDE_SequodeFormsXHR';
        $model->xhr->cards = 'SQDE_SequodeCardsXHR';
        $model->rest = (object) null;
        $model->rest->operations = 'SQDE_SequodeOperationsRest';
        $model->rest->downloads = 'SQDE_SequodeDownloadsRest';
		return $model;
	}
}