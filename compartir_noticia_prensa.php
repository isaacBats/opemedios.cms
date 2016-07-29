<?php
//llamamos el codigo de sesion para usuario nivel 3 = monitorista
//include("phpdelegates/rest_access_3.php");
//include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");
include("phpdelegates/thumbnailer.php");

// llamamos las clases a utilizar
include("phpclasses/Noticia.php");
include("phpclasses/NoticiaExtra.php");
include("phpclasses/TarifaPrensa.php");
include("phpclasses/Usuario.php");
include("phpclasses/Seccion.php");
include("phpclasses/Archivo.php");
include("phpclasses/Ubicacion.php");

//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");

// funcion para obtener la URL
function dameURL(){
	$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	return $url;
}

//recibe como parametro el resultado de la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();
//obtenemos la informacion del usuario y creamos el objeto
$base->execute_query("SELECT * FROM usuario WHERE username = '".$session_usr."'");// session_usr se encuentra en los includes de sesion
$current_user = new Usuario($base->get_row_assoc());

$tabla_tipo = "";
if($_GET['id_tipo_fuente'] == 3){
    $tabla_tipo = "noticia_per";
    $carpeta_tipo ="periodico";
	$imagen = "periodico";
	$imagen_compartir="bg-periodico.png";
}
if($_GET['id_tipo_fuente'] == 4){
    $tabla_tipo = "noticia_rev";
    $carpeta_tipo="revista";
	$imagen = "revista";
	$imagen_compartir="bg-revista.png";
}


//hacemos consulta para la creacion del objeto NoticiaExtra
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
                          tendencia.descripcion AS tendencia_monitorista,
                          ".$tabla_tipo.".pagina AS pagina,
                          ".$tabla_tipo.".id_tipo_pagina AS id_tipo_pagina,
                          ".$tabla_tipo.".porcentaje_pagina AS porcentaje_pagina,
                          tipo_pagina.descripcion AS tipo_pagina
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                         INNER JOIN ".$tabla_tipo." ON (noticia.id_noticia=".$tabla_tipo.".id_noticia)
                         INNER JOIN tipo_pagina ON (".$tabla_tipo.".id_tipo_pagina=tipo_pagina.id_tipo_pagina)
                    WHERE noticia.id_noticia = ".$_GET['id_noticia']." LIMIT 1;");

//creamos el objeto NoticiaElectronico con los datos que nos regrese la consulta
$noticia = new NoticiaExtra($base->get_row_assoc(),$_GET['id_tipo_fuente']);


//hacemos consulta para obtener los datos del usuario Uploader, creamos el objeto y lo asignamos a la noticia
$base->execute_query("SELECT * FROM usuario WHERE id_usuario = ".$noticia->getId_usuario().";");
if($base->num_rows() != 0)
{
	$uploader_exist = 1;
	$uploader = new Usuario($base->get_row_assoc());
    $noticia->setUsuario($uploader);
}
else
{
	$uploader_exist = 0;
}

//hacemos consulta para obtener los datos del archivo principal y creamos objeto Archivo para asignarlo a la noticia
$base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");

if($base->num_rows() == 0)
{
    $num_principal = 0;
}
else
{
    $num_principal = $base->num_rows();
    $principal = new Archivo($base->get_row_assoc());
    $noticia->setArchivo_principal($principal);
}


//hacemos consulta para obtener los archivos secundarios  de la noticia
//por cada archivo que obtengamos generamos un objeto Archivo y lo asignamos a nuestra noticia
$base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 0 ;");

if($base->num_rows() == 0)
{
    $secundarios = 0;
}
else
{
    $secundarios = $base->num_rows();
    while($row_archivo = $base->get_row_assoc())
    {
        $archivo = new Archivo($row_archivo);
        $noticia->addArchivo_alterno($archivo);
    }
}


//hacemos consulta para obtener los datos del archivo de la pagina donde se publico la nota y creamos objeto Archivo para asignarlo a la noticia
$base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 2 LIMIT 1;"); // las paginas tienen principal = 2

if($base->num_rows() == 0)
{
    $rows_pagina = 0;
}
else
{
    $rows_pagina = $base->num_rows();
    $pagina = new Archivo($base->get_row_assoc());
    $noticia->setArchivoPagina($pagina);
}



