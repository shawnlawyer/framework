<?php

namespace Sequode\Model\Application;

use Sequode\Model\Module\Registry as ModuleRegistry;

class Routeable
{
    public static function model()
    {

        $modules = ModuleRegistry::modules();

        $_o = (object)[
            'routes' => [],
            'xhr' => [],
            'rest' => [],
            'collections' => []
        ];

        foreach ($modules as $module) {

            if (isset($module::model()->routes)) {
                foreach ($module::model()->routes as $key => $class) {
                    $_o->routes[] = (object)[
                        'module' => $module::$registry_key,
                        'type' => $key,
                        'class' => $class
                    ];
                }
            }

            if (isset($module::model()->xhr)) {
                foreach ($module::model()->xhr as $key => $class) {
                    $_o->xhr[] = (object) [
                        'module' => $module::$registry_key,
                        'type'   => $key,
                        'class'  => $class
                    ];
                }
            }

            if (isset($module::model()->rest)) {
                foreach ($module::model()->rest as $key => $class) {
                    $_o->rest[] = (object)[
                        'module' => $module::$registry_key,
                        'type' => $key,
                        'class' => $class
                    ];
                }
            }

            if (isset($module::model()->collections)) {
                $_o->collections[] = (object)[
                    'module' => $module::$registry_key,
                    'type' => 'http',
                    'class' => $class
                ];
            }
        }
        return $_o;
    }
}