<?php
/*
 * Action que agrega un organizador a la base de datos
 */

include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
require("phpclasses/ColumnaPolitica.php");
include("phpdelegates/thumbnailer.php");

//creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();

//usamos esta funcion para meter 1 o 0 en el campo activo
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType)
    {
        case "defined":
            $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
            break;
    }
    return $theValue;
}
$url = "data/col_pol"; // donde se copian los documentos en el servidor
$urlpdf = "data/col_pol/pdf";
$urlThunmb = "data/thumbs";
echo $_POST['action'];
if($_POST['action']=='new')
{

$nombre_archivo_nuevo = $_FILES['imagen_jpg']['name'];
$tamano_archivo_nuevo = $_FILES['imagen_jpg']['size'];
$tipo_archivo_nuevo = $_FILES['imagen_jpg']['type'];

$nombre_archivo_nuevop = $_FILES['archivo_pdf']['name'];
$tamano_archivo_nuevop = $_FILES['archivo_pdf']['size'];
$tipo_archivo_nuevop = $_FILES['archivo_pdf']['type'];

//se crea el directorio de tipo de archivo si no existe

if(!is_dir($url))
{
    mkdir($url, 0777);
}
if(!is_dir($urlpdf))
{
    mkdir($urlpdf, 0777);
}
//Se comprueba tamaÃ±o de archivo y se coloca en su destino final

if (!($tamano_archivo_nuevo < 10000000)) //  10 Mb
{
    $mensaje ="Error: La imagen excede el tamaño limite";
    $error_upload = true;
}
if (!($tamano_archivo_nuevop < 10000000)) //  10 Mb
{
    $mensaje ="Error: El archivo excede el tamaño limite";
    $error_upload = true;
}
if (!($tipo_archivo_nuevop != 'PDF') || !($tipo_archivo_nuevop != 'pdf')) //  10 Mb
{
    $mensaje ="Error: El archivo debe ser PDF";
    $error_upload = true;
}
else
{
    $clave = rand();
    $clavep= rand();
    $error_upload= false;
    $path =  $url."/ID".$clave."_".strtolower(str_replace(" ","_",$nombre_archivo_nuevo));
    $pathp =  $urlpdf."/ID".$clavep."_".strtolower(str_replace(" ","_",$nombre_archivo_nuevop));
    if (move_uploaded_file($_FILES['imagen_jpg']['tmp_name'],$path))
    {
        $mensaje = "exito";
        $thumbnail = new thumbnail($path,$urlThunmb,150,200,90,"_pp.");
        $thumbnails = new thumbnail($path,$url,700,1000,90,"opm.");
    }
    if (move_uploaded_file($_FILES['archivo_pdf']['tmp_name'],$pathp))
    {
        $mensaje = "exito";
    }
    else
    {
        $mensaje = "Error en la copia del archivo a establecer";
        $error_upload = true;
    }
}
// si si se sube bien, hay que establecer en la base
if($mensaje == "exito")
{
    //se obtienen los datos del archivo para crear el objeto
    $datos = array("id"=>"",
                   "titulo"=>$_POST['titulo'],
                   "autor"=>$_POST['autor'],
                   "titulo"=>$_POST['titulo'],
                   "contenido"=>$_POST['contenido'],
                   "fecha"=>$_POST['fecha'],
                   "id_fuente"=>$_POST['id_fuente'],
                   "imagen_jpg"=>"ID".$clave."_".strtolower(str_replace(" ","_",$nombre_archivo_nuevo)),
                   "archivo_pdf"=>"ID".$clavep."_".strtolower(str_replace(" ","_",$nombre_archivo_nuevop)));

    //y se crea el objeto archivo para la insercion a BD

    $pPlana = new ColumnaPolitica($datos);

    //echo $pPlana->SQL_insert();

    $base->execute_query($pPlana->SQL_insert());
}

$base->close();
header("Location: agrega_colpol.php?&error_upload=".$error_upload."&mensaje=".$mensaje);
}
/****************************************************************************
 *
 * aqui esta para el update
 *
 *******************************************************************************/
if($_POST['action']=='edit')
{

//inicializamos variables
$row_id = $_POST['row_id'];
$tipo_archivo_nuevo = 0;

echo $query = "SELECT * FROM columna_politica WHERE id_columna_politica =".$row_id." LIMIT 1;";

$base->execute_query($query);
$pPlana = new ColumnaPolitica($base->get_row_assoc());
$mensaje = "exito";

if($mensaje == "exito") {
//obtenemos el nombre del archivo viejo
    // actualizamos la base con el nombre nuevo
    $pPlana->setAutor($_POST['autor']);
    $pPlana->setId_fuente($_POST['id_fuente']);
    $pPlana->setContenido($_POST['contenido']);
    $pPlana->setFecha($_POST['fecha']);
    $pPlana->setTitulo($_POST['titulo']);
    $pPlana->setId($row_id);
    $base->execute_query($pPlana->SQL_update());
    //echo $pPlana->SQL_update();
    //borramos archivo viejo
}
$base->close();
header("Location: edit_colpol.php?id_pp=".$row_id."&error_upload=".$error_upload."&mensaje=".$mensaje);
}
/****************************************************************************
 *
 * aqui esta para el update imagen
 *
 *******************************************************************************/
