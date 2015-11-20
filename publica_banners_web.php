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
				<td width="533" height="25" class="label2">Noticias --&gt; <span class="label4">Publicacion de Banners (Sitio Web)</span></td>
				<td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
			  </tr>
			</table>
			<br>
			<form enctype="multipart/form-data" action="action_add_banners.php" method="POST">
			<table width="960" border="0" align="center">
				<tr>
					<td align="left">
						<span class="label3"> Favor de Subir la imagen correspondiente al banner que desea publicar.</span><br>
						&nbsp;<br>
						<span class="label2">Considere lo siguiente:<br>
						- El archivo debe ser en <b>Formato ".png"</b> <br>
						- El tamaño del archivo no debe sobre pasar los <b>100 KB</b> <br>
						- Las dimensiones máximas de la imagen son 728 ancho X 90 largo pixeles<b>(728 x 90)</b>.</span><br>
						&nbsp;<br>
					</td>  
					<td align="right">
						<img src="images/banners_web1.png">
					</td>
				</tr>
				<tr>                            
					<td valign="top" align="center">
						<span class="label3">
						<input type="radio" name="posicion" value="1"> Banner 1 &nbsp;
						<input type="radio" name="posicion" value="2"> Banner 2 &nbsp;
						<input type="radio" name="posicion" value="3"> Banner 3 &nbsp;
						</span>
					</td>
				</tr>
				<tr>                            
					<td valign="top" align="center">						
						<!-- MAX_FILE_SIZE debe preceder el campo de entrada de archivo -->
						<input type="hidden" name="MAX_FILE_SIZE" value="300000" />
						<!-- El nombre del elemento de entrada determina el nombre en el array $_FILES -->
						<span class="label3"> Enviar este archivo: </span>
						<input name="pagina" type="hidden" value="1"/>
						<input name="pictures" type="file" />											
					</td>
				</tr>
				<tr>
					<td valign="top" align="center">
						<span class="label3"> Liga del Banner: </span>
						<input name="liga" type="text" size="40" maxlength="50">
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">&nbsp;</td>
				</tr>
				<tr>
					<td valign="top" align="right">
						<input type="submit" value="Send File" />
					</td>
				</tr>
			</table>
			</form>
			</td>
		</tr>
	</table>
	<?php include("includes/init_menu_principal.php");?>
	</body>
</html>