<?php
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");

$id_fuente_forma = "0001";
// escape variables for security
//$id_fuente_forma = mysql_real_escape_string($_GET['fuente']);
$id_fuente_forma = $_GET['fuente'];
	
	
	//creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
    $base = new OpmDB(genera_arreglo_BD());
	
    //iniciamos conexion
    $base->init();
	
	//creamos el SQL	
	$sql="SELECT duracion, costo FROM tarifario_radio WHERE id_fuente =".$id_fuente_forma ." order by duracion";
		
	//creamos un arreglo para mostrar los costos
	$base->execute_query($sql);	
	$arreglo_costo = array();
	while($costo = $base->get_row_assoc()){
		$arreglo_costo[$costo["duracion"]] = $costo["costo"];
	}
	
	//creamos un arreglo para mostrar las fuentes de radio
	$base->execute_query("SELECT id_fuente, nombre FROM fuente WHERE id_tipo_fuente = 2 AND activo = 1 ORDER BY nombre");
	$arreglo_fuentes = array();
	while($fuente = $base->get_row_assoc()){
		$arreglo_fuentes[$fuente["id_fuente"]] = $fuente["nombre"];
		if ($fuente["id_fuente"] == $id_fuente_forma){
			$nombre_fuente = $fuente["nombre"];
		}
	}
	
	//cerramos conexion
	$base->free_result();
	$base->close();
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tarifas de Radio</title>
<script type="text/javascript" language="javascript">
function ve_tarifa(dato) {
 //var data = window.document.getElementById('val0').innerHTML;
 alert(dato);
 window.opener.document.getElementById('costo').value = dato; 
 
 //this.window.close();
 }
</script>
</head>
 <body>
 <table border="1" width="300">
 <tr>
	 <td colspan="3"><b><?php echo $nombre_fuente; ?></b></td>
 </tr>
 <tr align="center">
	<td><b>Duración</b></td>
	<td><b>Costo</b></td>
	<td>&nbsp;</td>
 </tr>
	 
<?php
$i=0;
foreach ($arreglo_costo as $value => $label){
 echo '<tr align="center"><td>'.$value.'</td><td id="val'.$i.'">'.$label.'</td><td><input type="button" value=" OK " onclick="ve_tarifa('.$label.');"></td></tr>';
 $i++;
 }
?>
 </table>

 </body>
</html> 