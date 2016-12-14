<?php
/* 
 * Action para modificar la informacion de una noticia de medio electronico
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Noticia.php");
include("phpclasses/NoticiaElectronico.php");

//creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();

//obtenemos los datos del formulario y se meten en un arreglo para el objeto NoticiaElectronico
$datos_noticia = array("id_noticia"=>$_POST['id_noticia'],
                        "encabezado"=>$_POST['encabezado'],
                        "sintesis"=>$_POST['sintesis'],
                        "autor"=>$_POST['autor'],
                        "fecha"=>date("Y-m-d",mktime(0,0,0,$_POST['fecha_mm'],$_POST['fecha_dd'],$_POST['fecha_yy'])),
                        "comentario"=>$_POST['comentario'],
                        "alcanse"=>$_POST['alcanse'],
                        "id_tipo_fuente"=>$_POST['id_tipo_fuente'],
                        "id_fuente"=>$_POST['id_fuente'],
                        "id_seccion"=>$_POST['id_seccion'],
                        "id_sector"=>$_POST['id_sector'],
                        "id_tipo_autor"=>$_POST['id_tipo_autor'],
                        "id_genero"=>$_POST['id_genero'],
                        "id_tendencia_monitorista"=>$_POST['id_tendencia_monitorista'],
                        "hora"=>date("H:i:s",mktime($_POST['hora_hh'],$_POST['hora_mm'],$_POST['hora_ss'],1,1,2000)),
                        "duracion"=>date("H:i:s",mktime($_POST['duracion_hh'],$_POST['duracion_mm'],$_POST['duracion_ss'],1,1,2000)),
						"costo"=>$_POST['costo']);

//creamos el objeto NoticiaElectronico
$noticia = new NoticiaElectronico($datos_noticia);

// actualizamos
$base->execute_query($noticia->SQL_EDIT_NOTICIA());

$base->close();

header("Location:ver_noticia_selector.php?id_noticia=".$noticia->getId()."&id_tipo_fuente=".$noticia->getId_tipo_fuente());

?>
