<?php

namespace Sequode\Application\Modules\Console\Traits\Routes;

use Sequode\Controller\Application\Request\Site\XHR as SiteXHRRequest;

trait XHRTrait {
	
	public static function xhr(){
        return SiteXHRRequest::handle();
    }
    
}

