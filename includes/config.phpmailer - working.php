<?php

require_once('mailer/class.phpmailer.php');
$mail = new PHPMailer;

// $mail->isSMTP();
$mail->SMTPDebug = 0;

$mail->From = 'designer@remapweb.com';
$mail->FromName = 'ReMAP Designer';
$mail->Host = 'smtp.mailgun.org';
$mail->Port = 587;
$mail->SMTPSecure = "tls";
$mail->SMTPAuth = true;
$mail->Username = 'postmaster@remapweb.com';
$mail->Password = 'doreplynot1@1#';
/*
$mail->Port = 2525;
$mail->Username = 'postmaster@apps.remapweb.com';
$mail->Password = '4ed3c7498da0089e2b8a9e0a0c33770c';
*/
/*
$mail->Port = 2525;
$mail->Username = 'postmaster@sandbox44cbff10fc1c4762917bb9c9c572b4ca.mailgun.org';
$mail->Password = '217a62e519744a1d69ed7b9f566231d8-07f37fca-dd1c9f99';
*/
$mail->AddBcc('sdeep86@gmail.com','Designer-Admin');

?>
