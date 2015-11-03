<?php
/* 
 * Action que actualiza la informacion de un tema en la base de datos
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
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


//obtenemos los datos del formulario y se meten en un arreglo para el objeto empresa
$datos_seccion = array("id_seccion"=>$_POST['id_seccion'],
                       "nombre"=>$_POST['nombre'],
                       "descripcion"=>$_POST['descripcion'],
					   "activo"=>GetSQLValueString(isset($_POST['activo']) ? "true" : "", "defined","1","0"),
                       "id_fuente"=>$_POST['id_fuente']);

//creamos el objeto seccion
$seccion = new Seccion($datos_seccion);

//creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
$base = new OpmDB(genera_arreglo_BD());


//iniciamos conexion
if($base->init()!=0)
{
    //si no hay error y nos da la conexion seguimos:
    //
    //actualizamos la informacion
    $base->execute_query($seccion->SQL_update_seccion());

    //cerramos conexion
    $base->close();

    //una vez listos los datos, vamos a la pagina de ver_cliente para verificar los cambios
    header("Location: admin_secciones.php?id_fuente=".$seccion->get_id_fuente());
    exit ();
}
else
{
    // no se conecto a la base de datos
    $base->close();
    echo $base->get_error();
}

//fin del action

?>

