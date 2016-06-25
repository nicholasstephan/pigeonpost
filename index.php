<?php

require_once("vendor/autoload.php");


//
// Send an email message.
//

function sendEmail($message) {
    $to = $message->to;
    $cc = $message->cc;
    $bcc = $message->bcc;
    $from = $message->from;
    $subject = $message->subject;
    $message = $message->message;
    $attachments = $message->attachments;
    
    $mail = new PHPMailer;
    
    // Gmail example pulled from:
    // https://github.com/PHPMailer/PHPMailer/blob/master/examples/gmail.phps
    $mail->isSMTP();
    $mail->SMTPDebug = 0; // 1 or 2 for debugging
    $mail->Debugoutput = 'html';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = "alberto.rvx@gmail.com";
    $mail->Password = "zAq12345";
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