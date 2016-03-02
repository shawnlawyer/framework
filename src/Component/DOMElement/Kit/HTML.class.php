<?php
namespace Sequode\Component\DOMElement\Kit;
class HTML {
    public static function headTags(){
        $html = array();
        $html[] ='<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        $html[] ='<meta charset="utf-8">';
        $html[] ='<meta name="viewport" content="width=device-width, initial-scale=1">';
        $html[] ='<title>Sequode</title>';
        $html[] ='<meta name="description" content="Sequode visual programming tool." ';
        $html[] ='<link rel="icon" href="/favicon.ico" type="image/x-icon" />';
        $html[] ='<link href="/application.css" rel="stylesheet" type="text/css">';
        $html[] ='<script src="/vendor.js" defer="defer"></script>';
        $html[] ='<script src="/application.js" defer="defer"></script>';
        $html[] ='<!--[if lt IE 9]>';
        $html[] ='<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>';
        $html[] ='<![endif]-->';
        return implode('',$html);
	}
    public static function page($body_contents=''){
        $html = array();
        $html[] ='<!doctype html>';
        $html[] ='<html>';
        $html[] ='<head>';
        $html[] = self::headTags();
        $html[] ='</head>';
        $html[] ='<body>';
        $html[] =$body_contents;
        $html[] ='</body>';
        $html[] ='</html>';
        return implode('',$html);
	}
}