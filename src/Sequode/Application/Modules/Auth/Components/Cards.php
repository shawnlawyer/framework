<?php

namespace Sequode\Application\Modules\Auth\Components;

use Sequode\Application\Modules\Session\Store as SessionStore;

use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\Card\Kit as CardKit;
use Sequode\Component\Form as FormComponent;

use Sequode\Application\Modules\Auth\Module;

class Cards {
    
    public static $module = Module::class;
    const Module = Module::class;
    
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

        extract((static::Module)::variables());

        $_o = [];
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Login', $module::xhrCardRoute('login'));

        return $_o;
    }

    public static function login(){

        extract((static::Module)::variables());

        $dialog = forward_static_call_array([$component_dialogs, __FUNCTION__], []);
        
        if(!SessionStore::is($dialog->session_store_key)){
            SessionStore::set($dialog->session_store_key, $dialog->session_store_setup);
        }
        
        $dialog_store = SessionStore::get($dialog->session_store_key);
        $step = $dialog->steps[$dialog_store->step];
        
        $_o = (object) null;
        $_o->dom_id = str_replace("/","_", $module::xhrCardRoute(__FUNCTION__));
        $_o->icon_background = 'users-icon-background';
        $_o->size = 'small';
        
        if($dialog_store->step != 0){
            $_o->menu = (object) null;
            $_o->menu->items = [];
            $_o->menu->items[] = CardKit::onTapEventsXHRCallMenuItem('Reset', $module::xhrOperationRoute(__FUNCTION__), [FormComponent::jsQuotedValue('{"reset":"1"}')]);
        }
        
        $_o->head = 'Authentication';
        $_o->body = [''];
        
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
            $_o->body[] = CardKit::resetDialogButton( $module::xhrOperationRoute(__FUNCTION__));
        }
        
        $_o->body[] = (object) ['js' => '$(\'.focus-input\').focus(); $(\'.focus-input\').select();'];
        
        return $_o;

    }  
}