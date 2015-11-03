<?php
/* 
 * Action para establecer el archivo principal de una noticia
 */

//llamamos los archivos necesarios
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Archivo.php");

//inicializamos variables
$idnoticia = $_POST['id_noticia'];
$tipofuente = $_POST['id_tipo_fuente'];
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
        die("Error, el tipo de funte no es valido");
        break;
}

$url = "data/noticias/".$carpeta_tipo_fuente."/";


//creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();

$base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$_POST['id_noticia']." AND principal = 1;");

$error_delete = false;

//vemos si hay algun archivo principal
if(!$base->num_rows() <= 0)// si si hay
{
    //creamos objeto Archivo
    $archivoviejo = new Archivo($base->get_row_assoc());

    // borramos de disco duro
    if (unlink($url.$archivoviejo->getNombre_archivo()))
    {
        // Eliminamos el registro de la tabla
        $base->execute_query("DELETE FROM adjunto WHERE id_adjunto =".$archivoviejo->getId()." LIMIT 1;");
    }
    else
    {
        $mensaje= 'Error!  No se pudo borrar el archivo'.$archivoviejo->getNombre_archivo();
        $error_delete = true;
    }
}

//procedemos a la copia del archivo nuevo

$nombre_archivo_nuevo = $_FILES['archivo']['name'];
$tamano_archivo_nuevo = $_FILES['archivo']['size'];
$tipo_archivo_nuevo = $_FILES['archivo']['type'];

//se crea el directorio de tipo de archivo si no existe

if(!is_dir($url))
{
    mkdir($url, 0777);
}

//Se comprueba tamaño de archivo y se coloca en su destino final

if (!($tamano_archivo_nuevo < 268435456)) //  50 M
{
    $mensaje ="Error: El archivo excede el tamaño limite";
    $error_upload = true;
}
else
{
    $error_upload= false;
    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $url."/ID".$idnoticia."_".$nombre_archivo_nuevo))
    {
        $mensaje = "exito";
    }
    else
    {
        $mensaje = "Error en la copia del archivo a establacer";
        $error_upload = true;
    }
}
// si si se sube bien, hay que establecer en la base
if($mensaje == "exito")
{
    //se obtienen los datos del archivo para crear el objeto

    $datos_archivo = array("id_adjunto"=>"",
                                       "nombre"=>$nombre_archivo_nuevo,
                                       "nombre_archivo"=>"ID".$idnoticia."_".$nombre_archivo_nuevo,
                                       "tipo"=>$tipo_archivo_nuevo,
                                       "carpeta"=>"",
                                       "principal"=>1,
                                       "id_noticia"=>$idnoticia);

    //y se crea el objeto archivo para la insercion a BD

    $archivonuevo = new Archivo($datos_archivo);
    $base->execute_query($archivonuevo->SQL_Insert_Archivo());

}

$base->close();


if($error_delete == true || $error_upload == true || $mensaje!= "exito")
{
    header("Location:set_archivo_principal.php?id_noticia=".$idnoticia."&id_tipo_fuente=".$tipofuente."&mensaje=".$mensaje);
}
else
{
    header("Location:ver_noticia_selector.php?id_tipo_fuente=".$tipofuente."&id_noticia=".$idnoticia);
}

?>