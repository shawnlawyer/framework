<?php
namespace Sequode\Model\Database;

use Sequode\Model\Application\Configuration;
use Sequode\Controller\Database\MySQL;

class ResourceConnection{
	public static function model(){
        static $model;
        if(!is_object($model)){
            $model = (object) null;
            if(isset(Configuration::model()->database)){
                foreach(Configuration::model()->database as $member => $database){
                    $model->{$member. '_database'} = new MySQL($database->host,$database->user,$database->password,$database->name);
                }
            }
        }
        return $model;
    }
}