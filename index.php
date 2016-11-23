<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
header('Access-Control-Allow-Methods: GET, POST');


require_once("vendor/autoload.php");
$f3 = Base::instance();


function connectToDatabase() {
    $db = new DB\SQL(
        'mysql:host=192.186.239.166;port=3306;dbname=pigeonpost',
        'pigeonmaster',
        'zAq12345'
    );
    
    return $db;
}


$f3->route('GET /', function($f3) {
    echo View::instance()->render('welcome.html');
});


$f3->route('POST /coops', function($f3) {
    
    $payload = json_decode( file_get_contents('php://input'), true );
    
    // todo: do some data validation
    
    $db = connectToDatabase();
    $secret = md5(uniqid(rand(), true));
    
    $query = "
        INSERT INTO 
            coops (
                host, 
                port, 
                security, 
                auth, 
                username, 
                password, 
                address, 
                name, 
                secret
            ) 
        VALUES 
            (?,?,?,?,?,?,?,?,?)
    ";
    
    $values = array(
        1=>$payload['host'],
        2=>$payload['port'],
        3=>$payload['security'],
        4=>$payload['auth'],
        5=>$payload['username'],
        6=>$payload['password'],
        7=>$payload['address'],
        8=>$payload['name'],
        9=>$secret
    );
    
    $db->exec($query, $values);
    
    // todo: check response from db
    
    $response = array(
        success => true,
        secret => $secret
    );
    echo json_encode($response);
    
});


$f3->route('POST /email', function($f3) {
    
    // Gather up the data sent by the user.
    
    $payload = json_decode( file_get_contents('php://input'), true );
    
    $secret = $payload['secret'];
    $to = $payload['to'];
    $cc = $payload['cc'];
    $bcc = $payload['bcc'];
    $subject = $payload['subject'];
    $message = $payload['message'];
    $attachments = $payload['attachments'];
    
    
    // $db = connectToDatabase();
    // $query = "SELECT * FROM coops WHERE secret = ?";
    // $response = $db->exec($query, $secret);
    
    // if(!$response[0]) {
    //     $response = array(
    //         success => false,
    //         message => "We've lost your coop! It was hear a minute ago..."
    //     );
    //     echo json_encode($response);
    //     exit;
    // }
    
    // $from = $response[0];
    if(isset($payload['from'])) {
        $from = $payload['from'];
    }
    else if($payload['secret'] == "knack") {
        $from = array(
        	name => "Snooping",
    		address => "alberto.rvx@gmail.com",
    		host => "smtp.gmail.com",
    		port => 587,
    		security => "TLS",
    		auth => true,
    		username => "alberto.rvx@gmail.com",
    		password => "zAq12345"  
        );
    }
    else if($payload['secret'] == "impact") {
        $from = array(
        	name => "Impact XM",
    		address => "tools@impact-xm.com",
    		host => "homie.mail.dreamhost.com",
    		port => 25,
    		security => "TLS",
    		auth => true,
    		username => "fulfillment@impactxmstaging.com",
    		password => "BgNEvTSWYu7O"  
        );
    }
    else if($payload['secret'] == "66b7ae4e007281bf38b4d9016167dd2c") {
        $from = array(
        	name => "Amadeus",
        	address => "northamerica.events@amadeus.com",
        	host => "homie.mail.dreamhost.com",
        	port => 25,
        	security => "TLS",
        	auth => true,
        	username => "noreply@amadeuspowerstravel.com",
        	password => "25amadeus16"
        );
    }
    else {
        $from = array(
        	name => "Pigeon Post",
    		address => "pigeonpostonline@gmail.com",
    		host => "smtp.gmail.com",
    		port => 587,
    		security => "TLS",
    		auth => true,
    		username => "pigeonpostonline@gmail.com",
    		password => "zAq12345"  
        );
    }
    
    $debug = "";
    
    $mail = new PHPMailer;
    
    $mail->isSMTP();
    $mail->SMTPDebug = 2; // 1 or 2 for debugging
    $mail->Debugoutput = function($str, $level) {
        $debug .= "$level: $str\n";
    };
    $mail->Debugoutput = "html";
    $mail->isHTML(true);
    
    $mail->Host = $from['host'];    
    $mail->Port = $from['port'];
    $mail->SMTPSecure = $from['security'];
    $mail->SMTPAuth = $from['auth'];
    $mail->Username = $from['username'];
    $mail->Password = $from['password'];
    $mail->setFrom($from['address'], $from['name']);
    
    if(is_array($to)) foreach($to as $recipient) {
        $mail->addAddress($recipient['address'], $recipient['name']);
    }
    
    if(is_array($cc)) foreach($cc as $recipient) {
        $mail->addCC($recipient['address'], $recipient['name']);
    }
    
    if(is_array($bcc)) foreach($bcc as $recipient) {
        $mail->addBCC($recipient['address'], $recipient['name']);
    }
    
    $mail->Subject = $subject;
    $mail->Body = $message;
    
    if(is_array($attachments)) foreach($attachments as $att) {
        $mail->addStringAttachment(base64_decode($att['content']), $att['filename'], $att['encoding'], $att['type']);
    }
    
    // TODO: better result reporting.
    
    if(!$mail->send()) {
        $response = array(
            success => false,
            message => "It doesn't look like your bird made it over the mountains. Maybe you should send another one.",
            debug => $debug
        );
        echo json_encode($response);
        exit;
    }
    
    // $db->exec("INSERT INTO logs (secret) VALUES (?)", $secret);
    
    $response = array(
        success => true
    );
    echo json_encode($response);
});




$f3->run();

?>