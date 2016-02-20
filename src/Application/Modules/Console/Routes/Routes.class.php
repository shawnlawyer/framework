<?php

namespace Sequode\Application\Modules\Console\Routes;

use Sequode\Application\Modules\Session\Store as SessionStore;

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
        if(\Sequode\Application\Modules\Account\Authority::isAuthenticated()){
            $console = 'Sequode';
        }
        SessionStore::set('console',$console);
		echo DOMElementKitHTML::page();
		exit;
	}
	public static function vendorJS(){
        if(!SessionStore::is('console')){return;}
		$files = array('js/jquery-2.1.4.js','js/kinetic_v5_1_0.js');
		header('Content-type: application/javascript');
		foreach($files as $file){ 
			echo file_get_contents($file,true);
			echo "\n";
		}
	}
	public static function css(){
        if(!SessionStore::is('console')){return;}
		$files = array(
        'css/automagic_cards.css',
        'css/automagic_content.css',
        'css/automagic_data.css',
        'css/automagic_forms.css',
        'css/automagic_layout.css',
        'css/btn.css',
        'css/containers.css',
        'css/globals.css',
        'css/icons.css',
        'css/shortcuts.css',
        'css/text.css',
        'css/sequode.css',
        'css/client.css'
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
        if(!SessionStore::is('console')){return;}
        switch(SessionStore::get('console')){
            case 'Sequode':
                $files = array(
                    'js/Configuration.js',
                    'js/SymbolsKit.js',
                    'js/BaseKit.js',
                    'js/EventsKit.js',
                    'js/ShapesKit.js',
                    'js/CardsKit.js',
                    'js/Cards.js',
                    'js/XHR.js',
                    'js/Model.js',
                    'js/ModelEnds.js',
                    'js/Sequencer.js',
                    'js/SequencerPalette.js',
                    'js/CollectionCards.js',
                    'js/SequodeConsoleRegistry.js',
                    'js/SequodeConsole.js',
                    'js/sequode-main.js'
                );
                break;
            case 'Auth':
                $files = array(
                    'js/XHR.js',
                    'js/AuthConsole.js',
                    'js/AuthConsoleRegistry.js',
                    'js/auth-main.js'
                );
                break;
            
        }
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
    
	public static function xhr(){
        if(!SessionStore::is('console')){return;}
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
        $context = strtolower($call_pieces[1]);
        
        if(!in_array(ModuleRegistry::is($module))ModuleRegistry::is($module)){
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
        if(!SessionStore::is('console')){return;}
        switch(SessionStore::get('console')){
            case 'Sequode':
                $collections = array('my_sequodes', 'sequode_favorites', 'palette', 'sequodes', 'tokens', 'packages');
                break;
        }
        
        switch($collection){
			case 'packages':
                \Sequode\Application\Modules\Package\Routes\Collections\Collections::owned();
                return;
			case 'tokens':
               \Sequode\Application\Modules\Token\Routes\Collections\Collections::owned();
                return;
			case 'user_search':
                \Sequode\Application\Modules\User\Routes\Collections::search();
                return;
			case 'session_search':
                \Sequode\Application\Modules\Session\Routes\Collections\Collections::search();
                return;
			case 'package_search':
                \Sequode\Application\Modules\Package\Routes\Collections\Collections::search();
                return;
			case 'token_search':
                \Sequode\Application\Modules\Token\Routes\Collections\Collections::search();
                return;
			case 'palette':
                \Sequode\Application\Modules\Sequode\Routes\Collections\Collections::palette();
                return;
			case 'sequodes':
                \Sequode\Application\Modules\Sequode\Routes\Collections\Collections::main($key);
                return;
			case 'my_sequodes':
                \Sequode\Application\Modules\Sequode\Routes\Collections\Collections::owned();
                return;
			case 'sequode_favorites':
                \Sequode\Application\Modules\Sequode\Routes\Collections\Collections::favorited();
                return;
			case 'sequode_search':
                \Sequode\Application\Modules\Sequode\Routes\Collections\Collections::search();
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