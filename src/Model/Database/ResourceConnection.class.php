<?php
namespace Sequode\Model\Database;

class ResourceConnection{
	public static function model(){
        static $model;
        if(!is_object($model)){
            $model = (object) null;
            if(isset(\Sequode\ApplicationConfiguration::model()->database)){
                foreach(\Sequode\ApplicationConfiguration::model()->database as $member => $database){
                    $model->{$member. '_database'} = new \Sequode\Controller\Database\MySQL($database->host,$database->user,$database->password,$database->name);
                }
            }
        }
        return $model;
    }
}