//ahora vamos aobtener el costo beneficio

//tenemos toda la informacion para obtener las tarifas

//metemos las tarifas en un arreglo
$arreglo_tarifas = array();
$tarifas = 0;

//si hay una tarifa con el tamaño exacto de la nota creamos solo una  con el precio establecido
$base->execute_query("SELECT * FROM cuesta_prensa
                      WHERE
                          id_fuente = ".$noticia->getId_fuente()."
                      AND id_seccion = ".$noticia->getId_seccion()."
                      AND id_tipo_pagina = ".$noticia->getId_tipo_pagina().";");

if($base->num_rows()>0)
{
    $tarifas = 1;

    while($row_tarifa = $base->get_row_assoc())
    {
        $tarifa = new TarifaPrensa($row_tarifa);
        $base->execute_query2("SELECT * FROM seccion WHERE id_seccion=".$row_tarifa['id_seccion']." LIMIT 1");
        $seccion = new Seccion($base->get_row_assoc2());
        $tarifa->set_seccion($seccion);
        $precio_noticia = $tarifa->get_precio() * ($noticia->getPorcentaje_pagina()/100);
        $tarifa->setPrecio_noticia($precio_noticia);
        $arreglo_tarifas[$tarifa->get_id_fuente()."_".$tarifa->get_seccion()->get_id()."_".$tarifa->get_id_tipo_pagina()]=$tarifa;
    }
}

else // si no hubo una con el tamaño exacto vamos a leer de todos los tamaños
{
    $tarifas = 0;
}


// creamos los thumbs de el archivo principal y el de la pagina contenedora de la nota
if($num_principal > 0)
{
    $thumb_archivo_principal = new thumbnail("data/noticias/".$carpeta_tipo."/".$noticia->getArchivo_principal()->getNombre_archivo(),"data/thumbs",370,285,70);
}

if($rows_pagina > 0)
{
    $thumb_archivo_contenedor = new thumbnail("data/noticias/".$carpeta_tipo."/".$noticia->getArchivo_pagina()->getNombre_archivo(),"data/thumbs",150,210,70);
}


// creamos el objeto de ubicacion de la noticia

$base->execute_query("SELECT * FROM ubicacion WHERE id_noticia = ".$noticia->getId()." LIMIT 1;");
$ubicacion = new Ubicacion($base->get_row_assoc());


//cerramos conexion
$base->free_result();
if($tarifas > 0)
{
    $base->free_result2();
}
$base->close();

?>


<!DOCTYPE html>
<html>
<head>
	<meta name="description" content="Notas Importantes">
	<meta charset="UTF-8">
	<title>OPEMEDIOS-Notas</title>
	<!--<link rel=stylesheet href="estilos.css">-->
	<meta property="og:title" content='<?php echo addslashes($noticia->getEncabezado());?>' />
	<meta property="og:description" content='<?php echo addslashes($noticia->getSintesis());?>' />
	<meta property="og:url" content="<?php echo dameURL();?>" />
	<meta property="og:image" content="http://sistema.operamedios.com.mx/Nota/img/<?php echo $imagen_compartir;?>" />
	<meta property="og:type" content="article" />
	<meta property="og:site_name" content="OPEMEDIOS" />	
    <meta property="fb:app_id" content="613416598777015" />
	<link rel="stylesheet" href="Nota/css/style.css" type="text/css" media="screen">
	<link class="skin" rel="stylesheet" href="Nota/css/style-1.css" type="text/css" media="screen">
	
	<script type="text/javascript">
		function compartirFacebook() {
			var u=location.href;
			window.open('http://www.facebook.com/share.php?u='+encodeURIComponent(u), '_blank', 'toolbar=no, scrollbars=no, resizable=no, top=100, left=100, width=600, height=380');
		}
		function compartirTwitter() {
			var u=location.href;
			window.open('http://twitter.com/intent/tweet?related=Operamedios&amp;text=Sugerencia <?php echo $noticia->getEncabezado();?>;url=<?php echo dameURL();?>', '_blank', 'toolbar=no, scrollbars=no, resizable=no, top=200, left=200, width=600, height=380');
		}
	</script>
	
</head>

<body>
	<div id="header-container" class="clearfix">
		<div id="header">
			<div id="logo">
				<a href="http://www.opemedios.com.mx/">
					<img alt="Inicio-OperaMedios" src="Nota/images/logo111x42.png" width="80" />
				</a>
			</div>
		</div>
	</div>
	<div id="sub-menu-container">
		<div id="sub-menu">
			<div id="sub-menu-ul-container" style="margin-left: 0px;">
				<ul style="width: 625px;">
					<!-- <li><a href="http://www.opemedios.com.mx/">Inicio</a></li>
					<li><a href="http://operamedios.com.mx/servicios.php">Servicios</a></li>
					<li><a href="#">Periodico</a></li>
					<li><a href="#">Television</a></li>
					<li><a href="#">Radio</a></li>
					<li><a href="#">Internet</a></li> -->
				</ul>
			</div>
		</div>
	</div>
	
	
<div id="container">
	<div id="content">
		<div class="slider-wrapper">
				<div id="slider-item-icon" class="<?php echo $imagen;?>"></div>
				<div class="flexslider" style="background: transparent url(Nota/img/nota_fondo.jpg) no-repeat center center">
					<ul class="slides">
						<li style="width: 100%; float: left; margin-right: -100%; display: list-item;">
							<!--
							<div align="center">
								<embed src="data/noticias/<?php echo $carpeta_tipo."/".$noticia->getArchivo_principal()->getNombre_archivo(); ?>>" width="370" height="285" align="middle" border="3"></embed>
							</div>
							-->
							<div align="center">
								<a href="<?php echo "data/noticias/".$carpeta_tipo."/".$noticia->getArchivo_principal()->getNombre_archivo()?>" target="_blank">
								<?php echo '<img src=\''.$thumb_archivo_principal->getThumbnailPath().'\' alt=\'Hacer clic para Agrandar\' title=\'Noticia\'/>';?></a>
							</div>                                          
							
				     		<!-- <img alt="" src="upload/buda.jpg"> -->
				     		<div class="flex-caption">
				                <h1><?php echo $noticia->getEncabezado();?></h1>
				                <p><?php echo $noticia->getSintesis();?>
									<br><span class="desc"><?php echo $noticia->getFuente();?> / <?php echo $noticia->getSeccion();?> </span>									
								</p>
				            </div>							
						</li>
				  	</ul>
				</div>
	            <div id="slider-item-0" class="slider-post-info active" style="z-index: 9; right: 0px;">
					<span class="date"><?php echo $noticia->getFecha_larga();?></span>
		      		<span class="comments2"><a href="#" onclick="compartirTwitter();">&nbsp;</a></span>
		       		<span class="comments"><a href="#" onclick="compartirFacebook();">&nbsp;</a></span>
		           	<span class="more-link"><a href="#">Compartir...</a></span>
		        </div>	          	           
	        
		</div>
	</div>
		<div id="footer">
			<table border="0" cellspacing="0" cellpadding="0" width="960px" align="center">
			<tbody><tr>
				<td valign="top" width="50%" align="left">
				<b>OPEMEDIOS</b><br>
					Calle Ures 69, Colonia Roma Sur, Delegación Cuauhtémoc, México D.F.<br>
					Tel: 5584.64.10  E-mail: atencion@opemedios.com.mx  <br>
				</td>
				<td align="right" valign="top" width="50%">
					<img src="images/trans.gif" width="1" height="15" alt=""><br>
					<a href="http://www.opemedios.com.mx/quienes.shtml" target="_blank" >Quienes Somos</a> 	| 
					<a href="http://www.opemedios.com.mx/clientes.shtml" target="_blank" >Clientes</a> 		| 
					<a href="http://www.opemedios.com.mx/servicios.shtml" target="_blank" >Servicios</a> 	| 
					<a href="http://www.opemedios.com.mx/contacto.shtml" target="_blank" >Contacto</a> 		| 
					<a href="http://www.opemedios.com.mx/">Home</a>
					<a href="https://www.facebook.com/pages/Opemedios/586086271483511">
    					<img alt="" src="Nota/img/facebook.png">
    				</a>        				        				
    				<a href="https://twitter.com/DeMonitoreo">
    					<img alt="" src="Nota/img/twitter.png">
    				</a><br>
				</td>	
			</tr>
			</tbody>
			</table>
		</div>
</div>
</body>
</html>

