<?php

namespace Sequode\Application\Modules\Console\Traits\Routes;

use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

trait JavascriptTrait {

	public static function js($closure = true,$force_SSL = true){
        
        $module = static::$module;
        $files = $module::model()->assets->javascript;
		
        header('Content-type: application/javascript');
        echo "\n";
        if($closure == true){
            echo '!function() {';
        }
        if($force_SSL == true){
            //echo DOMElementKitJS::forceSSL();
        }
		foreach($files as $file){
			echo file_get_contents($file,true);
			echo "\n";
		}
        if($closure != false){
            echo '}();';
        }
	
    }
    
}