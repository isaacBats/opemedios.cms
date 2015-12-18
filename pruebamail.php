<?php 

include ("lib/PHPMailer/class.phpmailer.php");
include ("lib/PHPMailer/class.smtp.php");

$phpmailer = new PHPMailer();
$phpmailer->isSMTP();
$phpmailer->SMTPAuth = true;
$phpmailer->Host = 'email-smtp.us-west-2.amazonaws.com';
//$phpmailer->Host = 'smtp.gmail.com';
$phpmailer->Port = 25;
$phpmailer->Username = 'AKIAI5J7FY3GTCNC4UUA';
$phpmailer->Password = 'Ao/BnhPkCPjlKEZx/hvUeX4MBstNR8BNyWPt3X9IOSCK';
//$phpmailer->Username = 'noticias.opemedios@gmail.com';
//$phpmailer->Password = 'noti1234';
$phpmailer->SetFrom('noticias@opemedios.com.mx', 'Noticias OPEMEDIOS');

$phpmailer->AddAddress('klonate@gmail.com', 'daniel@carlos-villicana-davila.com.mx', 'prueba@opemedios.com.mx');
$phpmailer->Subject = 'Test envio autenticado';
$phpmailer->MsgHTML('<h1>Enviando E-mail autenticado</h1>');

if($phpmailer->Send())
	echo 'E-mail enviado con exito';
else
	echo 'Error al envias email '.$phpmailer->ErrorInfo;
