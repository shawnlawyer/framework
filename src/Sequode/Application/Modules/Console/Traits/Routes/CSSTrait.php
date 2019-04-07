<?php

namespace Sequode\Application\Modules\Console\Traits\Routes;

trait CSSTrait {

	public static function css(){

        extract((static::Module)::variables());

        $files = $module::model()->assets->css;
        
        header("Content-type: text/css", true);
        echo '@charset "utf-8";';
        echo "\n";
		foreach($files as $file){
			echo file_get_contents($file,true);
			echo "\n";
		}

    }
    
}