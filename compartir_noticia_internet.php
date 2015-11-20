<?php
//llamamos el codigo de sesion para usuario nivel 3 = monitorista
//include("phpdelegates/rest_access_3.php");
//include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/Noticia.php");
include("phpclasses/NoticiaExtra.php");
include("phpclasses/Usuario.php");
include("phpclasses/Seccion.php");
include("phpclasses/Archivo.php");

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

$tabla_tipo = "noticia_int";
$carpeta_tipo="internet";
$imagen="internet";
$imagen_compartir="bg-internet.png";


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
                          ".$tabla_tipo.".url AS url,
						  ".$tabla_tipo.".hora_publicacion AS hora_publicacion
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
                    WHERE noticia.id_noticia = ".$_GET['id_noticia']." LIMIT 1;");

//creamos el objeto NoticiaElectronico con los datos que nos regrese la consulta
$noticia = new NoticiaExtra($base->get_row_assoc(),5);


///hacemos consulta para obtener los datos del usuario Uploader, creamos el objeto y lo asignamos a la noticia
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


//cerramos conexion
$base->free_result();
$base->close();

?>


<!DOCTYPE html>
<html>
<head>
	<meta name="description" content="Notas Importantes">
	<meta charset="UTF-8">
	<title>OPEMEDIOS-Notas</title>
	<!--<link rel=stylesheet href="estilos.css">-->
	<meta property="og:title" content="<?php echo str_replace('"','',$noticia->getEncabezado());?>" />	
	<meta property="og:description" content="<?php echo addslashes($noticia->getSintesis());?>" />
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
				<a href="http://www.operamedios.com.mx"><img alt="Inicio-OperaMedios" src="Nota/images/logo111x42.png"></a>
			</div>
			<div id="header-ads">
				<img alt="" src="Nota/banner/VIDEOCINE2.jpg">
			</div>
		</div>
	</div>
	<div id="sub-menu-container">
		<div id="sub-menu">
			<div id="sub-menu-ul-container" style="margin-left: 0px;">
				<ul style="width: 625px;">
					<li><a href="http://www.operamedios.com.mx">Inicio</a></li>
					<li><a href="http://operamedios.com.mx/servicios.php">Servicios</a></li>					
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
							<div align="center">								
								<embed src="<?php echo "data/noticias/".$carpeta_tipo."/".$noticia->getArchivo_principal()->getNombre_archivo(); ?>" width="480" height="310" align="middle" border="3"></embed>								
							</div>
				     		<div class="flex-caption">
				                <h1><?php echo $noticia->getEncabezado();?></h1>
				                <p><?php echo $noticia->getSintesis();?>
									
									<br><span class="desc"><?php echo $noticia->getFuente();?> / <?php echo $noticia->getSeccion();?></span>									
								</p>
								<p><a href="<?php echo "data/noticias/".$carpeta_tipo."/".$noticia->getArchivo_principal()->getNombre_archivo(); ?>" href="_blank">Ver Nota completa Aquí</a></p>
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
	
	<div id="sidebar" style="position: relative; height: 500px;" class="masonry">
        	<ul>
        		<!-- Social Networks -->
        		<li class="masonry masonry-brick" style="position: absolute; top: 0px; left: 0px;">
        			<h3 class="widget-title">Redes Sociales</h3>
        			<div class="widget-container social-widget">
        				<a href="https://www.facebook.com/pages/Opemedios/586086271483511"><img alt="" src="Nota/img/facebook.png"></a>        				        				
        				<a href="https://twitter.com/DeMonitoreo"><img alt="" src="Nota/img/twitter.png"></a>        				
        			</div>
        		</li>
        		<!-- End Social Networks -->    
        			
        		<!-- Video Widget -->
        		<li class="masonry masonry-brick" style="position: absolute; top: 150px; left: 0px;">
        			<h3 class="widget-title">&nbsp;</h3>
        			<div class="widget-container video-widget" align="center">
        				<img src="Nota/banner/Derecho.jpg" alt="Publicidad" >
        			</div>
        		</li>
        		<!-- End Video Widget -->        		    	
        	</ul>
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
				<a href="http://operamedios.com.mx/quienes.php">Quienes Somos</a> | <a href="http://operamedios.com.mx/clientes.php">Clientes</a> | <a href="http://operamedios.com.mx/servicios.php">Servicios</a> | <a href="http://operamedios.com.mx/contacto.php">Contacto</a> | <a href="http://operamedios.com.mx/">Home</a><br>
				</td>	
			</tr>
			</tbody>
			</table>
		</div>
</div>
</body>
</html>