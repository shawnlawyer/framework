<?php

namespace Sequode\View\Email;

use Sequode\Model\Application\Configuration;

class EmailContent {
	public static function render($template,$hooks){
        $default_hooks = array(
            "#WEBSITENAME#",
            "#WEBSITEURL#",
            "#DATE#"
        );
        $default_replace = array(
            Configuration::model()->site->display_name,
            Configuration::model()->site->domain,
            date("l \\t\h\e jS")
        );
		$message = file_get_contents('/opts/includes/mail-templates/'.$template);
        $message = str_replace($default_hooks, $default_replace, $message);
        $message = str_replace($hooks["searchStrs"], $hooks["subjectStrs"], $message);
        if(strpos($message,"#INC-FOOTER#") !== FALSE){
            $footer = file_get_contents('/opts/includes/mail-templates/email-footer.txt');
            if($footer && !empty($footer)) $message .= str_replace($default_hooks,$default_replace,$footer);
            $message = str_replace("#INC-FOOTER#","",$message);
        }
        return $message;
	}
}