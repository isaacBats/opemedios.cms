<?php
/*
 * Action para agregar una nueva fuente de revista al sistema. recoje datos del formulario y
 * llama a la stored function  NEW_FUENTE_EXTRA despues manda a la pagina ver_fuente_prensa.php donde se puede continuar
 * con las modificaciones de la fuente
 *
 *@autor: Josue Morado Manríquez
 *
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Fuente.php");
include("phpclasses/FuenteExtra.php");


//usamos esta funcion para meter 1 o 0 en el campo activo
function GetSQLValueString1($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
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
$datos_fuente = array("id_fuente"=>"",
                       "nombre"=>$_POST['nombre'],
                       "empresa"=>$_POST['empresa'],
                       "comentario"=>$_POST['comentario'],
                       "logo"=>"",
                       "activo"=>GetSQLValueString1(isset($_POST['activo']) ? "true" : "", "defined","1","0"),
                       "id_tipo_fuente"=>4,
                       "id_cobertura"=>$_POST['id_cobertura'],
                       "tiraje"=>$_POST['tiraje']);

//creamos el objeto FuenteExtra
$nueva_fuente = new FuenteExtra($datos_fuente,4);


//creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
$base = new OpmDB(genera_arreglo_BD());


//iniciamos conexion
if($base->init()!=0)
{
    //si no hay error y nos da la conexion seguimos:
    //introducimos a base de datos el nuevo cliente
    if($base->execute_query($nueva_fuente->SQL_NEW_FUENTE_EXTRA())!=0)
    {
        //si hay exito, continuamos
        //obtenemos el valor del id que nos da la tabla y actualizamos el id del objeto empresa
        $id_registro = $base->get_row();
        $nueva_fuente->set_id($id_registro[0]);

        //cerramos la conexion
        $base->close();

        //terminamos. ahora redireccionamos a la siguiente pantalla: ver_fuente.php
        header("Location: ver_fuente_prensa.php?id_fuente=".$nueva_fuente->get_id()."&id_tipo_fuente=4");
        exit ();
    }
    else
    {
        //no se ejecuto el query
        echo $base->get_error();
        $base->close();
    }
}
else
{
    // no se conecto a la base de datos
    echo $base->get_error();
    $base->close();
}

//fin del action
?>
