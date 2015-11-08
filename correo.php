<?php
echo 'hola...';
require 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
require_once('vendor/phpmailer/phpmailer/class.phpmailer.php');

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'noticias.opemedios@gmail.com';                 // SMTP username
$mail->Password = 'noti1234';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
//$mail->Port = 465;                                    // TCP port to connect to
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom('noticias.opemedios@gmail.com', 'Noticias');
$mail->addAddress('klonate@gmail.com', 'Joe User');     // Add a recipient
$mail->addAddress('klonate@yahoo.com.mx');               // Name is optional
$mail->addReplyTo('noticias1.opemedios@gmail.com', 'Information');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Esta es una noticia enviada';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}