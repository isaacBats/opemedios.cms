<?php 

//include ("lib/PHPMailer/class.phpmailer.php");
//include ("lib/PHPMailer/class.smtp.php");
require 'lib/PHPMailer/PHPMailerAutoload.php';


$correos = array(
	'klonate@gmail.com', 
	'prueba@opemedios.com.mx', 
	'daniel@carlos-villicana-davila.com.mx'
);




date_default_timezone_set("Mexico/General");

$phpmailer = new PHPMailer();
$phpmailer->isSMTP();
$phpmailer->SMTPAuth = true;
$phpmailer->SMTPSecure = "ssl";
$phpmailer->Host = 'email-smtp.us-west-2.amazonaws.com';
//$phpmailer->Host = "smtp.gmail.com";
$phpmailer->Port = 465;
$phpmailer->Username = 'AKIAI5J7FY3GTCNC4UUA';
$phpmailer->Password = 'Ao/BnhPkCPjlKEZx/hvUeX4MBstNR8BNyWPt3X9IOSCK';
$phpmailer->CharSet = 'UTF-8';
//$phpmailer->Username = 'noticias.opemedios@gmail.com';
//$phpmailer->Password = 'noti1234';
$phpmailer->SetFrom('noticias@opemedios.com.mx', 'Noticias OPEMEDIOS');

//$address = "klonate@gmail.com";
//$address = "daniel@carlos-villicana-davila.com.mx";
$address = "prueba@opemedios.com.mx";
foreach ($correos as $correo) {
	# code...
	$phpmailer->AddAddress($correo);
}

$phpmailer->Subject = 'Test envio autenticado';
$phpmailer->MsgHTML('<h1>Enviando E-mail autenticado desde amazon</h1>');

if($phpmailer->Send())
	echo 'E-mail enviado con exito';
else
	echo 'Error al enviar el correo '.$phpmailer->ErrorInfo;
