<?php

require_once("vendor/autoload.php");


$contact_image_data="data:image/png;base64,iVBORw0KGgo[...]";





// =============================================================================
// Define Routes
// =============================================================================

$f3 = Base::instance();



// http://docs.pigeonpost.apiary.io/#reference/0/email-capsules

$f3->route('POST /email', function($f3) {
    $payload = json_decode( $f3->get("BODY") );
    
    $from = $payload->from;
    $to = $payload->to;
    $cc = $payload->cc;
    $bcc = $payload->bcc;
    $subject = $payload->subject;
    $message = $payload->message;
    $attachments = $payload->attachments;
    
    $mail = new PHPMailer;
    
    $mail->isSMTP();
    $mail->SMTPDebug = 0; // 1 or 2 for debugging
    $mail->Debugoutput = 'html';
    $mail->Host = $from->host;
    $mail->Port = $from->port;
    $mail->SMTPSecure = $from->security;
    $mail->SMTPAuth = $from->auth;
    $mail->Username = $from->username;
    $mail->Password = $from->password;
    $mail->setFrom($from->address, $from->name);
    $mail->isHTML(true);
    
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
    
    if(is_array($attachments)) foreach($attachments as $att) {
        $mail->addStringAttachment(base64_decode($att->content), $att->filename, $att->encoding, $att->type);
    }
    
    
    // TODO: better result reporting.
    
    if(!$mail->send()) {
        $f3->error(500);
    } 
});




$f3->run();

?>