<?php

namespace Sequode\Application\Modules\Console\Traits\Routes;

trait VendorJavascriptTraits {

	public static function vendorJS(){
        
        $files = array('js/jquery-2.1.4.js','js/kinetic_v5_1_0.js');
		header('Content-type: application/javascript');
		foreach($files as $file){ 
			echo file_get_contents($file,true);
			echo "\n";
		}
        
	}
    
}