<?php
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");


	//creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
    $base = new OpmDB(genera_arreglo_BD());
	
    //iniciamos conexion
    $base->init();
	
	// escape variables for security
	$fuente = mysql_real_escape_string($_POST['fuente']);
	$minutos = mysql_real_escape_string($_POST['minutos']);
	$costo = mysql_real_escape_string($_POST['costo']);
	$duracion = mysql_real_escape_string($_POST['duracion_HH']).":".mysql_real_escape_string($_POST['duracion_MM']).":".mysql_real_escape_string($_POST['duracion_SS']);
	
	// formamos el SQL
	$sql=sprintf("INSERT INTO tarifario_radio (id_fuente, duracion, costo) VALUES ('%s', '%s', '%s')",$fuente,$duracion,$costo);
	
	//ejecutamos el query
	$base->execute_query($sql);
	//cerramos la conexión a la base de datos
	$base->close();
	
	header("Location:exito_tarifa.html?".$duracion);
?>