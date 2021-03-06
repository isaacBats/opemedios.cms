<?php
/* 
 *Modifica los datos generales de una fuente de television
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Fuente.php");
include("phpclasses/FuenteRadio.php");

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

//creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();

//obtenemos los datos del formulario y se meten en un arreglo para el objeto empresa
$datos_fuente_radio = array("id_fuente"=>$_POST['id_fuente'],
                         "nombre"=>$_POST['nombre'],
                         "empresa"=>$_POST['empresa'],
                         "comentario"=>$_POST['comentario'],
                         "logo"=>"",
                         "activo"=>GetSQLValueString(isset($_POST['activo']) ? "true" : "", "defined","1","0"),
                         "id_tipo_fuente"=>2,
                         "id_cobertura"=>$_POST['id_cobertura'],
                         "conductor"=>$_POST['conductor'],
                         "estacion"=>$_POST['estacion'],
                         "horario"=>$_POST['horario'],);

//creamos el objeto FuenteRadio
$fuente = new FuenteRadio($datos_fuente_radio);

// actualizamos
$base->execute_query($fuente->SQL_UPDATE_FUENTE_RADIO());

$base->close();

header("Location:ver_fuente_radio.php?id_fuente=".$_POST['id_fuente']."&mensaje=Se modifico la fuente!!");

?>
