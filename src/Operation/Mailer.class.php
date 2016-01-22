<?php

namespace Sequode\Operation;

use Sequode\ApplicationConfiguration as ApplicationConfiguration;

class Mailer {
	public static function makeTemplate($template,$hooks){
        $default_hooks = array("#WEBSITENAME#","#WEBSITEURL#","#DATE#");
        $default_replace = array('Sequode','sequode.com',date("l \\t\h\e jS"));
		$message = file_get_contents('/opts/includes/mail-templates/'.$template);
        $message = str_replace($default_hooks,$default_replace,$message);
        $message = str_replace($hooks["searchStrs"],$hooks["subjectStrs"],$message);
        if(strpos($message,"#INC-FOOTER#") !== FALSE){
            $footer = file_get_contents('/opts/includes/mail-templates/email-footer.txt');
            if($footer && !empty($footer)) $message .= str_replace($default_hooks,$default_replace,$footer);
            $message = str_replace("#INC-FOOTER#","",$message);
        }
        return $message;
	}
	public static function send($to_email, $reply_email, $reply_name, $from_email, $from_name, $subject, $body, $attachments = array()){
        if(ApplicationConfiguration::model()->emailer->relay == 'SMTP'){
            $email = new \PHPMailer();
            
            //$email->SMTPDebug   = 4;
            $email->isSMTP();
            $email->Host 		        = ApplicationConfiguration::model()->emailer->host;
            $email->SMTPAuth        = ApplicationConfiguration::model()->emailer->auth;
            $email->Username 	    = ApplicationConfiguration::model()->emailer->username;
            $email->Password 	    = ApplicationConfiguration::model()->emailer->password;
            $email->SMTPSecure    = ApplicationConfiguration::model()->emailer->security;
            $email->Port                 = ApplicationConfiguration::model()->emailer->port;
        }
        
        $email->addAddress($to_email); 
        $email->addReplyTo($reply_email,$reply_name);
        $email->setFrom($from_email, $from_name);
        $email->Subject = $subject;
        $email->Body = $body;
        $email->msgHTML($body);
		$email->AltBody = strip_tags(str_replace('<br>', "\n\r", $body));
        if(is_array($attachments)){
            foreach($attachments as $value){
                $email->addAttachment($value);
            }
        }
        $email->send();
    }
	public static function systemSend($to, $subject, $body, $attachments = array()){
		self::send($to, 'noreply@sequode.com', 'Sequode', 'system@sequode.com', 'Sequode', $subject, $body);
	}
}