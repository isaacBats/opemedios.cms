<?php


 /* 
 * Action que borra  un archivo secundario de una noticia
 */

//llamamos los archivos necesarios
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Archivo.php");

//inicializamos variables
$idnoticia = $_GET['id_noticia'];
$tipofuente = $_GET['id_tipo_fuente'];
$carpeta_tipo_fuente = "";

switch($tipofuente)
{
    case 1:
        $carpeta_tipo_fuente = "_tel";
        break;
    case 2:
        $carpeta_tipo_fuente = "_rad";
        break;
    case 3:
        $carpeta_tipo_fuente = "_per";
        break;
    case 4:
        $carpeta_tipo_fuente = "_rev";
        break;
    case 5:
        $carpeta_tipo_fuente = "_int";
        break;
    default:
        die("Error, el tipo de fuente no es valido");
        break;
}


//creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();

// creamos objeto archivo
$base->execute_query("DELETE FROM noticia WHERE id_noticia = ".$idnoticia." LIMIT 1;");

$base->execute_query("DELETE FROM noticia".$carpeta_tipo_fuente." WHERE id_noticia = ".$idnoticia." LIMIT 1;");

$base->close();

header("Location: noticiashoy.php");



?>