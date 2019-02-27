<?php

namespace Sequode\Application\Modules\Console\Traits\Routes;

use Sequode\Controller\Application\XMLHTTPRequest;

trait XHRTrait {
	
	public static function xhr(){
        return XMLHTTPRequest::handle();
    }
    
}

