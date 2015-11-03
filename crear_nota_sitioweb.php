<?php
//llamamos el codigo de sesion para usuario nivel 3 = monitorista
//include("phpdelegates/rest_access_3.php");
//include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/Noticia.php");
include("phpclasses/NoticiaElectronico.php");
include("phpclasses/TarifaElectronico.php");
include("phpclasses/Usuario.php");
include("phpclasses/Horario.php");
include("phpclasses/Archivo.php");

//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");

//incluir imagen en nheader
//include("plugins/functions.php");

// funcion para obtener la URL
function dameURL(){
	$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	return $url;
}


//creamos un DAO para obtener los datos de la empresa dada mediante el metodo GET
//recibe como parametro el resultado de la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();
//obtenemos la informacion del usuario y creamos el objeto
$base->execute_query("SELECT * FROM usuario WHERE username = '".$session_usr."'");// session_usr se encuentra en los includes de sesion
$current_user = new Usuario($base->get_row_assoc());

$tabla_tipo = "";
if($_POST['id_tipo_fuente'] == 1){
    $tabla_tipo = "noticia_tel";
    $carpeta_tipo="television";
	$imagen="tele";
	$imagen_compartir="bg-tv.png";
}
if($_POST['id_tipo_fuente'] == 2){
    $tabla_tipo = "noticia_rad";
    $carpeta_tipo="radio";
	$imagen="radio";
	$imagen_compartir="bg-radio.png";
}

//hacemos consulta para la creacion del objeto NoticiaElectronico
$base->execute_query("SELECT 
                          noticia.id_noticia AS id_noticia,
                          noticia.encabezado AS encabezado,
                          noticia.sintesis AS sintesis,
                          noticia.autor AS autor,
                          noticia.fecha AS fecha,
                          noticia.comentario AS comentario,
                          noticia.id_tipo_fuente AS id_tipo_fuente,
                          noticia.id_fuente AS id_fuente,
                          noticia.id_seccion AS id_seccion,
                          noticia.id_sector AS id_sector,
                          noticia.id_tipo_autor AS id_tipo_autor,
                          noticia.id_genero AS id_genero,
                          noticia.id_tendencia_monitorista AS id_tendencia_monitorista,
                          noticia.id_usuario AS id_usuario,
                          fuente.nombre AS fuente,
                          seccion.nombre AS seccion,
                          sector.nombre AS sector,
                          tipo_fuente.descripcion AS tipo_fuente,
                          tipo_autor.descripcion AS tipo_autor,
                          genero.descripcion AS genero,
                          tendencia.descripcion AS tendencia_monitorista                         
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)                     
                    WHERE
                         noticia.id_noticia =".$_POST['id_noticia'].";");

//creamos el objeto NoticiaElectronico con los datos que nos regrese la consulta
$noticia = new NoticiaElectronico($base->get_row_assoc());


//hacemos consulta para obtener los datos del usuario Uploader, creamos el objeto y lo asignamos a la noticia
$base->execute_query("SELECT * FROM usuario WHERE id_usuario = ".$noticia->getId_usuario().";");
if($base->num_rows() != 0){
	$uploader_exist = 1;
	$uploader = new Usuario($base->get_row_assoc());
    $noticia->setUsuario($uploader);
}
else{
	$uploader_exist = 0;
}

//hacemos consulta para obtener los datos del archivo principal y creamos objeto Archivo para asignarlo a la noticia
$base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");

if($base->num_rows() == 0){
    $principal = 0;
}
else{
    $principal = new Archivo($base->get_row_assoc());
    $noticia->setArchivo_principal($principal);
}


//hacemos consulta para obtener los archivos secundarios  de la noticia
//por cada archivo que obtengamos generamos un objeto Archivo y lo asignamos a nuestra noticia
$base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 0 ;");

if($base->num_rows() == 0){
    $secundarios = 0;
}
else{
	$secundarios = $base->num_rows();
    while($row_archivo = $base->get_row_assoc())
    {
        $archivo = new Archivo($row_archivo);
        $noticia->addArchivo_alterno($archivo);
    }
}

