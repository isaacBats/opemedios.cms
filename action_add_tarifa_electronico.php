<?php
/*
 * Action para recojer datos de formulario y agragar una nueva tarifa a una fuente de medios electronica
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/TarifaElectronico.php");
include("phpclasses/Horario.php");

//obtenemos los datos del formulario y se meten en un arreglo para el objeto TarifaElectronico
$datos_tarifa = array("id_fuente"=>$_POST['id_fuente'],
                       "id_mes"=>$_POST['id_mes'],
                       "tiempo"=>date("H:i:s",mktime($_POST['tiempo_hh'],$_POST['tiempo_mm'],$_POST['tiempo_ss'],1,1,2000)),
                       "precio"=>$_POST['precio']);

//creamos el objeto empresa

$nueva_tarifa = new TarifaElectronico($datos_tarifa);

//creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
$base = new OpmDB(genera_arreglo_BD());
$base->init();

$base->execute_query("SELECT * FROM horario WHERE id_horario = ".$_POST['id_horario']);

$horario = new Horario($base->get_row_assoc());

$nueva_tarifa->set_horario($horario);

$base->execute_query($nueva_tarifa->SQL_insert_tarifa());

$base->close();



if($_POST['id_tipo_fuente']== 1)
{
    header("Location: ver_fuente_tv.php?id_fuente=".$_POST['id_fuente']);
    exit ();
}
else
{
    if($_POST['id_tipo_fuente']== 2)
    {
        header("Location: ver_fuente_radio.php?id_fuente=".$_POST['id_fuente']);
        exit();
    }
}

?>