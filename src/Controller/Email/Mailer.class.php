<?php

namespace Sequode\Controller\Email;

use Sequode\Model\Application\Configuration;

class Mailer {
	public static function send($to_email, $reply_email, $reply_name, $from_email, $from_name, $subject, $body, $attachments = array()){
        if(Configuration::model()->emailer->relay == 'SMTP'){
            $email = new \PHPMailer();
            
            //$email->SMTPDebug   = 4;
            $email->isSMTP();
            $email->Host 		        = Configuration::model()->emailer->host;
            $email->SMTPAuth        = Configuration::model()->emailer->auth;
            $email->Username 	    = Configuration::model()->emailer->username;
            $email->Password 	    = Configuration::model()->emailer->password;
            $email->SMTPSecure    = Configuration::model()->emailer->security;
            $email->Port                 = Configuration::model()->emailer->port;
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
        self::send(
            $to,
            Configuration::model()->email->system->reply_email,
            Configuration::model()->email->system->reply_name,
            Configuration::model()->email->system->from_email,
            Configuration::model()->email->system->from_name,
            $subject,
            $body
        );
	}
}