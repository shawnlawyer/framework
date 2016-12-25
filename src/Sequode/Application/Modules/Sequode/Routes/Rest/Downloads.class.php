<?php

namespace Sequode\Application\Modules\Sequode\Routes\Rest;

class Downloads{
	public static function sdk(){
        header('Content-Type: text/plain', true);
        header('Content-Disposition: attachment; filename="SequodeSDK.class.php"');
		echo file_get_contents('php/SQDE_SequodeSDK.class.phps', true);
    }
}