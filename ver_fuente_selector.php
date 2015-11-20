<?php
/* 
 * Elije la interfaz para ver la fuente segun su tipo
 */

$tipofuente = $_GET['id_tipo_fuente'];
$idfuente = $_GET['id_fuente'];

switch($tipofuente)
{
	case "1":
		header("Location: ver_fuente_tv.php?id_fuente=".$idfuente );
		break;

	case "2":
		header("Location: ver_fuente_radio.php?id_fuente=".$idfuente );
		break;

	case "3":
		header("Location: ver_fuente_prensa.php?id_fuente=".$idfuente."&id_tipo_fuente=3" );
		break;

	case "4":
		header("Location: ver_fuente_prensa.php?id_fuente=".$idfuente."&id_tipo_fuente=4" );
		break;

	case "5":
		header("Location: ver_fuente_internet.php?id_fuente=".$idfuente );
		break;
}

?>
