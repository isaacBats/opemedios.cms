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

//recibe como parametro el resultado de la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();
//obtenemos la informacion del usuario y creamos el objeto
$base->execute_query("SELECT * FROM usuario WHERE username = '".$session_usr."'");// session_usr se encuentra en los includes de sesion
$current_user = new Usuario($base->get_row_assoc());

$tabla_tipo = "";
if($_GET['id_tipo_fuente'] == 3)
{
    $tabla_tipo = "noticia_per";
    $carpeta_tipo="periodico";
}
if($_GET['id_tipo_fuente'] == 4)
{
    $tabla_tipo = "noticia_rev";
    $carpeta_tipo="revista";
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
						  ".$tabla_tipo.".costo AS costo,
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
    $num_principal = 0;
}
else{
    $num_principal = $base->num_rows();
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

else { // si no hubo una con el tamaño exacto vamos a leer de todos los tamaños
    $tarifas = 0;
}


// creamos los thumbs de el archivo principal y el de la pagina contenedora de la nota
if($num_principal > 0){
    $thumb_archivo_principal = new thumbnail("data/noticias/".$carpeta_tipo."/".$noticia->getArchivo_principal()->getNombre_archivo(),"data/thumbs",370,285,70);
}

if($rows_pagina > 0){
    $thumb_archivo_contenedor = new thumbnail("data/noticias/".$carpeta_tipo."/".$noticia->getArchivo_pagina()->getNombre_archivo(),"data/thumbs",150,210,70);
}

// creamos el objeto de ubicacion de la noticia
$base->execute_query("SELECT * FROM ubicacion WHERE id_noticia = ".$noticia->getId()." LIMIT 1;");
$ubicacion = new Ubicacion($base->get_row_assoc());

//cerramos conexion
$base->free_result();
if($tarifas > 0){
    $base->free_result2();
}
$base->close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Ver Noticia de Medio Impreso</title>
        <style type="text/css">
            <!--
            body {
                background-color: #000000;
                margin-left: 0px;
                margin-top: 0px;
                margin-right: 0px;
                margin-bottom: 0px;
            }
            -->
        </style>
        <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
        <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
        <link href="CSS/opemedios.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript">
            <!--
            function MM_preloadImages() { //v3.0
                var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
                    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
                        if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
                }
                //-->
        </script>
        <link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
                <td width="1000" height="500" valign="top" background="images/images/BackGround_02bg.jpg"><table width="1000" border="0" cellpadding="0" cellspacing="0" background="images/images/BackGround_02.jpg">
                        <tr valign="top">
                            <td height="25" colspan="3"><?php include("includes/mainmenu.php");?></td>
                        </tr>
                        <tr valign="middle">
                            <td width="15" height="25">&nbsp;</td>
                            <td width="533" height="25" class="label2">Noticias --&gt; <span class="label4">Ver Noticia de <?php if($noticia->getId_tipo_fuente()==3){echo "Periódico";}else{echo "Revista";}?></span></td>
                            <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                        </tr>
                    </table>
                    <table width="1000" border="0">
                        <tr>
                            <td width="160">&nbsp;</td>
                            <td width="830">&nbsp;</td>
                        </tr>
                        <tr>
                            <td valign="top"><?php include("includes/menu_noticias.php");?></td>
                            <td valign="top">
							<table width="825" border="0">
                                    <tr>
                                        <td width="431" valign="top"><table width="430" border="0">
                                                <tr>
                                                    <td><span class="label2">Noticia #:</span> <span class="label5"><?php echo $noticia->getId(); ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td class="label2"><div align="center">Encabezado:</div></td>
                                                </tr>
                                                <tr>
                                                    <td><div align="justify" class="label3"><?php echo $noticia->getEncabezado();?></div></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="label2"><div align="center">Síntesis:</div></td>
                                                </tr>
                                                <tr>
                                                    <td class="label1"><div align="justify"><?php echo $noticia->getSintesis();?></div></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label2">Autor</span>: <span class="label3"><?php echo $noticia->getAutor()." (".$noticia->getTipo_autor().")";?></span></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label2">Fecha:</span> <span class="label3"><?php echo $noticia->getFecha_larga();?></span></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label2">Página: </span> <span class="label3"><?php echo $noticia->getPagina()." (".$noticia->getTipo_pagina().")";?></span></td>
                                                </tr>

                                                <tr>
                                                    <td class="label2">Tamaño(%): <span class="label3"><?php echo $noticia->getPorcentaje_pagina();
?></span></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td><p><span class="label2">Fuente:</span> <span class="label3"><?php echo $noticia->getFuente();?></span></p>                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label2">Sección:</span> <span class="label3"><?php echo $noticia->getSeccion();?></span></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                </tr>

                                                <tr>
                                                    <td><span class="label2">Sector:</span> <span class="label3"><?php echo $noticia->getSector();?></span></td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label2">Género:</span> <span class="label3"><?php echo utf8_encode($noticia->getGenero());?></span></td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label2">Tendencia:</span> <span class="label3"><?php echo $noticia->getTendencia_monitorista();?></span></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td><div align="center" class="label2">Comentarios:</div></td>
                                                </tr>
                                                <tr>
                                                    <td><div align="justify" class="label1"><?php echo $noticia->getComentario();?></div></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="label2">Usuario que subio la nota:</td>
                                                </tr>
                                                <tr>
                                                    <td class="label3"><?php if($uploader_exist){echo $uploader->get_nombre_completo()." (".$uploader->get_cargo().")";}?></td>
                                                </tr>
                                        </table></td>
                                        <td width="384" valign="top" class="label2">
											Costo beneficio: <span class="label5">$ <?php echo number_format($noticia->getCosto(),2); ?></span><br>&nbsp;<br>
											Tarifas Relacionadas:<br />
                                            <?php if ($tarifas != 0) { // Muestra solo si hay tarifas ?>
                                            <table width="380" border="0" align="center">
                                                <tr class="header2">
                                                    <td><div align="center">Sección</div></td>
                                                    <td><div align="center">Tipo Página</div></td>
                                                    <td><div align="center">Costo Página</div></td>
                                                    <td><div align="center">Tamaño (%)</div></td>
                                                    <td><div align="center">Costo Nota</div></td>
                                                </tr>
                                                <?php
                                                foreach ($arreglo_tarifas as  $tarifa_fuente) {
                                                    echo'<tr class="label1">';
                                                    echo'<td><div class="label2" align="center">'.$tarifa_fuente->get_seccion()->get_nombre().'</div></td>';
                                                    echo'<td><div class="label2" align="center">'.$tarifa_fuente->get_tipo_pagina().'</div></td>';
                                                    echo'<td><div class="label2" align="center">$ '.$tarifa_fuente->get_precio().'</div></td>';
                                                    echo'<td><div class="label2" align="center"> '.$noticia->getPorcentaje_pagina().' %</div></td>';
                                                    echo'<td><div class="label5" align="center">$ '.$tarifa_fuente->getPrecio_noticia().'</div></td>';
                                                    echo'</tr>';
                                                }
                                                ?>
                                            </table>
                                            <?php } // End If ?>
                                            <br />
                                            <br />
                                            <?php if ($num_principal != 0) { // Muestra solo si hay archivo principal ?>
                                            <div class="contenedor" id="nota"><a href="<?php echo "data/noticias/".$carpeta_tipo."/".$noticia->getArchivo_principal()->getNombre_archivo()?>" target="_blank"><?php echo '<img src=\''.$thumb_archivo_principal->getThumbnailPath().'\' alt=\'Hacer clic para Agrandar\' title=\'Noticia\'/>';?></a></div>
                                            <a href="data/noticias/<?php echo $carpeta_tipo."/".$noticia->getArchivo_principal()->getNombre_archivo(); ?>" class="label2">Descarga Aqui</a>
                                            <?php } // End If ?>
                                            <br />
                                            <br />
                                            <?php if ($secundarios != 0) { // Muestra solo si hay archivo principal ?>
                                            Archivos Secundarios:<br />
                                            <table width="350" border="0">
                                                <tr class="header2">
                                                    <td><div align="center">Archivo</div></td>
                                                    <td><div align="center">Acciones</div></td>
                                                </tr>
                                                <?php
                                                foreach ($noticia->getArchivos_alternos() as  $alterno) {
                                                    echo'<tr class="label1">';
                                                    echo'<td><div class="label2" align="center"><a target="_blank" href="data/noticias/'.$carpeta_tipo.'/'.$alterno->getNombre_archivo().'">'.$alterno->getNombre().'</a></div></td>';
                                                    if(($current_user->get_id() == $noticia->getId_usuario()) || ($current_user->get_tipo_usuario() == 1) || ($current_user->get_tipo_usuario() == 2)){
                                                        echo'<td><div class="label2" align="center"><a  onclick="if(!confirm(\'Está seguro de borrar el archivo '.$alterno->getNombre().'?\'))return false" href="borra_archivo_secundario.php?id_noticia='.$noticia->getId().'&id_adjunto='.$alterno->getId().'&id_tipo_fuente='.$noticia->getId_tipo_fuente().'">Borrar</a></div></td>';}
                                                    echo'</tr>';
                                                }
                                                ?>
                                            </table>
                                            <p>
                                                <?php } // End If ?>
                                            </p>
                                            <table width="384" border="0">
                                                <tr>
                                                    <td width="222" colspan="3"><div align="center">Página donde se publicó la nota:</div></td>
                                                    <td width="152"><div align="center">Ubicación de la nota:</div></td>
                                                </tr>
                                                <tr>
                                                    <td width="25">&nbsp;</td>
                                                    <td><?php if($rows_pagina > 0){?><div align="center" class="contenedor2" id="pagina_nota"><a href="<?php echo "data/noticias/".$carpeta_tipo."/".$noticia->getArchivo_pagina()->getNombre_archivo()?>" target="_blank"><?php echo '<img src=\''.$thumb_archivo_contenedor->getThumbnailPath().'\' alt=\'Hacer clic para Agrandar\' title=\'Página Contenedora\'/>';?></a></div> <?php }// end if rowspagina >0?></td>
                                                    <td>&nbsp;</td>
                                                    <td align="center">
                                                        <table width="80" height="121" border="0">

                                                            <tr>
                                                                <td class="<?php if($ubicacion->getUno() == 1) {echo "ubicacion1";}else {echo "ubicacion";} ?>">&nbsp;</td>
                                                                <td class="<?php if($ubicacion->getDos() == 1) {echo "ubicacion1";}else {echo "ubicacion";} ?>">&nbsp;</td>
                                                                <td class="<?php if($ubicacion->getTres() == 1) {echo "ubicacion1";}else {echo "ubicacion";} ?>">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="<?php if($ubicacion->getCuatro() == 1) {echo "ubicacion1";}else {echo "ubicacion";} ?>">&nbsp;</td>
                                                                <td class="<?php if($ubicacion->getCinco() == 1) {echo "ubicacion1";}else {echo "ubicacion";} ?>">&nbsp;</td>
                                                                <td class="<?php if($ubicacion->getSeis() == 1) {echo "ubicacion1";}else {echo "ubicacion";} ?>">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="<?php if($ubicacion->getSiete() == 1) {echo "ubicacion1";}else {echo "ubicacion";} ?>">&nbsp;</td>
                                                                <td class="<?php if($ubicacion->getOcho() == 1) {echo "ubicacion1";}else {echo "ubicacion";} ?>">&nbsp;</td>
                                                                <td class="<?php if($ubicacion->getNueve() == 1) {echo "ubicacion1";}else {echo "ubicacion";} ?>">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="<?php if($ubicacion->getDiez() == 1) {echo "ubicacion1";}else {echo "ubicacion";} ?>">&nbsp;</td>
                                                                <td class="<?php if($ubicacion->getOnce() == 1) {echo "ubicacion1";}else {echo "ubicacion";} ?>">&nbsp;</td>
                                                                <td class="<?php if($ubicacion->getDoce() == 1) {echo "ubicacion1";}else {echo "ubicacion";} ?>">&nbsp;</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            <p>&nbsp;</p>
                                        </td>
                                    </tr>
                            </table>
							<br>
							<table width="825" border="0">
								<tr>
								<td><span class="label2">Liga para compartir en Redes Sociales:&nbsp;</span><span class="label3"><a href="compartir_noticia_prensa.php?id_noticia=<?php echo $noticia->getId()."&id_tipo_fuente=".$noticia->getId_tipo_fuente();?>" target="_blank"> COMPARTIR</a></span></td>
								<td>&nbsp;</td>
								</tr>
							</table>
							</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <?php include("includes/init_menu_principal.php");?>
        <?php include("includes/init_menu_empresas.php");?>
    </body>
</html>