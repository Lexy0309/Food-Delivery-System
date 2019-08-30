<?php
 
function Send_Mail($to,$subject,$body)
{
require 'class.phpmailer.php';
$from = "email@domain-name.com";
$mail = new PHPMailer();
$mail->IsSMTP(true); // SMTP
$mail->SMTPAuth   = true;  // SMTP authentication
$mail->Mailer = "smtp";
$mail->Host       = "tls://p3plcpnl0531.prod.phx3.secureserver.net"; // Amazon SES server, note "tls://" protocol
$mail->Port       = 465;                    // set the SMTP port
$mail->Username   = "email@domain-name.com";  // SES SMTP  username
$mail->Password   = "112233445566";  // SES SMTP password
$mail->SetFrom($from, 'Abc');
$mail->AddReplyTo($from,'Abc');
$mail->Subject = $subject;
$mail->MsgHTML($body);
$address = $to;
$mail->AddAddress($address, $to);

if(!$mail->Send())
{
return false;
}
else
{
	//else condition if you want to add
}

}
?>