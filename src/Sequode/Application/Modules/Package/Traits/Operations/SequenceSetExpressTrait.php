<?php
namespace Sequode\Application\Modules\Package\Traits\Operations;

trait SequenceSetExpressTrait {
        
    public static function exists($value, $by='id'){
        
        if($by == 'name'){
            
            return (isset(static::$name_to_id[$value])) ? static::$id_to_key[static::$name_to_id[$value]] : false;
        }else{
        
            return (isset(static::$id_to_key[intval($value)])) ? static::$id_to_key[intval($value)] : false;
        }
        
    }
    
    public static function node($value, $by = null){

        switch($by){
            case 'id':
            case 'name':
                break;
            default:
                $by = 'id';
                break;
        }

        $key = static::exists($value, $by);
        $models = static::models();
        return ($key !== false) ? $models[$key] : false;

	}
    
    public static function express(&$sm){
            
        $_ks = $_ki = $_kp = $_ko = 0;
        $_s = [[]];
        foreach($sm->s as $id){
            
            $_s[] = static::node($sm->s[$_ks]);
            $_ks++;
            foreach($_s[$_ks]->i as $_m => $_v){
                
                $_ki++;
                $_s[$_ks]->i->{$_m} = $sm->im[$_ki]->Value;
                if($sm->im[$_ki]->Key == 0 && $sm->im[$_ki]->Stack == 'i'){
                    
                    $_s[$_ks]->i->{$_m} = $sm->i->{$sm->im[$_ki]->Member};
                    
                }elseif($sm->im[$_ki]->Key > 0 && $sm->im[$_ki]->Stack == 'o'){
                    
                    $_s[$_ks]->i->{$_m} = $_s[$sm->im[$_ki]->Key]->o->{$sm->im[$_ki]->Member};
                    
                }
                
                if(is_string($_s[$_ks]->i->{$_m}) && in_array(strtolower($_s[$_ks]->i->{$_m}), ['true','false'])){
                    
                    $_s[$_ks]->i->{$_m} = (strtolower($_s[$_ks]->i->{$_m}) == 'true') ? true : false;
                    
                }
                
            }
            
            foreach($_s[$_ks]->p as $_m => $_v){
                
                $_kp++;
                $_s[$_ks]->p->{$_m} = $sm->pm[$_kp]->Value;
                if($sm->pm[$_kp]->Key == 0 && $sm->pm[$_kp]->Stack == 'p'){
                    
                    $_s[$_ks]->p->{$_m} = $sm->p->{$sm->pm[$_kp]->Member};
                    
                }elseif($sm->pm[$_kp]->Key > 0 && $sm->pm[$_kp]->Stack == 'o'){
                    
                    $_s[$_ks]->p->{$_m} = $_s[$sm->pm[$_kp]->Key]->o->{$sm->pm[$_kp]->Member};
                    
                }
                
                if(is_string($_s[$_ks]->p->{$_m}) && in_array(strtolower($_s[$_ks]->p->{$_m}), ['true','false'])){
                    
                    $_s[$_ks]->p->{$_m} = (strtolower($_s[$_ks]->p->{$_m}) == 'true') ? true : false;
                    
                }
                
            }
            
            (isset($_s[$_ks]->c))
                ? call_user_func_array($_s[$_ks]->c, [&$_s[$_ks]])
                : static::express($_s[$_ks]);
                
            foreach($_s[$_ks]->o as $_m => $_v){
                
                $_ko++;
                if($sm->om[$_ko]->Key == 0 && $sm->om[$_ko]->Stack == 'o'){
                    $sm->o->{$sm->om[$_ko]->Member} = $_s[$_ks]->o->{$_m};
                }
                
            }
            
		}
        
		return;
        
	}
}