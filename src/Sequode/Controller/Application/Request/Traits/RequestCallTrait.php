<?php
namespace Sequode\Controller\Application\Request\Traits;

trait RequestCallTrait  {

	public static function call($class, $method, $parameters = null){
		return forward_static_call_array([$class, $method], ($parameters === null) ? [] : $parameters);
    }
}