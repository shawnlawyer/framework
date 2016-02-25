<?php

namespace Sequode\Application\Modules\Auth\Components;

use Sequode\Application\Modules\Session\Store as SessionStore;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\Card\CardKit as CardKit;
use Sequode\Component\Form\Form as FormComponent;

use Sequode\Application\Modules\Auth\Module;

class Cards {
    
    public static $module = Module::class;
    
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
       
        $module = static::$module;
        $dialogs = $module::model()->components->dialogs;
        $dialog = forward_static_call_array(array($dialogs, __FUNCTION__), array());
        if(!SessionStore::is($dialog->session_store_key)){
            SessionStore::set($dialog->session_store_key, $dialog->session_store_setup);
        }
        $dialog_store = SessionStore::get($dialog->session_store_key);
        $step = $dialog->steps[$dialog_store->step];
        $_o = (object) null;
        $_o->icon_background = 'users-icon-background';
        $_o->size = 'small';
        if($dialog_store->step != 0){
            $_o->menu = (object) null;
            $_o->menu->items = array();
            $_o->menu->items[] = CardKit::onTapEventsXHRCallMenuItem('Reset', 'operations/auth/' . __FUNCTION__, array(FormComponent::jsQuotedValue('{"reset":"1"}')));
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
                $_o->body = array_merge($_o->body, ModuleForm::render($module::$registry_key, $form));
            }
        }
        if($dialog_store->step != 0){
            $_o->body[] = CardKit::resetDialogButton('operations/auth/' . __FUNCTION__);
        }
        $_o->body[] = (object) array('js' => '$(\'.focus-input\').focus(); $(\'.focus-input\').select();');
        return $_o;    
    }  
}