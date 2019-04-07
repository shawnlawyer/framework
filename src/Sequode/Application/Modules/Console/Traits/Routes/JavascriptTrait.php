<?php

namespace Sequode\Application\Modules\Console\Traits\Routes;

use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

trait JavascriptTrait {

	public static function js($closure = true,$force_SSL = true){

        extract((static::Module)::variables());

        $files = $module::model()->assets->javascript;
		
        header('Content-type: application/javascript');
        echo "\n";
        if($closure == true){
            if($_ENV['FRONTEND_CLOSURE_ENABLED']) {
                echo '!function() {';
            }
        }
        if($force_SSL == true){
            if($_ENV['SSL_ENABLED']) {
                echo DOMElementKitJS::forceSSL();
            }
        }
		foreach($files as $file){
			echo file_get_contents($file,true);
			echo "\n";
		}
        if($closure != false){
            if($_ENV['FRONTEND_CLOSURE_ENABLED']) {
                echo '}();';
            }
        }
	
    }
    
}