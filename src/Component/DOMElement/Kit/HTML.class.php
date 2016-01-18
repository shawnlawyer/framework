<?php
namespace Sequode\Component\DOMElement\Kit;
class HTML {
    public static function loginIframe(){
        return '<iframe style="visibility:hidden;display:none;" height="0" width="0" src="http://sequo.de/setSessionCookie/'.\SQDE_Session::id().'"></iframe>';
    }    
    public static function headTags(){
        $html = array();
        $html[] ='<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        $html[] ='<meta charset="utf-8">';
        $html[] ='<meta name="viewport" content="width=device-width, initial-scale=1">';
        $html[] ='<title>'.\Sequode\Model\Application::model()->site->display_name.'</title>';
        $html[] ='<meta name="description" content="Sequode visual programming tool." ';
        $html[] ='<link rel="icon" href="/favicon.ico" type="image/x-icon" />';
        $html[] ='<link href="/application.css" rel="stylesheet" type="text/css">';
        $html[] ='<script src="/3rdParty.js" defer="defer"></script>';
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