if($_POST['action']=='imagen')
{

//inicializamos variables
$row_id = $_POST['row_id'];

$nombre_archivo_nuevo = $_FILES['imagen_jpg']['name'];
$tamano_archivo_nuevo = $_FILES['imagen_jpg']['size'];
$tipo_archivo_nuevo = $_FILES['imagen_jpg']['type'];

$query = "SELECT * FROM columna_politica WHERE id_columna_politica =".$row_id." LIMIT 1;";

$base->execute_query($query);
$pPlana = new ColumnaPolitica($base->get_row_assoc());
if (!($tamano_archivo_nuevo < 20000000)) //  20 Mb
{
    $mensaje ="Error: La imagen excede el tamaño limite";
    $error_upload = true;
}
else
{
    $clave = rand();
    $error_upload= false;
    $path =  $url."/ID".$clave."_".strtolower(str_replace(" ","_",$nombre_archivo_nuevo));
    if (move_uploaded_file($_FILES['imagen_jpg']['tmp_name'],$path))
    {
        $mensaje = "exito";
        $thumbnail = new thumbnail($path,$urlThunmb,150,200,90,"_pp.");
        $thumbnails = new thumbnail($path,$url,700,1000,90,"opm.");
        $imagen_jpg = "ID".$clave."_".strtolower(str_replace(" ","_",$nombre_archivo_nuevo));
    }
    else
    {
        
        $mensaje = "Error en la copia del archivo a establecer";
        $error_upload = true;
    }
}
// si si se sube bien, hay que establecer en la base
if($mensaje == "exito")
{

    //y se crea el objeto archivo para la insercion a BD

        $viejo = $pPlana->getImagen_jpg();
    // actualizamos la base con el nombre nuevo
    $pPlana->setImagen_jpg($imagen_jpg);
    $pPlana->setId($row_id);
    $base->execute_query($pPlana->SQL_update());
  //echo  $pPlana->SQL_update(); 
}
$base->close();
header("Location: edit_colpol.php?id_pp=".$row_id."&error_upload=".$error_upload."&mensaje=".$mensaje." imagen");
}
/****************************************************************************
 *
 * aqui esta para el update archivo
 *
 *******************************************************************************/

if($_POST['action']=='archivo')
{

//inicializamos variables
$row_id = $_POST['row_id'];

$nombre_archivo_nuevop = $_FILES['archivo_pdf']['name'];
$tamano_archivo_nuevop = $_FILES['archivo_pdf']['size'];
$tipo_archivo_nuevop = $_FILES['archivo_pdf']['type'];

$query = "SELECT * FROM columna_politica WHERE id_columna_politica =".$row_id." LIMIT 1;";

$base->execute_query($query);
$pPlana = new ColumnaPolitica($base->get_row_assoc());
if (!($tamano_archivo_nuevop < 20000000)) //  20 Mb
{
    $mensaje ="Error: El archivo debe ser menos a 20 Mb";
    $error_upload = true;
}
else
{
    $clavep= rand();
    $error_upload= false;
    $pathp =  $urlpdf."/ID".$clavep."_".strtolower(str_replace(" ","_",$nombre_archivo_nuevop));
    if (move_uploaded_file($_FILES['archivo_pdf']['tmp_name'],$pathp))
    {
        $mensaje = "exito";
        $archivo_pdf = "ID".$clavep."_".strtolower(str_replace(" ","_",$nombre_archivo_nuevop));
    }
    else
    {
        $mensaje = "Error en la copia del archivo a establecer";
        $error_upload = true;
    }
}
// si si se sube bien, hay que establecer en la base
if($mensaje == "exito")
{

    //y se crea el objeto archivo para la insercion a BD

    $viejo = $pPlana->getArchivo_pdf();
    // actualizamos la base con el nombre nuevo
    $pPlana->setArchivo_pdf($archivo_pdf);
    $pPlana->setId($row_id);
    $base->execute_query($pPlana->SQL_update());
  //echo  $pPlana->SQL_update();
}
$base->close();
header("Location: edit_colpol.php?id_pp=".$row_id."&error_upload=".$error_upload."&mensaje=".$mensaje." archivo");
}

else
{
   $base->close();
}
?>