//cerramos conexion
$base->free_result();
$base->close();
								/*<div id="video_background">
									<embed id="video_background" src="http://sistema.operamedios.com.mx/data/noticias/'.$carpeta_tipo.'/'.$noticia->getArchivo_principal()->getNombre_archivo().'" align="center">
								</div> */

								//<video autoplay="autoplay" id="video_background" preload="auto" controls>
									//	<source src="http://sistema.operamedios.com.mx/data/noticias/'.$carpeta_tipo.'/'.$noticia->getArchivo_principal()->getNombre_archivo().'">										
									//Your browser does not support the video tag.
								//</video>

$nombre_archivo = '/var/www/vhosts/operamedios.com.mx/httpdocs/PortalNuevo/includes/nota.html';
$contenido = 		'<div class="flexslider">
						<ul class="slides">
							<li style="width: 100%; float: left; margin-right: -100%; display: list-item;">	
								<video autoplay="autoplay" id="video_background" preload="auto" controls width="446">
									<source src="http://sistema.operamedios.com.mx/data/noticias/'.$carpeta_tipo.'/'.$noticia->getArchivo_principal()->getNombre_archivo().'">										
									Your browser does not support the video tag.
								</video>
								<div class="texto-nota">'.$noticia->getSintesis().'</div>								
								<div class="flex-caption">
									<h1>Noticias</h1>
									<h2>'.$noticia->getEncabezado().'</h2>
								</div>
							</li>
						</ul>
					</div>';

// Primero vamos a asegurarnos de que el archivo existe y es escribible.
if (is_writable($nombre_archivo)) {

    if (!$gestor = fopen($nombre_archivo, 'w')) {
         $mensaje = "No se puede abrir el archivo(".$nombre_archivo.")";
         exit;
    }

    // Escribir $contenido a nuestro archivo abierto.
    if (fwrite($gestor, $contenido) === FALSE) {
        $mensaje = "No se puede escribir en el archivo (".$nombre_archivo.")";
        exit;
    }
	
	$exito = 1;
    $mensaje = "Éxito, La nota fue publicada en el sitio web de operamedios.com.mx";
    fclose($gestor);

} else {
    $mensaje = "El archivo". $nombre_archivo." no es escribible<br> ERROR, no se publico la nota correctamente";
	$exito = 0;
}
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
		
        <script type="text/javascript" language="javascript" src="ajax_fuentes_secciones_1.js"></script>
        <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
        <script type="text/javascript" language="javascript">
	function seleccion_fuente(){
					var i = document.getElementById('id_fuente').selectedIndex;
					var valor = document.getElementById('id_fuente').options[i].value;
					sndReqCat(valor);
					document.form1.enviar.disabled=true;
	}
	function activa()
	{
		var i = document.getElementById('id_seccion').selectedIndex;
		var valor = document.getElementById('id_seccion').options[i].value;
		//alert(valor);
		if(valor == 0)
		{
			document.form1.enviar.disabled=true;
			//alert(document.form1.enviar.disabled);
		}
		else
		{
			document.form1.enviar.disabled=false;
			//alert(document.form1.enviar.disabled);
		}
	}
	
	function disable(){
		document.form1.enviar.value = 'Cargando Noticia.. Espere';
		document.form1.enviar.disabled = true;
	}


function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
	//x=valida_combo();
	var combo1 = document.getElementById("id_tendencia_monitorista");
	if(combo1.value == 0)
		errors+='- Tendencia debe seleccionar una opción.\n';
    for (i=0; i<(args.length-2); i+=3) { 
	  test=args[i+2]; 
	  val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' solo debe contener números (sin comas ni puntos).\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' debe contener un valor.\n'; }
    } if (errors) alert('Favor de atender lo siguiente:\n'+errors);
    document.MM_returnValue = (errors == '');
} }

<!--
function MM_preloadImages() { //v3.0
                var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
                    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
                        if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
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
			
			<table width="900" border="0">
				<tr>                            
					<td valign="top" align="center"><?php echo $mensaje; ?></td>
				</tr>
			</table>
			</td>
		  </tr>
		</table> 
	<?php include("includes/init_menu_principal.php");?>
    </body>
</html>	

