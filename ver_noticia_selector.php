<?php
/* 
 * Elige la interfaz de la noticia segun su tipo
 */

$tipofuente = $_GET['id_tipo_fuente'];
$idnoticia = $_GET['id_noticia'];

$is_red = (isset($_GET['red'])) ? true : false;


switch($tipofuente)
{
	case "1":
		header("Location: ver_noticia_electronico.php?id_noticia=".$idnoticia."&id_tipo_fuente=1");
		break;

	case "2":
		header("Location: ver_noticia_electronico.php?id_noticia=".$idnoticia."&id_tipo_fuente=2");
		break;

	case "3":
		header("Location: ver_noticia_prensa.php?id_noticia=".$idnoticia."&id_tipo_fuente=3");
		break;

	case "4":
		header("Location: ver_noticia_prensa.php?id_noticia=".$idnoticia."&id_tipo_fuente=4" );
		break;

	case "5":
		header("Location: ver_noticia_internet.php?id_noticia=".$idnoticia."&red=".$is_red);
		break;
}

?>
