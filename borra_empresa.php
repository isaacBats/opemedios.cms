<?php
 /* 
 * Action que borra  un archivo secundario de una noticia
 */

//llamamos los archivos necesarios
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Empresa.php");

//inicializamos variables
$idempresa = $_GET['id_empresa'];

//creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();

// eliminamos las asignaciones de noticias a esa empresa
$base->execute_query("DELETE FROM asigna WHERE id_empresa = ".$idempresa.";");
// eliminamos las cuentas de esa empresa
$base->execute_query("DELETE FROM cuenta WHERE id_empresa = ".$idempresa.";");
// eliminamos los temas
$base->execute_query("DELETE FROM tema WHERE id_empresa = ".$idempresa.";");
// eliminamos cliente
$base->execute_query("DELETE FROM empresa WHERE id_empresa = ".$idempresa." LIMIT 1;");

$base->close();

header("Location: admin_clientes.php");

?>