<?php
namespace Sequode;
header('Access-Control-Allow-Origin: *');
define('MACHINE_TEMP_DIRECTORY',((!empty($_SERVER['WINDIR'])) ? '%TEMP%' : '/tmp'));
define('SEQUODE_DIRECTORY', MACHINE_TEMP_DIRECTORY . DIRECTORY_SEPARATOR . 'sequode');
define('SEQUODE_API_HOST', $_ENV['SEQUODE_API_HOST'] . $_ENV['SEQUODE_API_TOKEN'] . '/');
define('SEQUODE_API_TOKEN', $_ENV['SEQUODE_API_TOKEN']);
if(!is_dir( SEQUODE_DIRECTORY ) && !mkdir( SEQUODE_DIRECTORY , 0777, true)) {

    echo 'no cache directory:' . SEQUODE_DIRECTORY;

}

function Sequode($token = false) {

    return new Sequode($token);

}

class Sequode{

    private $package = false;
    private $origin_host = SEQUODE_API_HOST;
    private $name = null;
    private $token = null;
    public function __construct($token = false){
        if($token != false && !class_exists($token)){
            $this->token = $token;
            $this->loadPackage($token, true);
        }
        return ($this->package != false && $this->package == $token) ? $this : false;
    }

    public static function requestPieces($mode = '', $line = ''){
        switch($mode){
            case 'line':
                $request_pieces = $line;
                $request_pieces = explode('?',$request_pieces)[0];
                $request_pieces = explode('#',$request_pieces)[0];
                return (strpos($request_pieces, '/') === false) ? [$request_pieces] : explode('/',$request_pieces);
            case 'console':
                $request_pieces = fgets(STDIN);
                $request_pieces = explode('?',$request_pieces)[0];
                $request_pieces = explode('#',$request_pieces)[0];
                return (strpos($request_pieces, '/') === false) ? [$request_pieces] : explode('/',$request_pieces);
            case 'cli':
                global $argv;
                $request_pieces = $argv[1];
                $request_pieces = explode('?',$request_pieces)[0];
                $request_pieces = explode('#',$request_pieces)[0];
                return (strpos($request_pieces, '/') === false) ? [$request_pieces] : explode('/',$request_pieces);
            case 'http':
            default:
                $request_pieces = $_SERVER['REQUEST_URI'];
                $request_pieces = explode('?',$request_pieces)[0];
                $request_pieces = explode('#',$request_pieces)[0];
                $request_pieces = explode('/',$request_pieces);
                array_shift($request_pieces);
                return $request_pieces;
        }
    }
    public function console($show_welcome = true){
        if($show_welcome == true){
            echo str_replace('_',' ', $this->name) .' Console:';
        }
        echo "\n:";
        $line = fgets(STDIN);
        if(strpos(trim($line),'origin/')  === 0){
            $url = $this->origin_host . '/' . substr_replace(trim($line), '', 0 ,strlen('origin/'));
            $this->apiRequest($url, true);
        }elseif(strpos(trim($line),'-b')  === 0){
            $this->installPackage($this->token);
            echo "Rebuilt " . str_replace('_',' ', $this->name) ."\n";
            echo "Must restart\n";
            exit;
        }elseif(strpos(trim($line),'end')  === 0){
            echo "Take Care!\n";
            exit;
        }else{
            $this->expressRequest(self::requestPieces('line', $line) , true);
        }
        $this->console(false);
    }
    public function cli(){
        $this->expressRequest(self::requestPieces( __FUNCTION__ ) , true);
        exit;
    }
    public function line($line){
        $this->expressRequest(self::requestPieces( __FUNCTION__,$line) , true);
    }
    public function http(){
        $this->expressRequest(self::requestPieces( __FUNCTION__ ) , true);
        exit;
    }
    private function installPackage($token){
        $filepath = SEQUODE_DIRECTORY . DIRECTORY_SEPARATOR . $token.'.class.php';
        $url = $this->origin_host .'downloads/package/source/' . $token;
        echo 'Pulling from: ';
        echo $url . PHP_EOL;
        $read = fopen($url, "rb");
        if($read){
            $write = fopen($filepath, "wb");
            if($write){
                while(!feof($read)){
                    fwrite($write, fread($read, 1024 * 8 ), 1024 * 8 );
                }
                fclose($write);
            }
            fclose($read);
        }
    }

    private function apiRequest($url){
        $read = fopen($url, "rb");
        if($read){
            while(!feof($read)){
                echo fread($read, 1024 * 8 );
            }
            fclose($read);
        }
    }
    private function loadPackage($token, $try_install = false){
        if(!class_exists($token)){
            $filepath = SEQUODE_DIRECTORY . DIRECTORY_SEPARATOR . $token.'.class.php';
            if(!file_exists($filepath)){
                if($try_install == true){
                    $this->installPackage($token);
                    $this->loadPackage($token);
                }
            } else {
                include($filepath);
            }
        }

        if(class_exists($token)){
            $this->package = $token;
            $this->name = $token;
            $this->token = $token;
        }
    }
    private function expressRequest($request_pieces, $buffer_output=true){
        $output_as = '';
        $_package = $this->package;
        if(isset($request_pieces[0]) && trim($request_pieces[0]) != ''){
            $route = str_replace('\s','_',str_replace('%20','_',str_replace(' ','_',trim($request_pieces[0]))));
            if(strpos($route,'.exp') !== false){
                $output_as = 'exp';
                $route = str_replace('.exp', '', $route);
            }elseif(strpos($route,'.json') !== false){
                $output_as = 'json';
                $route = str_replace('.json', '', $route);
            }
            array_shift($request_pieces);

            $_sm = $_package::node($route, 'name');
            if($_sm === false){
                echo 'Unknown: '.$route;
                return;
            }
            $parameters = [];
            if(isset($request_pieces[0])){
                $parameters = $request_pieces;
            }
            unset($request_pieces);
        }else{
            $_sm = $_package::node($_package::$index, 'id');
            if(!$_sm){
                return;
            }
        }
        foreach($_sm->i as $m => $v){
            if(isset($parameters[0])){
                $_sm->i->$m = $parameters[0];
                array_shift($parameters);
            }
        }
        foreach($_sm->p as $m => $v){
            if($m == 'Run_Process'){ continue; }
            if(isset($parameters[0])){
                $_sm->p->$m = $parameters[0];
                array_shift($parameters);
            }
        }
        $_sm->p->Run_Process = true;
        if($buffer_output == true){
            ob_start();
            $_package::express($_sm);
            $_sm->o->Headers_List = headers_list();
            $_sm->o->Output_Buffer = ob_get_contents();
            ob_end_clean();

            switch($output_as){
                case 'json':
                    echo json_encode($_sm->o);
                    break;
                case 'exp':
                    if(strlen($_sm->o->Output_Buffer) > 0){
                        $lines = explode('
',$_sm->o->Output_Buffer);
                        foreach($lines as $line){
                            $this->line($line);
                        }
                    }
                    break;
                default:
                    if(strlen($_sm->o->Output_Buffer) > 0){
                        echo $_sm->o->Output_Buffer;
                    }else{
                        echo json_encode($_sm->o);
                    }
                    break;
            }
        }else{
            $_package::express($_sm);
        }
    }
}