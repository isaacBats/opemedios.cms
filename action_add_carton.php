<?php
/*
 * Action que agrega un organizador a la base de datos
 */

include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Cartones.php");
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
$url = "data/cartones"; // donde se copian los documentos en el servidor
$urlThunmb = "data/thumbs";
echo $_POST['action'];
if($_POST['action']=='new')
{


$nombre_archivo_nuevo = $_FILES['imagen']['name'];
$tamano_archivo_nuevo = $_FILES['imagen']['size'];
$tipo_archivo_nuevo = $_FILES['imagen']['type'];

//se crea el directorio de tipo de archivo si no existe

if(!is_dir($url))
{
    mkdir($url, 0777);
}

//Se comprueba tamaÃ±o de archivo y se coloca en su destino final

if (!($tamano_archivo_nuevo < 10000000)) //  10 Mb
{
    $mensaje ="Error: El archivo excede el tamaño limite";
    $error_upload = true;
}
else
{
    $clave = rand();
    $error_upload= false;
    $path =  $url."/ID".$clave."_".strtolower(str_replace(" ","_",$nombre_archivo_nuevo));
    if (move_uploaded_file($_FILES['imagen']['tmp_name'],$path))
    {
        $mensaje = "exito";
        $thumbnail = new thumbnail($path,$urlThunmb,150,200,90,"_pp.");
        $thumbnails = new thumbnail($path,$url,700,1000,90,"opm.");
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
                   "fecha"=>$_POST['fecha'],
                   "id_fuente"=>$_POST['fuente_id'],
                   "imagen"=>"ID".$clave."_".strtolower(str_replace(" ","_",$nombre_archivo_nuevo)));

    //y se crea el objeto archivo para la insercion a BD

    $pPlana = new Cartones($datos);

    echo $pPlana->SQL_insert();

    $base->execute_query($pPlana->SQL_insert());
}

$base->close();
header("Location: agrega_carton.php?&error_upload=".$error_upload."&mensaje=".$mensaje);
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

echo $query = "SELECT * FROM carton WHERE id_carton =".$row_id." LIMIT 1;";

$base->execute_query($query);
$pPlana = new Cartones($base->get_row_assoc());

//subimos archivo nuevo
echo $_FILES['imagen']['name'];

if($_FILES['imagen']['name']!=""){
$nombre_archivo_nuevo = $_FILES['imagen']['name'];
$tamano_archivo_nuevo = $_FILES['imagen']['size'];
$tipo_archivo_nuevo = $_FILES['imagen']['type'];
}
//se crea el directorio de tipo de archivo si no existe

if(!is_dir($url)) {
    mkdir($url, 0777);
}

//Se comprueba tamaño de archivo y se coloca en su destino final

if (!($tamano_archivo_nuevo < 10000000)) //  10 Mb
{
    $mensaje ="Error: El archivo excede el tamaño limite";
    $error_upload = true;
}
else {
    if($_FILES['imagen']['name']!=""){
            $clave = rand();
            $error_upload= false;
            $path =  $url."/ID".$clave."_".strtolower(str_replace(" ","_",$nombre_archivo_nuevo));
            if (move_uploaded_file($_FILES['imagen']['tmp_name'],$path))
            {
                $mensaje = "exito";
                $thumbnail = new thumbnail($path,$urlThunmb,150,200,90,"_pp.");
                $thumbnails = new thumbnail($path,$url,700,1000,90,"opm.");
            }
            else {
                $mensaje = "Error en la copia del archivo a establecer";
                $error_upload = true;
            }
    }else{
        $mensaje = "exito";
    }
}

if($mensaje == "exito") {
//obtenemos el nombre del archivo viejo
    $viejo = $pPlana->get_imagen();
    // actualizamos la base con el nombre nuevo
    if($_FILES['imagen']['name']!="")
    {
        $pPlana->set_imagen("ID".$clave."_".$nombre_archivo_nuevo);
        unlink($url."/".$viejo);
        unlink($urlThunmb."/".$viejo."_pp.jpeg");
        unlink($urlThunmb."/".$viejo."opm.jpeg");
    }
    $pPlana->set_fecha($_POST['titulo']);
    $pPlana->set_id_fuente($_POST['autor']);
    $pPlana->set_fecha($_POST['fecha']);
    $pPlana->set_id_fuente($_POST['fuente_id']);
    $pPlana->set_id($row_id);
    $base->execute_query($pPlana->SQL_update());
    echo $pPlana->SQL_update();
    //borramos archivo viejo
}
$base->close();
header("Location: edit_carton.php?id_pp=".$row_id."&error_upload=".$error_upload."&mensaje=".$mensaje);
}
else
{
   $base->close();
}
?>