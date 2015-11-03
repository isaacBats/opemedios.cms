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

//cerramos conexion
$base->free_result();
$base->close();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Agregar Noticia Sitio Web - OPM</title>
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
				<!--
					function MM_preloadImages() { //v3.0
						var d=document; 
							if(d.images){ 
								if(!d.MM_p) d.MM_p=new Array();
								var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
								if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}
							}
					}
				//-->
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
				<td width="533" height="25" class="label2">Noticias --&gt; <span class="label4">Publicacion de Noticias en Sitio Web</span></td>
				<td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
			  </tr>
			</table>
			<br>			
			<table width="900" border="0" align="center">
				<tr>
					<td align="center">&nbsp;</td>                           
				</tr>
				<tr>                            
					<td valign="top">
					
						<?php
						$posicion = $_POST['posicion'];
						$pagina = $_POST['pagina'];
						$uploadedfileload="true";
						$msg ="<p>";
						if ($pagina=1) {
							$uploaddir = "/var/www/vhosts/operamedios.com.mx/httpdocs/images/banner/";
						}
						else {
							$uploaddir = "Nota/banner/";
						}
						$uploadfile = $uploaddir.'Banner.png';
						if ($posicion == "1") {
							$uploadfile = $uploaddir.'Banner1.png';
						}
						if ($posicion == "2") {
							$uploadfile = $uploaddir.'Banner2.png';
						}
						if ($posicion == "3") {
							$uploadfile = $uploaddir.'Banner3.png';
						}
						$uploadedfile_size = $_FILES['pictures']['size'];
						$uploadedfile_tipo = $_FILES['pictures']['type'];
						if ($uploadedfile_size > 100000){
							$msg=$msg."El archivo es mayor que <b>100,000 Bytes</b>, debes reduzcirlo antes de subirlo, actualmente pesa: <b>".$uploadedfile_size."</b> Bytes<br>";
							$uploadedfileload="false";
						}
						if (($uploadedfile_tipo != "image/png")){
							$msg=$msg." Tu archivo tiene que ser en formato <b>PNG</b>. Esta intentando subir un archivo con formato: <b>".$uploadedfile_tipo."</b><br>";
							$uploadedfileload="false";
						}
						if($uploadedfileload=="true"){
							if (move_uploaded_file($_FILES['pictures']['tmp_name'], $uploadfile)) {
								echo "<span class='label3'>El banner número ". $posicion ." fue válido y cargado exitosamente.<br> Con un peso en Bytes de: ".$uploadedfile_size."</span>";
							}
							else{echo "<span class='label3'>Error al subir el banner, favor de intentarlo nuevamente.</span>";}
						}
						else{echo "<span class='label3' style='color:#800000'>El archivo contiene los siguientes errores:</span><br>".$msg;}						
						// Info de ayuda
						/*echo '<pre> Aquí hay más información del archivo:';
						print_r($_FILES);
						print "</pre>";*/
						echo "</p>";
						?> 						
					</td>
				</tr>
			</table>
			</td>
		</tr>
		</table>
		<?php include("includes/init_menu_principal.php");?>
	</body>
</html>