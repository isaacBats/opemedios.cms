<?php
/* 
 * Action que inserta un tema en la base de datos y regresa a la pagina admin_secciones.php
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
//llamamos la clase DAO
include("phpdao/OpmDB.php");
//llamamos clases a utilizar
include("phpclasses/Seccion.php");


//usamos esta funcion para meter 1 o 0 en el campo activo
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
    switch ($theType)
    {
        case "defined":
            $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
            break;
    }
    return $theValue;
}


//obtenemos los datos del formulario y se meten en un arreglo para el objeto seccion
$datos_seccion = array("id_seccion"=>"",
                       "nombre"=>$_POST['nombre'],
                       "descripcion"=>$_POST['descripcion'],
                       "activo"=>GetSQLValueString(isset($_POST['activo']) ? "true" : "", "defined","1","0"),
                       "id_fuente"=>$_POST['id_fuente']);

//creamos el objeto seccion
$seccion = new Seccion($datos_seccion);

//creamos un DAO
$base = new OpmDB(genera_arreglo_BD());

//iniciamos conexion
$base->init();

//insertamos los datos en la base de datos
$base->execute_query($seccion->SQL_insert_seccion());

//cerramos conexion
$base->close();

//regresamos a la pagina
header("Location:admin_secciones.php?id_fuente=".$seccion->get_id_fuente());
?>
