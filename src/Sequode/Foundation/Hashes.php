<?php

namespace Sequode\Foundation;

class Hashes {
    
	public static function uniqueHash($seed='',$prefix='SQDE'){
		$time = explode(' ', microtime());
        $time = $time[0] + $time[1];
		return $prefix.md5($time.$seed);
	}
	public static function generateHash($text='', $salt = null){
        $salt = ($salt === null) ? substr(md5(uniqid(rand(), true)), 0, 25) : substr($salt, 0, 25);
        return $salt . sha1($salt . $text);
    }
}