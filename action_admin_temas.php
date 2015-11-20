<?php
/* 
 * Action que inserta un tema en la base de datos y regresa a la pagina admin_temas.php
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
//llamamos la clase DAO
include("phpdao/OpmDB.php");
//llamamos clases a utilizar
include("phpclasses/Tema.php");

//obtenemos los datos del formulario y se meten en un arreglo para el objeto tema
$datos_tema = array("id_tema"=>"",
                    "nombre"=>$_POST['nombre'],
                    "descripcion"=>$_POST['descripcion'],
                    "id_empresa"=>$_POST['id_empresa']);

//creamos el objeto tema
$tema = new Tema($datos_tema);

//creamos un DAO
$base = new OpmDB(genera_arreglo_BD());

//iniciamos conexion
$base->init();

//insertamos los datos en la base de datos
$base->execute_query($tema->SQL_insert_tema());

//cerramos conexion
$base->close();

//regresamos a la pagina
header("Location:admin_temas.php?id_empresa=".$tema->get_id_empresa());
?>
