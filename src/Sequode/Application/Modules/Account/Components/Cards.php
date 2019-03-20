<?php

namespace Sequode\Application\Modules\Account\Components;

use Sequode\Application\Modules\Session\Store as SessionStore;

use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\Card\Kit as CardKit;
use Sequode\Component\Form as FormComponent;

use Sequode\Application\Modules\Account\Module;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;

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

        $module = static::$module;
        $_o = [];
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Account Details', $module::xhrCardRoute('details'));
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Update Password', $module::xhrCardRoute('updatePassword'));
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Update Email', $module::xhrCardRoute('updateEmail'));
        
        return $_o;
        
    }

    public static function modelOperationsMenuItems($filter='', $_model = null){
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $_model = forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
        $items = [];
        $items[] = CardKit::onTapEventsXHRCallMenuItem('Detail', $module::xhrCardRoute('detail'), [$_model->id]);
        $items[] = CardKit::onTapEventsXHRCallMenuItem('Account Password', $module::xhrCardRoute('updatePassword'), [$_model->id]);
        $items[] = CardKit::onTapEventsXHRCallMenuItem('Account Email', $module::xhrCardRoute('updateEmail'), [$_model->id]);

        return $items;
    }
    public static function details(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;
        
        $_model = forward_static_call_array([$modeler,'model'],[]);
        
        $_o = (object) null;
        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'users',
            'node' => $_model->id
        ];
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        $_o->head = 'Account Detail';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'user-icon-background';
        $_o->body[] = CardKitHTML::sublineBlock('Email');
        $_o->body[] = $_model->email;

        if(AccountAuthority::isSystemOwner()){
            $_o->body[] = CardKitHTML::modelId($_model);
        }
        return $_o;
    }
    
    public static function updatePassword(){
        
        $module = static::$module;
        $dialogs = $module::model()->components->dialogs;
        $dialog = forward_static_call_array([$dialogs, __FUNCTION__], []);
        
        if(!SessionStore::is($dialog->session_store_key)){
            SessionStore::set($dialog->session_store_key, $dialog->session_store_setup);
        }
        
        $dialog_store = SessionStore::get($dialog->session_store_key);
        $step = $dialog->steps[$dialog_store->step];
        
        $_o = (object) null;
        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__)
        ];
        $_o->icon_background = 'users-icon-background';
        $_o->size = 'small';
        
        if($dialog_store->step != 0){
            
            $_o->menu = (object) null;
            $_o->menu->items = [];
            $_o->menu->items[] = CardKit::onTapEventsXHRCallMenuItem('Start Over', $module::xhrOperationRoute(__FUNCTION__), [FormComponent::jsQuotedValue('{"reset":"1"}')]);
        
        }
        
        $_o->head = 'Account Password';
        $_o->body = [''];
        $js = [];
        $_o->body[] = (object) ['js' => implode('', $js)];
        if(AccountAuthority::isSystemOwner()){
            $_o->body[] = CardKitHTML::modelId($_model);
        }
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
    
    public static function updateEmail(){
        
        $module = static::$module;
        $dialogs = $module::model()->components->dialogs;
        $dialog = forward_static_call_array([$dialogs, __FUNCTION__], []);
        
        
        if(!SessionStore::is($dialog->session_store_key)){
            SessionStore::set($dialog->session_store_key, $dialog->session_store_setup);
        }
        
        $dialog_store = SessionStore::get($dialog->session_store_key);
        $step = $dialog->steps[$dialog_store->step];
        
        $_o = (object) null;
        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__)
        ];
        $_o->icon_background = 'users-icon-background';
        $_o->size = 'small';
        
        if($dialog_store->step != 0){
            
            $_o->menu = (object) null;
            $_o->menu->items = [];
            $_o->menu->items[] = CardKit::onTapEventsXHRCallMenuItem('Start Over', $module::xhrOperationRoute(__FUNCTION__), [FormComponent::jsQuotedValue('{"reset":"1"}')]);
        
        }
        
        $_o->head = 'Account Email';
        $_o->body = [''];

        $js = [];
        $_o->body[] = (object) ['js' => implode('', $js)];
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
            
            $_o->body[] = CardKit::resetDialogButton( $module::xhrOperationRoute(__FUNCTION__));
            
        }
        
        $_o->body[] = (object) ['js' => '$(\'.focus-input\').focus(); $(\'.focus-input\').select();'];
        
        return $_o;
        
    }
}