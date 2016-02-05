<?php

namespace Sequode\Application\Modules\Console\Routes;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Model\Application\Routes as ApplicationRoutes;
use Sequode\Model\Application\Runtime as RuntimeModel;
use Sequode\Controller\Application\HTTPRequest\XHR as XHRRequest;
use Sequode\Component\DOMElement\Kit\HTML as DOMElementKitHTML;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

class Routes{
	public static $merge = false;
	public static $routes = array(
		'xhr',
		'routes',
		'cards',
		'operations',
		'collections',
		'application.css',
		'3rdParty.js',
        'application.js'
	);
	public static $routes_to_methods = array(
		'xhr' => 'xhr',
		'routes' => 'routes',
		'cards' => 'cards',
		'operations' => 'operations',
		'collections' => 'collections',
		'application.css' => 'css',
		'3rdParty.js' => 'vendorJS',
		'application.js' => 'js'
	);
    public static function index(){
        $console = 'Auth';
        if(\SQDE_UserAuthority::isAuthenticated()){
            $console = 'Sequode';
        }
        \SQDE_Session::set('console',$console);
		echo DOMElementKitHTML::page();
		exit;
	}
	public static function vendorJS(){
        if(!\SQDE_Session::is('console')){return;}
		$files = array('js/jquery-2.1.4.js','js/kinetic_v5_1_0.js');
		header('Content-type: application/javascript');
		foreach($files as $file){ 
			echo file_get_contents($file,true);
			echo "\n";
		}
	}
	public static function css(){
        if(!\SQDE_Session::is('console')){return;}
		$files = array(
        'css/SQDE_automagic_cards.css',
        'css/SQDE_automagic_content.css',
        'css/SQDE_automagic_data.css',
        'css/SQDE_automagic_forms.css',
        'css/SQDE_automagic_layout.css',
        'css/SQDE_btn.css',
        'css/SQDE_containers.css',
        'css/SQDE_globals.css',
        'css/SQDE_icons.css',
        'css/SQDE_shortcuts.css',
        'css/SQDE_text.css',
        'css/sequode.css',
        'css/SQDE_client.css'
        );
        header("Content-type: text/css", true);
        echo '@charset "utf-8";';
        echo "\n";
		foreach($files as $file){
			echo file_get_contents($file,true);
			echo "\n";
		}
	}
	public static function js($closure = true,$force_SSL = true){
        if(!\SQDE_Session::is('console')){return;}
        switch(\SQDE_Session::get('console')){
            case 'Sequode':
                $files = array(
                    'js/SQDE_Configuration.js',
                    'js/SQDE_SymbolsKit.js',
                    'js/SQDE_BaseKit.js',
                    'js/SQDE_EventsKit.js',
                    'js/SQDE_ShapesKit.js',
                    'js/SQDE_CardsKit.js',
                    'js/SQDE_Cards.js',
                    'js/SQDE_XHR.js',
                    'js/SQDE_Model.js',
                    'js/SQDE_ModelEnds.js',
                    'js/SQDE_Sequencer.js',
                    'js/SQDE_SequencerPalette.js',
                    'js/SQDE_CollectionCards.js',
                    'js/SQDE_SequodeConsoleRegistry.js',
                    'js/SQDE_SequodeConsole.js',
                    'js/sequode-main.js'
                );
                break;
            case 'Auth':
                $files = array(
                    'js/SQDE_XHR.js',
                    'js/SQDE_AuthConsole.js',
                    'js/SQDE_AuthConsoleRegistry.js',
                    'js/auth-main.js'
                );
                break;
            
        }
		header('Content-type: application/javascript');
        echo '/* Copyright (c) 2006-2015 Shawn Thomas Lawyer - shawnlawyer@gmail.com. All Rights Reserved. */';
        echo "\n";
        if($closure == true){
            echo '!function() {';
        }
        if($force_SSL == true && $_SERVER['WORKFLOW_ENVIRONMENT'] != 'development'){
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
	public static function xhr(){
        if(!\SQDE_Session::is('console')){return;}
		$call = false;
		$args = array();

		if(isset($_POST['sub']) && !empty($_POST['sub'])){
			$call = $_POST['sub'];
		}elseif(isset($_GET['sub']) && !empty($_GET['sub'])){
			$call = $_GET['sub'];
		}
        
        $call_pieces = explode('/',$call);
        if(!isset($call_pieces[1])){
            return;
        }
        if(!isset($call_pieces[2])){
            return;
        }
        $package = ucfirst(strtolower($call_pieces[1]));
        if(!ModuleRegistry::is($package)){
            return;
        }
        $request_type = $call_pieces[0];
        if(!isset(ModuleRegistry::model($package)->xhr->$request_type)){
            return;
        }
        $routes_class = ModuleRegistry::model($package)->xhr->$request_type;
        if(!in_array($call_pieces[2], ApplicationRoutes::routes('\\'.$routes_class))){
            return;
        }
        $route = ApplicationRoutes::route('\\'.$routes_class, $call_pieces[2]);
        
		if(isset($_POST['args']) && !empty($_POST['args'])){
            if( 500000 < strlen(http_build_query($_POST))){ return; }
			$args = $_POST['args'];
            
		}elseif(isset($_GET['args']) && !empty($_GET['args'])){
            if( 500000 < strlen(http_build_query($_GET))){ return; }
			$args = $_GET['args'];
		}

        echo XHRRequest::call('\\'.$routes_class, $route, $args);
        return true;
    }
	public static function collections($collection='collections', $key = null){
        if(!\SQDE_Session::is('console')){return;}
        switch(\SQDE_Session::get('console')){
            case 'Sequode':
                $collections = array('my_sequodes', 'sequode_favorites', 'palette', 'sequodes', 'tokens', 'packages');
                break;
        }
        

        switch($collection){
			case 'my_sequodes':
                \SQDE_SequodeCollections::owned();
                return;
			case 'packages':
                \SQDE_PackageCollections::owned();
                return;
			case 'tokens':
                \SQDE_TokenCollections::owned();
                return;
			case 'sequode_search':
                \SQDE_SequodeCollections::search();
                return;
			case 'user_search':
                \Sequode\Application\Modules\User\Routes\Collections::search();
                return;
			case 'session_search':
                \SQDE_SessionCollections::search();
                return;
			case 'package_search':
                \SQDE_PackageCollections::search();
                return;
			case 'token_search':
                \SQDE_TokenCollections::search();
                return;
			case 'sequode_favorites':
                \SQDE_SequodeCollections::favorited();
                return;
			case 'palette':
                \SQDE_SequodeCollections::palette();
                return;
			case 'sequodes':
                \SQDE_SequodeCollections::main($key);
                return;
            default:
			case 'collections':
                echo '{';
                echo  "\n";
                foreach($collections as $loop_key => $collection){
                    if($loop_key != 0){echo ",\n";}
                    echo '"'.$collection.'":';
                    echo self::collections($collection);
                }
                
                echo  "\n";
                echo '}';
                return;
		}
	}
}