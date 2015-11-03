<?php
/* 
 * Action que actualiza la informacion de un sector en la base de datos
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Sector.php");

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


$datos_sector = array("id_sector"=>$_POST['id_sector'],
                       "nombre"=>$_POST['nombre'],
                       "descripcion"=>$_POST['descripcion'],
                       "activo"=>GetSQLValueString(isset($_POST['activo']) ? "true" : "", "defined","1","0"));

//creamos el objeto empresa
$sector = new Sector($datos_sector);


//actualizamos la informacion
$base->execute_query($sector->SQL_update_sector());

//cerramos conexion
$base->close();

//una vez listos los datos, vamos a la pagina de ver_cliente para verificar los cambios
header("Location: admin_sectores.php?mensaje=Sector modificado!!");

?>
