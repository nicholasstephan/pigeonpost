<?php

require_once("vendor/autoload.php");


//
// Send an email message.
//

function sendEmail($message) {
    $from = $message->from;
    $to = $message->to;
    $cc = $message->cc;
    $bcc = $message->bcc;
    $subject = $message->subject;
    $message = $message->message;
    $attachments = $message->attachments;
    
    $mail = new PHPMailer;
    
    // Gmail example pulled from:
    // https://github.com/PHPMailer/PHPMailer/blob/master/examples/gmail.phps
    $mail->isSMTP();
    $mail->SMTPDebug = 0; // 1 or 2 for debugging
    $mail->Debugoutput = 'html';
    $mail->Host = $from->host;
    $mail->Port = $from->port;
    $mail->SMTPSecure = $from->security;
    $mail->SMTPAuth = $from->auth;
    $mail->Username = $from->username;
    $mail->Password = $from->password;
    $mail->isHTML(true);
    
    $mail->setFrom($from->address, $from->name);
    
    if(is_array($to)) foreach($to as $recipient) {
        $mail->addAddress($recipient->address, $recipient->name);
    }
    
    if(is_array($cc)) foreach($cc as $recipient) {
        $mail->addCC($recipient->address, $recipient->name);
    }
    
    if(is_array($bcc)) foreach($bcc as $recipient) {
        $mail->addBCC($recipient->address, $recipient->name);
    }
    
    $mail->Subject = $subject;
    $mail->Body = $message;
    
    return $mail->send();
}



//
// Define Routes
//

$f3 = Base::instance();


/** 
 * Send an email.
 * 
 * Docs:
 * 
 *     http://docs.pigeonpost.apiary.io/#reference/0/email-capsules
 * 
 * Example Payload:
 * 
 *     {  
 *        "from": {
 *          "host": "smtp.gmail.com",
 *          "port": "587",
 *          "security": "tls",
 *          "auth": true,
 *          "username": "alberto.rvx@gmail.com",
 *          "password": "zAq12345",
 *          "address": "alberto.rvx@gmail.com",
 *          "name": "Alberto Siza"
 *        },
 *        "to": [
 *         {
 *           "address": "amanda.rvx@gmail.com",
 *           "name": "Amanda Carter"
 *         }
 *        ],
 *        "subject": "Hello Amanda",
 *        "message": "<b>Hello</b> Amanda!"
 *     }
 * 
 */

$f3->route('POST /email',
    function($f3) {
        $result = sendEmail( json_decode( $f3->get("BODY") ) );
        
        // TODO: better result reporting.
        if(!$result) {
            $f3->error(500);
        } 
    }
);

$f3->run();

?>