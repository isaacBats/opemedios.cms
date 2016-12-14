<?php
/* 
 * Action para modificar la informacion de una noticia de medio impreso
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Noticia.php");
include("phpclasses/NoticiaExtra.php");
include("phpclasses/Ubicacion.php");

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType) {
        case "text":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "int":
            $theValue = ($theValue != "") ? intval($theValue) : "NULL";
            break;
		case "float":
            $theValue = ($theValue != "") ? floatval($theValue) : "NULL";
            break;
        case "date":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "defined":
            $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
            break;

    } // end switch
    return $theValue;
} // end function



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
                        "pagina"=>$_POST['pagina'],
                        "id_tipo_pagina"=>$_POST['id_tipo_pagina'],
                        "porcentaje_pagina"=>$_POST['porcentaje_pagina'],
						"costo"=>$_POST['costo'],
						);

//creamos el objeto NoticiaElectronico
$noticia = new NoticiaExtra($datos_noticia,$_POST['id_tipo_fuente']);

// actualizamos
$base->execute_query($noticia->SQL_EDIT_NOTICIA());

//actualizamos la ubicacion
$datos_ubicacion = array("id_noticia"=>$noticia->getId(),
                                     "uno"=>GetSQLValueString(isset($_POST['checkbox1']) ? "true" : "", "defined","1","0"),
                                     "dos"=>GetSQLValueString(isset($_POST['checkbox2']) ? "true" : "", "defined","1","0"),
                                     "tres"=>GetSQLValueString(isset($_POST['checkbox3']) ? "true" : "", "defined","1","0"),
                                     "cuatro"=>GetSQLValueString(isset($_POST['checkbox4']) ? "true" : "", "defined","1","0"),
                                     "cinco"=>GetSQLValueString(isset($_POST['checkbox5']) ? "true" : "", "defined","1","0"),
                                     "seis"=>GetSQLValueString(isset($_POST['checkbox6']) ? "true" : "", "defined","1","0"),
                                     "siete"=>GetSQLValueString(isset($_POST['checkbox7']) ? "true" : "", "defined","1","0"),
                                     "ocho"=>GetSQLValueString(isset($_POST['checkbox8']) ? "true" : "", "defined","1","0"),
                                     "nueve"=>GetSQLValueString(isset($_POST['checkbox9']) ? "true" : "", "defined","1","0"),
                                     "diez"=>GetSQLValueString(isset($_POST['checkbox10']) ? "true" : "", "defined","1","0"),
                                     "once"=>GetSQLValueString(isset($_POST['checkbox11']) ? "true" : "", "defined","1","0"),
                                     "doce"=>GetSQLValueString(isset($_POST['checkbox12']) ? "true" : "", "defined","1","0"));

$ubicacion = new Ubicacion($datos_ubicacion);
$base->execute_query($ubicacion->SQL_Update_Ubicacion());

$base->close();

header("Location:ver_noticia_selector.php?id_noticia=".$noticia->getId()."&id_tipo_fuente=".$noticia->getId_tipo_fuente());

?>
