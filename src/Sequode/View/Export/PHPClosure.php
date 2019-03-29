<?php
namespace Sequode\View\Export;

use stdClass;

class PHPClosure {
    public static function export($variable, $delimeter_nl = true ) {
        if ($variable instanceof stdClass) {
            $result = '(object) '.self::export(get_object_vars($variable), false);
        } else if (is_array($variable)) {
            $array = [];
            foreach ($variable as $key => $value) {
                $array[] = var_export($key, true).' => '.self::export($value);
            }
            if($delimeter_nl == true){
            $result = '[
        '.implode(',
        ', $array).'
    ]';
            }else{
            $result = '['.implode(', ', $array).']';
            }
        } else {
            $result = var_export($variable, true);
        }

        return $result;
    }
}