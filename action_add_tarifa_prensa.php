<?php
/*
 * Action para recojer datos de formulario y agragar una nueva tarifa a una fuente de medios IMPRESOS
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/TarifaPrensa.php");
include("phpclasses/Seccion.php");

//obtenemos los datos del formulario y se meten en un arreglo para el objeto TarifaPrensa
$datos_tarifa = array("id_fuente"=>$_POST['id_fuente'],
                       "id_tipo_pagina"=>$_POST['id_tipo_pagina'],
                       "id_tamano_nota"=>$_POST['id_tamano_nota'],
                       "precio"=>$_POST['precio']);

//creamos el objeto TarifaPrensa

$nueva_tarifa = new TarifaPrensa($datos_tarifa);

//creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
$base = new OpmDB(genera_arreglo_BD());
$base->init();

//obtenemos la seccion y la añadimos a la tarifa
$base->execute_query("SELECT * FROM seccion WHERE id_seccion=".$_POST['id_seccion']);
$seccion = new Seccion($base->get_row_assoc());
$nueva_tarifa->set_seccion($seccion);


$base->execute_query($nueva_tarifa->SQL_insert_tarifa());

$base->close();

header("Location: ver_fuente_prensa.php?id_fuente=".$_POST['id_fuente']."&id_tipo_fuente=".$_POST['id_tipo_fuente']);

?>
