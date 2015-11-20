<?php
/* 
 * Codigo que actualiza los permisos del portal de una empresa
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
//llamamos la clase DAO
include("phpdao/OpmDB.php");
//llamamos clases a utilizar
include("phpclasses/Permiso.php");

//obtenemos los datos del formulario y se meten en un arreglo para el objeto tema
$datos_permiso = array("id_empresa"=>$_POST['id_empresa'],
                       "primeras_planas"=>$_POST['primeras_planas'],
                       "col_pol"=>$_POST['col_pol'],
                       "col_fin"=>$_POST['col_fin'],
                       "cartones"=>$_POST['cartones'],
                       "portadas_fin"=>$_POST['portadas_fin']);

//creamos el objeto tema
$permiso = new Permiso($datos_permiso);

//creamos un DAO
$base = new OpmDB(genera_arreglo_BD());

//iniciamos conexion
$base->init();

//insertamos los datos en la base de datos
$base->execute_query($permiso->SQL_update_permiso());

//cerramos conexion
$base->close();

//regresamos a la pagina
header("Location:ver_cliente.php?id_empresa=".$permiso->get_id_empresa());

?>

