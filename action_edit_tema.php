<?php
/* 
 * Action que actualiza la informacion de un tema en la base de datos
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Tema.php");

//obtenemos los datos del formulario y se meten en un arreglo para el objeto empresa
$datos_tema = array("id_tema"=>$_POST['id_tema'],
                       "nombre"=>$_POST['nombre'],
                       "descripcion"=>$_POST['descripcion'],
                       "id_empresa"=>$_POST['id_empresa']);

//creamos el objeto empresa
$tema = new Tema($datos_tema);

//creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
$base = new OpmDB(genera_arreglo_BD());


//iniciamos conexion
if($base->init()!=0)
{
    //si no hay error y nos da la conexion seguimos:
    //
    //actualizamos la informacion
    $base->execute_query($tema->SQL_update_tema());

    //cerramos conexion
    $base->close();

    //una vez listos los datos, vamos a la pagina de ver_cliente para verificar los cambios
    header("Location: admin_temas.php?id_empresa=".$tema->get_id_empresa());
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

