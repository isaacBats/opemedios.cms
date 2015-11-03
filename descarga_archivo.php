<?php
/* 
 * Descarga el archivo segun los parametros dados en los links que se envian por mail
 */

include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Archivo.php");

// iniciamos conexion

$base = new OpmDB(genera_arreglo_BD());
$base->init();

//obtenemos la carpeta donde se encuentra la noticia
$carpeta_tipo = "";
switch($_GET['id_tipo_fuente'])
{
    case 1:
        $carpeta_tipo = "television";
        break;
    case 2:
        $carpeta_tipo = "radio";
        break;
    case 3:
        $carpeta_tipo = "periodico";
        break;
    case 4:
        $carpeta_tipo = "revista";
        break;
    case 5:
        $carpeta_tipo = "internet";
        break;
    default:
        $carpeta_tipo = "error";
        break;

}

//vamos a obtener los datos del archivo
$base->execute_query("SELECT * FROM adjunto WHERE id_adjunto=".$_GET['id_adjunto']);
$archivo = new Archivo($base->get_row_assoc());

if(md5("noticia".$archivo->getId_noticia()."encriptada") == $_GET['token'])
{
    $file_path ="data/noticias/".$carpeta_tipo."/".$archivo->getNombre_archivo();
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header("Content-Disposition: attachment; filename=".str_replace(" ","_",$archivo->getNombre()));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header ("Content-Length: ".filesize($file_path));
    ob_clean();
    flush();
    readfile($file_path);
    exit();
}
else
{
    header("Location: http://www.operamedios.com.mx/notfound.php");
    exit();
}

?>
