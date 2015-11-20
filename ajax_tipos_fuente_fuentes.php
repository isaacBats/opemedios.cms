<?php
/**
 * Obtiene las fuentes de un tipo de fuente
 */
$tipo_fuente	= $_REQUEST['t'];

include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");

//creamos un DAO
//recibe como parametro el resultado de la funcion
$base_ajax = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base_ajax->init();

if($tipo_fuente == 0)
{
 //   $base_ajax->execute_query("SELECT `id_formato`,`nombre` FROM `formato`;");
}
else
{
    $base_ajax->execute_query("SELECT `id_fuente`,`nombre` FROM `fuente` WHERE `id_tipo_fuente` = ".$tipo_fuente." ORDER BY nombre;");	// Consultamos todas las fuentes
}

$resp = array();
while ($fila = $base_ajax->get_row())	// $fila[0] = id_formato  $fila[1] = nombre
{
    $resp[]= $fila;
}

if(count($resp)<=0)
{
    $output = "$fuente|<span class=\"label2\"><strong>No hay fuentes de este tipo</strong></span>";
    echo $output;
    return true;
}
else
{
    $salto="<br /><br />";

    $new_back = array();

    $new_back[] .= '<select name="id_fuente" id="id_fuente" class="combo3" onchange="seleccion_fuente()">';
	$new_back[] .= '<option value="0">**Todas las Fuentes**</option>';
    foreach($resp as $sub)
    {
        $new_back[] .= '<option value="'.$sub[0].'" '.$add.'>'.$sub[1].'</option>';
    }
    $new_back[] .= '</select>';

    $allnewback = join("", $new_back);

    // ========================

    //name of the div id to be updated | the html that needs to be changed
    $output = "$tipo_fuente|$allnewback";
    echo $output;
    return true;
}

$base_ajax->close();
?>
