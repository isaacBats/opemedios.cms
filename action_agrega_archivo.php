<?php
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Archivo.php");

//creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();

if (isset($_POST['id_noticia']))
{
    $id_noticia= $_POST['id_noticia'];
}
if(isset($_POST['principal']))
{
    $principal = $_POST['principal'];
}

$url = "data/noticias/".$_POST['folder'];// directorio donde se copian los archivos de las noticias

$nombre_archivo_nuevo = $_FILES['archivo']['name'];
$tamano_archivo_nuevo = $_FILES['archivo']['size'];
$tipo_archivo_nuevo = $_FILES['archivo']['type'];

//se crea el directorio de tipo de archivo si no existe

if(!is_dir($url))
{
    mkdir($url, 0777);
}

//Se comprueba tamaño de archivo y se coloca en su destino final

if (!($tamano_archivo_nuevo < 100000000)) //  100 Mb
{
    $mensaje ="Error: El archivo excede el tamaño limite";
    $error_upload = true;
}
else
{
    $error_upload= false;
    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $url."/ID".$id_noticia."_".$nombre_archivo_nuevo))
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

    $datos_archivo = array("id_adjunto"=>"",
                                       "nombre"=>$nombre_archivo_nuevo,
                                       "nombre_archivo"=>"ID".$id_noticia."_".$nombre_archivo_nuevo,
                                       "tipo"=>$tipo_archivo_nuevo,
                                       "carpeta"=>"",
                                       "principal"=>$principal,
                                       "id_noticia"=>$id_noticia);

    //y se crea el objeto archivo para la insercion a BD

    $archivo = new Archivo($datos_archivo);
    $base->execute_query($archivo->SQL_Insert_Archivo());

    $principal = 0;

}

$base->close();
header("Location:agrega_archivo.php?id_noticia=".$id_noticia."&principal=".$principal."&error_upload=".$error_upload.
                                                "&mensaje=".$mensaje."&archivo=".$nombre_archivo_nuevo."&folder=".$_POST['folder']);

?>