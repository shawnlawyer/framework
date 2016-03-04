<?php

namespace Sequode\Application\Modules\AuthConsole\Components;

use Sequode\Application\Modules\AuthConsole\Module;

class Cards {
    
    public static $module = Module::class;
    
    
    public static function feature(){
        $_o = (object) null;
        $_o->head = 'Sequencer for PHP';
        $_o->component_seperator = '';
        
        $html = $js = array();
        $_o->body = array();
        $html[] = '<div class="card-textblock card-text"></div>';
        
        $html[] = '<div class="subline">Project Introduction</div>';
        $html[] = '<div class="card-textblock card-text">';
        $html[] = 'Sequencer is a tool for process creation and management in PHP. In the current state, the tool allows you to arrange PHP SPL code elements into processes without having to write the actual code.';
        $html[] = '</div>';
        
        $html[] = '<div class="card-textblock card-text"></div>';
        
        $html[] = '<div class="subline ">Sequences of Programming Logic</div>';
        $html[] = '<div class="card-textblock card-text">';
        $html[] = 'The tool is <span class="subline ">not</span> a code replacement tool. The tool abstracts the code elements into simple visual components and allows you to create software visually by arraging the visual elements on a stage or programmatically via a REST API.';
        $html[] = '</div>';
        
        $html[] = '<div class="card-textblock card-text"></div>';
        
        $html[] = '<div class="subline ">Future</div>';
        $html[] = '<div class="card-textblock card-text">';
        $html[] = 'The end goal of this project is to allow any PHP library to be arranged, not just the SPL.';
        $html[] = '</div>';
        
        $html[] = '<div class="card-textblock card-text"></div>';
        
        $_o->body[] = (object) array('html' => implode('',$html));
        return $_o;
    }
}