<?php
//llamamos el codigo de sesion para usuario nivel 3 = monitorista
include("phpdelegates/rest_access_3.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");
// llamamos las clases a utilizar
include("phpclasses/Usuario.php");
//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");
//creamos un DAO
//recibe como parametro el resultado de la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();
//obtenemos la informacion del usuario y creamos el objeto
$base->execute_query("SELECT * FROM usuario WHERE username = '".$session_usr."'");// session_usr se encuentra en los inclides de sesion
$current_user = new Usuario($base->get_row_assoc());

$SQL_fecha = "";
if($_GET['F1'] != ""){
    $SQL_fecha = "Periodo de Busqueda del ". $_GET['F1']." al ".$_GET['F2'];
	$SQL="SELECT noticia.id_usuario, usuario.nombre, count(*) notas FROM noticia
	INNER JOIN usuario ON noticia.id_usuario=usuario.id_usuario
	WHERE (fecha BETWEEN '".$_GET['F1']."' AND '".$_GET['F2']."')
	GROUP BY id_usuario
	Order BY 3 DESC";
}
else {
	$SQL_fecha = "Periodo de Busqueda de ".date('Y-m-d');
	$SQL="SELECT noticia.id_usuario, usuario.nombre, count(*) notas FROM noticia
	INNER JOIN usuario ON noticia.id_usuario=usuario.id_usuario
	WHERE fecha = CURDATE()
	GROUP BY id_usuario
	Order BY 3 DESC";
}

$base->execute_query($SQL); // query para obtener el numero de notas por dia y por usuario

$num_fila = $base->num_rows();

//cerramos conexion
//$base->free_result();
//$base->close();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Publica Banner Sitio Web - OPM</title>
        <style type="text/css">
            <!--
            @import url("CSS/opemedios.css");
            body {
				margin-left: 0px;
				margin-top: 0px;
				margin-right: 0px;
				margin-bottom: 0px;
				background-image: url(images/noticia_bg.jpg);
            }
            -->
        </style>
		<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
		<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
		<link href="CSS/opemedios.css" rel="stylesheet" type="text/css" />
		
		<script type="text/javascript" language="javascript">
			function MM_preloadImages() { //v3.0
				var d=document; 
					if(d.images){ 
						if(!d.MM_p) d.MM_p=new Array();
						var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
						if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}
					}
			}				
		</script>
    </head>

    <body>	
	<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
		  <!--DWLayoutTable-->
		  <tr>
			<td width="1000" height="500" valign="top" background="images/images/BackGround_02bg.jpg">
			<table width="1000" border="0" cellpadding="0" cellspacing="0" background="images/images/BackGround_02.jpg">
			  <tr valign="top">
				<td height="25" colspan="3"><?php include("includes/mainmenu.php");?></td>
			  </tr>
			  <tr valign="middle">
				<td width="15" height="25">&nbsp;</td>
				<td width="533" height="25" class="label2">Reportes --&gt; <span class="label4">Reporte Diario</span></td>
				<td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
			  </tr>
			</table>
			<br>&nbsp;<br>			
			<form action="reporte1.php" method="get">
			<table border="1" align="center" cellpadding="0" cellspacing="0">
				<tr bgcolor="#cfb9bc">
					<td align="right">Fecha Inicio (aaaa-mm-dd):</td>
					<td align="center"><input type="text" value="" name="F1"></td>
				</tr>
				<tr bgcolor="#cfb9bc">
					<td align="right">Fecha Fin (aaaa-mm-dd): </td>
					<td align="center"><input type="text" value="" name="F2"></td>
				</tr>
				<tr>					
					<td colspan="2" align="center"><input type="submit" value="Ver"></td>
				</tr>
			</table>
			</form>
			<br>
			<table border="1" align="center" cellpadding="0" cellspacing="0" width="900">
				<tr>
					<td colspan="4" bgcolor="#885159" align="center"><font color="white">Reporte de número de notas por usuarios <b><?php echo $SQL_fecha; ?></b>&nbsp;</font></td>
				</tr>
				<tr bgcolor="#cfb9bc" align="center">
					<td width="50"><b>Id Usuario</b></td>
					<td width="120"><b>Nombre</b></td>
					<td width="50"><b>Número de Notas</b></td>
					<td></td>
				</tr>
				
				<?php				
				while ($fila = $base->get_row_assoc()) {
					echo '<tr><td align="center">'.$fila['id_usuario'].'</td>';
					echo '<td>'.$fila['nombre'].'</td>';
					echo '<td align="center">'.$fila['notas'].'</td>';
					$barra = ($fila['notas']) / 100;
					echo '<td><div style="width:'.$barra.'%; background-color:#BDDA4C">&nbsp;</div></td></tr>';
				}
				?>
			</table>
			<br>
			</td>
		</tr>
	</table>
	<?php include("includes/init_menu_principal.php");?>
	</body>
</html>
<?php
//cerramos conexion
$base->free_result();
$base->close();
?>