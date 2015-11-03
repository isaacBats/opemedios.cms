<?php
/* 
 * Action que agrega un nuevo sector, revisando que no haya uno con el mismo nombre
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

//obtenemos los datos del formulario y se meten en un arreglo para el objeto Sector
$datos_sector = array("id_sector"=>"",
                      "nombre"=>$_POST['nombre'],
                      "descripcion"=>$_POST['descripcion'],
                      "activo"=>GetSQLValueString(isset($_POST['activo']) ? "true" : "", "defined","1","0"));

//revisamos si el sector  ya se encuentra registrado

if($base->exists_sector($_POST['nombre']))
{
    $base->close();

    //regresamos a la misma pagina indicando el error
    header("Location:add_sector.php?&mensaje=El sector: ".$_POST['nombre']." ya esta registrado
                                     &error=true");
    exit ();
}
else
{
    //creamos el objeto Sector
    $nuevo_sector = new Sector($datos_sector);

    // hacemos la insercion a base de datos
    $base->execute_query($nuevo_sector->SQL_insert_sector());

    $base->close();
    header("Location:admin_sectores.php?&mensaje=Se ha agregado una nuevo sector!!");

    exit ();
}

//fin del action
?>
