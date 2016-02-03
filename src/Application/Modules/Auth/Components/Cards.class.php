<?php

namespace Sequode\Application\Modules\Auth\Components;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\Card\CardKit as CardKit;

class SQDE_AuthCardObjects {
    public static $package = 'Auth';
    public static function menu(){
        $_o = (object) null;
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'settings-icon-background';
        $_o->menu = (object) null;
        $_o->menu->position_adjuster =  'automagic-card-menu-right-side-adjuster';
        $_o->menu->items = self::menuItems();
        return $_o;
    }
    public static function menuItems(){
        $_o = array();
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Login','cards/auth/login');
        return $_o;
    }
   public static function login(){
        $dialog = ModuleRegistry::model(static::$package)->xhr->dialogs[__FUNCTION__];
        if(!SQDE_Session::is($dialog['session_store_key'])){
            SQDE_Session::set($dialog['session_store_key'], $dialog['session_store_setup']);
        }
        $dialog_store = SQDE_Session::get($dialog['session_store_key']);
        $step = $dialog['steps'][$dialog_store->step];
        $_o = (object) null;
        $_o->icon_background = 'users-icon-background';
        $_o->size = 'small';
        if($dialog_store->step != 0){
            $_o->menu = (object) null;
            $_o->menu->items = array();
            $_o->menu->items[] = CardKit::onTapEventsXHRCallMenuItem('Reset','operations/auth/' . __FUNCTION__,array(Sequode\Component\Form\Form::jsQuotedValue('{"reset":"1"}')));
        }
        $_o->head = 'Authentication';
        $_o->body = array('');
        if(isset($step->content)){
            if(isset($step->content->head)){
                $_o->body[] = '<div class="subline">'.$step->content->head.'</div>';
            }
            if(isset($step->content->head)){
                $_o->body[] = $step->content->body;
            }
        }
        if(isset($step->forms)){
            foreach($step->forms as $form){
                $_o->body = array_merge($_o->body, ModuleForm::render(self::$package, $form));
            }
        }
        if($dialog_store->step != 0){
            $_o->body[] = CardKit::resetDialogButton('operations/auth/' . __FUNCTION__);
        }
        $_o->body[] = (object) array('js' => '$(\'.focus-input\').focus(); $(\'.focus-input\').select();');
        return $_o;    
    }  
}