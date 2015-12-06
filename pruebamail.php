<?php 

require "lib/PHPMailer/class.phpmailer.php";

$phpmailer = new PHPMailer();
$phpmailer->isSMTP()
          ->SMTPAuth = true
          ->Host = 'email-smtp.us-west-2.amazonaws.com'
          ->Port = 25
          ->Username = 'AKIAI5J7FY3GTCNC4UUA'
          ->Password = 'Ao/BnhPkCPjlKEZx/hvUeX4MBstNR8BNyWPt3X9IOSCK'
          ->SetFrom('noticias@opemedios.com.mx', 'Noticias OPEMEDIOS');

$phpmailer->AddAddress('klonate@gmail.com', 'daniel@carlos-villicana-davila.com.mx')
          ->Subject = 'Test envio autenticado'
          ->MsgHTML('<h1>Enviando E-mail autenticado</h1>');

if($phpmailer->Send())
	echo 'E-mail enviado con exito';
else
	echo 'Error al envias email '.$phpmailer->ErrorInfo;
