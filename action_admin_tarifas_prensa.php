<?php
/* 
 * Borra una terifa de una fuente de prensa
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/TarifaPrensa.php");
include("phpclasses/Seccion.php");

// iniciamos conexion
$base = new OpmDB(genera_arreglo_BD());
$base->init();



//creamos objeto tarifa
$base->execute_query("SELECT * FROM cuesta_prensa WHERE id_fuente=".$_GET['id_fuente']." AND id_seccion=".$_GET['id_seccion']." AND id_tipo_pagina=".$_GET['id_tipo_pagina']);
$tarifa = new TarifaPrensa($base->get_row_assoc());

$base->execute_query("SELECT * FROM seccion WHERE id_seccion =".$_GET['id_seccion']);
$seccion = new Seccion($base->get_row_assoc());
$tarifa->set_seccion($seccion);

//borramos tarifa
$base->execute_query($tarifa->SQL_delete_tarifa());

//cerramos conexion
$base->close();

header("Location: admin_tarifas_prensa.php?id_fuente=".$_GET['id_fuente']);

?>