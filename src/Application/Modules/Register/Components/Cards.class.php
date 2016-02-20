<?php

namespace Sequode\Application\Modules\Register\Components;

use Sequode\Application\Modules\Session\Store as SessionStore;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\Card\CardKit as CardKit;
use Sequode\Component\Form\Form as FormComponent;

class Cards {
    public static $module_registry_key = 'Register';
    public static function menu(){
        $_o = (object) null;
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'user-icon-background';
        $_o->menu = (object) null;
        $_o->menu->position_adjuster =  'automagic-card-menu-right-side-adjuster';
        $_o->menu->items = self::menuItems();
        return $_o;
    }
    public static function menuItems(){
        $_o = array();
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Signup','cards/register/signup');
        return $_o;
    }
    public static function signup(){
        $dialog = ModuleRegistry::model(static::$module_registry_key)->xhr->dialogs[__FUNCTION__];
        if(!SessionStore::is($dialog['session_store_key'])){
            SessionStore::set($dialog['session_store_key'], $dialog['session_store_setup']);
        }
        $dialog_store = SessionStore::get($dialog['session_store_key']);
        $step = $dialog['steps'][$dialog_store->step];
        $_o = (object) null;
        $_o->icon_background = 'users-icon-background';
        $_o->size = 'small';
        if($dialog_store->step != 0 && $dialog_store->step < count($dialog['steps']) - 1){
            $_o->menu = (object) null;
            $_o->menu->items = array();
            $_o->menu->items[] = CardKit::onTapEventsXHRCallMenuItem('Start Over','operations/account/' . __FUNCTION__,array(FormComponent::jsQuotedValue('{"reset":"1"}')));
        }
        $_o->head = ' Create Account';
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
        if($dialog_store->step != 0 && $dialog_store->step < count($dialog['steps']) - 1){
            $_o->body[] = CardKit::resetDialogButton('operations/register/' . __FUNCTION__);
        }
        $_o->body[] = (object) array('js' => '$(\'.focus-input\').focus(); $(\'.focus-input\').select();');
        return $_o;    
    }
}