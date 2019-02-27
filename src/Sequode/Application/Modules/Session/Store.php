<?php

namespace Sequode\Application\Modules\Session;

use Sequode\Foundation\Traits\StaticStoreTrait;
use Sequode\Application\Modules\Session\Traits\Operations\IsSetGetClearSessionStore;
use Sequode\Application\Modules\Traits\Modeler\ActiveModelTrait;

class Store {
    
    use StaticStoreTrait,
        IsSetGetClearSessionStore,
        /*
         *  with ActiveModelTrait, i think i was trying to use a static storage like a session and
         * then attach a model that it might be saved to a persistant model in the db in stead of
         * having to loosly use them together with no logically awareness other then what's hard coded.
         *
         * the trait's method's available to this class are not used.
         * */
        ActiveModelTrait;
    public static $operations = Operations::class;
}