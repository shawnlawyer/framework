<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Model\Module\Registry as ModuleRegistry;

trait ORMModelFavoritedModuleModelsTrait {

    public static function emptyFavorites($registry_key, $_model = null){

        $modeler = static::$modeler;
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $favorites = $modeler::model()->favorites;
        $favorites[$registry_key] = [];

        $modeler::model()->favorites = $favorites;
        $modeler::model()>save();
        return $modeler::model();
    }

    public static function favorite($registry_key, $favorite_model = null, $_model = null){

        $modeler = static::$modeler;

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        extract((ModuleRegistry::module($registry_key))::variables(), EXTR_PREFIX_ALL, 'favorited');

        forward_static_call_array([$favorited_modeler, 'model'], ($favorite_model == null) ? [] : [$favorite_model]);

        $favorites = $modeler::model()->favorites;
        if(!empty($favorites[$registry_key])){
            $favorites[$registry_key][] = $favorited_modeler::model()->id;
        }else{
            $favorites[$registry_key] = [$favorited_modeler::model()->id];
        }
        $favorites[$registry_key] = array_unique($favorites[$registry_key]);

        $modeler::model()->favorites = $favorites;
        $modeler::model()->save();

        return $modeler::model();

    }

    public static function unfavorite($registry_key, $favorite_model = null, $_model = null){

        $modeler = static::$modeler;

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        extract((ModuleRegistry::module($registry_key))::variables(), EXTR_PREFIX_ALL, 'favorited');

        forward_static_call_array([$favorited_modeler, 'model'], ($favorite_model == null) ? [] : [$favorite_model]);

        $favorites = $modeler::model()->favorites;

        $array = [];
        foreach($favorites[$registry_key] as $value){
            if(intval($value) != $favorited_modeler::model()->id){
                $array[] = $value;
            }
        }
        $favorites[$registry_key] = $array;
        $modeler::model()->favorites = $favorites;
        $modeler::model()->save();

        return $modeler::model();
    }
}