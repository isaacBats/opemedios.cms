<?php
/* 
 * Borra una terifa de una fuente electronica
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/TarifaElectronico.php");
include("phpclasses/Horario.php");

// iniciamos conexion
$base = new OpmDB(genera_arreglo_BD());
$base->init();


//metemos los horarios en un arreglo
$base->execute_query("SELECT * FROM horario WHERE id_horario=".$_GET['id_horario']);
$arreglo_horarios = array();
while($row_horarios = $base->get_row_assoc())
{
    $horario = new Horario($row_horarios);
    $arreglo_horarios[$horario->get_id()]=$horario;
}

//creamos objeto tarifa
$base->execute_query("SELECT * FROM cuesta_electronico WHERE id_fuente=".$_GET['id_fuente']." AND id_mes=".$_GET['id_mes']." AND id_horario=".$_GET['id_horario']);
$tarifa = new TarifaElectronico($base->get_row_assoc());
$tarifa->set_horario($arreglo_horarios[$_GET['id_horario']]);

//borramos tarifa
$base->execute_query($tarifa->SQL_delete_tarifa());

//cerramos conexion
$base->close();

header("Location: admin_tarifas_electronico.php?id_fuente=".$_GET['id_fuente']);

?>