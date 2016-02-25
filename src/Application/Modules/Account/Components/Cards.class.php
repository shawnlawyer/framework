<?php

namespace Sequode\Application\Modules\Account\Components;

use Sequode\Application\Modules\Session\Store as SessionStore;

use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\Card\CardKit as CardKit;
use Sequode\Component\Form\Form as FormComponent;

use Sequode\Application\Modules\Account\Module;

class Cards {
    
    public static $module = Module::class;
    
    public static function menu(){
        
        $_o = (object) null;
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'user-icon-background';
        $_o->menu = (object) null;
        $_o->menu->position_adjuster =  'automagic-card-menu-right-side-adjuster';
        $_o->menu->items =  self::menuItems();
        
        return $_o;
        
    }
    
    public static function menuItems(){
        
        $_o = array();
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Account Details','cards/account/details');
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Update Password','cards/account/updatePassword');
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Update Email','cards/account/updateEmail');
        
        return $_o;
        
    }
    public static function details(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        
        $_model = forward_static_call_array(array($modeler,'model'),array());
        
        $_o = (object) null;
        $_o->head = 'Account Detail';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'user-icon-background';
        $_o->body[] = CardKitHTML::sublineBlock('Email');
        $_o->body[] = $_model->email;
        if(\Sequode\Application\Modules\Account\Authority::isSystemOwner()){
            $_o->body[] = CardKitHTML::modelId($_model);
        }
        return $_o;
    }
    
    public static function updatePassword(){
        
        $module = static::$module;
        $dialogs = $module::model()->components->dialogs;
        $dialog = forward_static_call_array(array($dialogs, __FUNCTION__), array());
        
        $step = $dialog->steps[$dialog_store->step];
        $_o = (object) null;
        $_o->icon_background = 'users-icon-background';
        $_o->size = 'small';
        if($dialog_store->step != 0){
            $_o->menu = (object) null;
            $_o->menu->items = array();
            $_o->menu->items[] = CardKit::onTapEventsXHRCallMenuItem('Start Over','operations/account/' . __FUNCTION__,array(FormComponent::jsQuotedValue('{"reset":"1"}')));
        }
        $_o->head = 'Account Password';
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
            $_o->body[] = CardKit::resetDialogButton('operations/account/' . __FUNCTION__);
        }
        
        $_o->body[] = (object) array('js' => '$(\'.focus-input\').focus(); $(\'.focus-input\').select();');
        
        return $_o;    
    }
    
    public static function updateEmail(){
        
        $module = static::$module;
        $dialogs = $module::model()->components->dialogs;
        $dialog = forward_static_call_array(array($dialogs, __FUNCTION__), array());
        
        $dialog_store = SessionStore::get($dialog->session_store_key);
        $step = $dialog->steps[$dialog_store->step];
        
        $_o = (object) null;
        $_o->icon_background = 'users-icon-background';
        $_o->size = 'small';
        
        if($dialog_store->step != 0){
            
            $_o->menu = (object) null;
            $_o->menu->items = array();
            $_o->menu->items[] = CardKit::onTapEventsXHRCallMenuItem('Start Over','operations/account/' . __FUNCTION__,array(FormComponent::jsQuotedValue('{"reset":"1"}')));
        
        }
        
        $_o->head = 'Account Email';
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
        
        if($dialog_store->step > 0){
            $_o->body[] = CardKit::resetDialogButton('operations/account/' . __FUNCTION__);
        }
        
        $_o->body[] = (object) array('js' => '$(\'.focus-input\').focus(); $(\'.focus-input\').select();');
        
        return $_o;
        
    }
}