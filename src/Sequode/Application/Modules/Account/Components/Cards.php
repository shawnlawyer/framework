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
    
    const Module = Module::class;

    const Tiles = ['details'];

    public static function menu(){
        
        $_o = (object) null;

        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'user-icon-background';
        $_o->menu = (object) null;
        $_o->menu->position_adjuster =  'automagic-card-menu-right-side-adjuster';
        $_o->menu->items =  self::menuItems();
        
        return $_o;
        
    }
    
    public static function menuItems($filters=[]){

        extract((static::Module)::variables());

        $_o = [];

        $_o[$module::xhrCardRoute('details')] = CardKit::onTapEventsXHRCallMenuItem('Account Details', $module::xhrCardRoute('details'));
        $_o[$module::xhrCardRoute('updatePassword')] = CardKit::onTapEventsXHRCallMenuItem('Update Password', $module::xhrCardRoute('updatePassword'));
        $_o[$module::xhrCardRoute('updateEmail')] = CardKit::onTapEventsXHRCallMenuItem('Update Email', $module::xhrCardRoute('updateEmail'));

        foreach($filters as $filter){

            unset($_o[$filter]);

        }

        return $_o;
        
    }

    public static function modelMenuItems($filters=[], $_model = null){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $items = [];

        $items[$module::xhrCardRoute('details')] = CardKit::onTapEventsXHRCallMenuItem('Detail', $module::xhrCardRoute('details'), [$modeler::model()->id]);
        $items[$module::xhrCardRoute('updatePassword')] = CardKit::onTapEventsXHRCallMenuItem('Account Password', $module::xhrCardRoute('updatePassword'), []);
        $items[$module::xhrCardRoute('updateEmail')] = CardKit::onTapEventsXHRCallMenuItem('Account Email', $module::xhrCardRoute('updateEmail'), []);

        foreach($filters as $filter){

            unset($_o[$filter]);

        }

        return $items;

    }

    public static function details($_model = null){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
        
        $_o = (object) null;

        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'users',
            'node' => $modeler::model()->id
        ];
        $_o->menu = (object) null;
        $_o->menu->items = self::modelMenuItems();
        $_o->head = 'Account Detail';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'user-icon-background';
        $_o->body = [''];
        $_o->body[] = CardKitHTML::sublineBlock('Email');
        $_o->body[] = $modeler::model()->email;

        return $_o;

    }
    
    public static function updatePassword(){

        extract((static::Module)::variables());

        $dialog = forward_static_call_array([$component_dialogs, __FUNCTION__], []);

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
        
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
                
                $_o->body = array_merge($_o->body, ModuleForm::render($module::Registry_Key, $form));
                
            }
            
        }
        
        if($dialog_store->step != 0){
            
            $_o->body[] = CardKit::resetDialogButton( $module::xhrOperationRoute(__FUNCTION__));
            
        }
        
        $_o->body[] = (object) ['js' => '$(\'.focus-input\').focus(); $(\'.focus-input\').select();'];
        
        return $_o;
        
    }
    
    public static function updateEmail(){

        extract((static::Module)::variables());

        $dialog = forward_static_call_array([$component_dialogs, __FUNCTION__], []);

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
                
                $_o->body = array_merge($_o->body, ModuleForm::render($module::Registry_Key, $form));
                
            }
            
        }
        
        if($dialog_store->step > 0){
            
            $_o->body[] = CardKit::resetDialogButton( $module::xhrOperationRoute(__FUNCTION__));
            
        }
        
        $_o->body[] = (object) ['js' => '$(\'.focus-input\').focus(); $(\'.focus-input\').select();'];
        
        return $_o;
        
    }

}