<?php
/* 
 * Action que borra  un archivo secundario de una noticia
 */

//llamamos los archivos necesarios
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Archivo.php");

//inicializamos variables
$idadjunto = $_GET['id_adjunto'];
$idnoticia = $_GET['id_noticia'];
$tipofuente = $_GET['id_tipo_fuente'];
$carpeta_tipo_fuente = "";

switch($tipofuente)
{
    case 1:
        $carpeta_tipo_fuente = "television";
        break;
    case 2:
        $carpeta_tipo_fuente = "radio";
        break;
    case 3:
        $carpeta_tipo_fuente = "periodico";
        break;
    case 4:
        $carpeta_tipo_fuente = "revista";
        break;
    case 5:
        $carpeta_tipo_fuente = "internet";
        break;
    default:
        die("Error, el tipo de fuente no es valido");
        break;
}

$url = "data/noticias/".$carpeta_tipo_fuente."/";


//creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();

// creamos objeto archivo
$base->execute_query("SELECT * FROM adjunto WHERE id_adjunto = ".$idadjunto.";");

$archivo = new Archivo($base->get_row_assoc());

if (unlink($url.$archivo->getNombre_archivo()))
{
	// Eliminamos el registro de la tabla 
    $base->execute_query($archivo->SQL_Delete_Archivo());
} 
else
{
    die('Error!  No se pudo borrar el archivo'.$archivo->getNombre_archivo()) ;
}

header("Location: ver_noticia_selector.php?id_noticia=".$idnoticia."&id_tipo_fuente=".$tipofuente);

?>
