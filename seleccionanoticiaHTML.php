<?php
//llamamos el codigo de sesion para usuario nivel 3 = monitorista
//  include("phpdelegates/rest_access_3.php");
//include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
//include("db_array.php");

function HTML_noticia($tipofuente,$idnoticia,$base)
{
// llamamos las clases a utilizar
include("phpclasses/Noticia.php");
include("phpclasses/NoticiaElectronico.php");
include("phpclasses/TarifaElectronico.php");
//include("phpclasses/Usuario.php");
include("phpclasses/Horario.php");
include("phpclasses/Archivo.php");

//llamamos la clase  Data Access Object
//include("phpdao/OpmDB.php");

switch($tipofuente)
{
	case "1":
    case "2":

//funcion que convierte una hora en segundos, con el fin de comparar horarios al momento de buscar tarifas relacionadas
function strtimetosec($time)
{
    if(strlen($time) != 8)
    {
        return -1;
    }
    else
    {
        $horasstr = substr($time,0,2);
        $horas = intval($horasstr);
        if(!($horas == 0 && $horasstr != '00')) // si no hay error
        {
            $minsstr = substr($time,3,2);
            $mins = intval($minsstr);
            if(!($mins == 0 && $minsstr != '00')) // si no hay error
            {
                $segsstr = substr($time,6,2);
                $segs = intval($segsstr);
                if(!($segs == 0 && $segsstr != '00')) // si no hay error
                {
                    return ($horas*60*60)+($mins*60)+($segs);
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }

    }
}
//$base = new OpmDB(genera_arreglo_BD());
//$base->init();
//$base->execute_query("SELECT * FROM usuario WHERE username = '".$session_usr."'");// session_usr se encuentra en los includes de sesion
//$current_user = new Usuario($base->get_row_assoc());
$tabla_tipo = "";
if($tipofuente == 1)
{
    $tabla_tipo = "noticia_tel";
    $carpeta_tipo="television";
}
if($tipofuente == 2)
{
    $tabla_tipo = "noticia_rad";
    $carpeta_tipo="radio";
}
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
                          ".$tabla_tipo.".hora AS hora,
                          ".$tabla_tipo.".duracion AS duracion
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
                    WHERE
                         noticia.id_noticia =".$idnoticia.";");
//creamos el objeto NoticiaElectronico con los datos que nos regrese la consulta
$noticia = new NoticiaElectronico($base->get_row_assoc());
//hacemos consulta para obtener los datos del usuario Uploader, creamos el objeto y lo asignamos a la noticia
$user = $noticia->getId_usuario();
$user =1;
$base->execute_query("SELECT * FROM usuario WHERE id_usuario = ".$user.";");
$uploader = new Usuario($base->get_row_assoc());
$noticia->setUsuario($uploader);
//hacemos consulta para obtener los datos del archivo principal y creamos objeto Archivo para asignarlo a la noticia
$base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$idnoticia." AND principal = 1 LIMIT 1;");

if($base->num_rows() == 0)
{
    $principal = 0;
}
else
{
    $principal = new Archivo($base->get_row_assoc());
    $noticia->setArchivo_principal($principal);
}
//hacemos consulta para obtener los archivos secundarios  de la noticia
//por cada archivo que obtengamos generamos un objeto Archivo y lo asignamos a nuestra noticia
$base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$idnoticia." AND principal = 0 ;");
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
//metemos los horarios de las tarifas  en un arreglo
$base->execute_query("SELECT * FROM horario");
$arreglo_horarios = array();
while($row_horarios = $base->get_row_assoc())
{
    $horario = new Horario($row_horarios);
    $arreglo_horarios[$horario->get_id()]=$horario;
}
$mes = substr($noticia->getFecha(),5,2);
$idmes = date("n",mktime(00,00,00,$mes,01,2000)); // nos devuelve el valor sin ceros a la izquierda
//ahora buscamos en que horario entra la hora de la noticia, utilizando la funcion creada arriba
$horarioid = 0;
foreach($arreglo_horarios as $horario) // objetos horario
{
    if((strtimetosec($noticia->getHora()) >= strtimetosec($horario->get_hora_inicio())) && (strtimetosec($noticia->getHora()) <= strtimetosec($horario->get_hora_final())))
    {
        $horarioid = $horario->get_id();
    }
}
$base->execute_query("SELECT * FROM cuesta_electronico
                      WHERE id_fuente = ".$tipofuente."
                      AND id_horario = ".$horarioid."
                      AND id_mes = ".$idmes."
                      ORDER BY id_mes, id_horario");

if($base->num_rows() == 0)
{
    $tarifas = 0;
}
else
{
	$tarifas = $base->num_rows();
    $arreglo_tarifas = array();
    while($row_tarifa = $base->get_row_assoc())
    {
        $tarifa = new TarifaElectronico($row_tarifa);
        $tarifa->set_horario($arreglo_horarios[$row_tarifa['id_horario']]);
        $factor = ceil(strtimetosec($noticia->getDuracion())/strtimetosec($tarifa->get_tiempo()));
        $precio_noticia = $tarifa->get_precio() * $factor;
        $tarifa->setPrecio_noticia($precio_noticia);

        $arreglo_tarifas[$tarifa->get_id_fuente()."_".$tarifa->get_id_mes()."_".$tarifa->get_horario()->get_id()]=$tarifa;
    }
}
$base->free_result();
$base->close();
$html =
'                    <table width="1000" border="0">
                        <tr>
                            <td width="160">&nbsp;</td>
                            <td width="830">&nbsp;</td>
                        </tr>
                        <tr>
                            <td valign="top"><?php include("includes/menu_noticias.php");?></td>
                            <td valign="top"><table width="825" border="0">
                                    <tr>
                                        <td width="431" valign="top"><table width="430" border="0">
                                                <tr>
                                                    <td><span class="label2">Noticia #:</span> <span class="label5">'.$noticia->getId().'</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="label2"><div align="center">Encabezado:</div></td>
                                                </tr>
                                                <tr>
                                                    <td><div align="justify" class="label3">'. $noticia->getEncabezado().'</div></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="label2"><div align="center">Síntesis:</div></td>
                                                </tr>
                                                <tr>
                                                    <td class="label1"><div align="justify">'.$noticia->getSintesis().'</div></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label2">Autor</span>: <span class="label3">'.$noticia->getAutor().' ('.$noticia->getTipo_autor().')</span></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label2">Fecha:</span> <span class="label3">'.$noticia->getFecha_larga().'</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label2">Hora:</span> <span class="label3">'.$noticia->getHora().'</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label2">Duración:</span> <span class="label3">'.$noticia->getDuracion().'</span></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td><p><span class="label2">Fuente:</span> <span class="label3">'.$noticia->getFuente().'</span></p>                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label2">Sección:</span> <span class="label3">'.$noticia->getSeccion().'</span></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                </tr>

                                                <tr>
                                                    <td><span class="label2">Sector:</span> <span class="label3">'.$noticia->getSector().'</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label2">Género:</span> <span class="label3">'.utf8_encode($noticia->getGenero()).'</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label2">Tendencia:</span> <span class="label3">'.$noticia->getTendencia_monitorista().'</span></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td><div align="center" class="label2">Comentarios:</div></td>
                                                </tr>
                                                <tr>
                                                  <td><div align="justify" class="label1">'.$noticia->getComentario().'</div></td>
                                                </tr>
                                                <tr>
                                                  <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="label2">Usuario que subio la nota:</td>
                                                </tr>
                                                <tr>
                                                    <td class="label3">'.$uploader->get_nombre_completo().' ('.$uploader->get_cargo().')</td>
                                                </tr>
                                      </table></td>
                                        <td width="384" valign="top" class="label2">Tarifas Relacionadas:<br />
                                            <?php if ($tarifas != 0) { // Muestra solo si hay tarifas ?>
                                            <table width="380" border="0" align="center">
                                                <tr class="header2">
                                                    <td><div align="center">Mes</div></td>
                                                    <td><div align="center">Horario</div></td>
                                                    <td><div align="center">Tiempo</div></td>
                                                    <td><div align="center">Precio</div></td>
                                                    <td><div align="center">Costo Estimado</div></td>
                                                </tr';
                                                if(isset($arreglo_tarifas)){
                                                foreach ($arreglo_tarifas as  $tarifa_fuente)
                                                {
                                                    $html.= '<tr class="label1">';
                                                    $html.= '<td><div class="label2" align="center">'.$tarifa_fuente->get_mes().'</div></td>';
                                                    $html.= '<td><div class="label2" align="center">'.$tarifa_fuente->get_horario()->get_hora_inicio().' a '.$tarifa_fuente->get_horario()->get_hora_final().'</div></td>';
                                                    $html.= '<td><div class="label2" align="center">'.$tarifa_fuente->get_tiempo().'</div></td>';
                                                    $html.= '<td><div class="label2" align="center">$ '.$tarifa_fuente->get_precio().'</div></td>';
                                                    $html.= '<td><div class="label5" align="center">$ '.$tarifa_fuente->getPrecio_noticia().'</div></td>';
                                                    $html.= '</tr>';
                                                }
                                                }
                                            $html.='</table>
                                            <br />
                                            <br />';
                                    
                                            $html.='</table>';
                                             
                                        $html.='</td>
                                    </tr>
                            </table></td>
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
        </table>';

        $html1 = 'blablabala';
        return $html;
		//header("Location: ver_noticia_electronico.php?id_noticia=".$idnoticia."&id_tipo_fuente=2");
		break;

	case "3":
	case "4":

//llamamos archivos extras a utilizar
include("phpdelegates/thumbnailer.php");

// llamamos las clases a utilizar
include("phpclasses/NoticiaExtra.php");
include("phpclasses/TarifaPrensa.php");
include("phpclasses/Seccion.php");
include("phpclasses/Ubicacion.php");
		//header("Location: ver_noticia_prensa.php?id_noticia=".$idnoticia."&id_tipo_fuente=4" );
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();
//obtenemos la informacion del usuario y creamos el objeto
//$base->execute_query("SELECT * FROM usuario WHERE username = '".$session_usr."'");// session_usr se encuentra en los includes de sesion
//$current_user = new Usuario($base->get_row_assoc());

$tabla_tipo = "";
if($tipofuente == 3)
{
    $tabla_tipo = "noticia_per";
    $carpeta_tipo="periodico";
}
if($tipofuente == 4)
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
                          ".$tabla_tipo.".id_tamano_nota AS id_tamano_nota,
                          tipo_pagina.descripcion AS tipo_pagina,
                          tamano_nota.descripcion AS tamano_nota
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
                         INNER JOIN tamano_nota ON (".$tabla_tipo.".id_tamano_nota=tamano_nota.id_tamano_nota)
                    WHERE noticia.id_noticia = ".$idnoticia." LIMIT 1;");

//creamos el objeto NoticiaElectronico con los datos que nos regrese la consulta
$noticia = new NoticiaExtra($base->get_row_assoc(),$tipofuente);


//hacemos consulta para obtener los datos del usuario Uploader, creamos el objeto y lo asignamos a la noticia
$base->execute_query("SELECT * FROM usuario WHERE id_usuario = ".$noticia->getId_usuario().";");
$uploader = new Usuario($base->get_row_assoc());
$noticia->setUsuario($uploader);

//hacemos consulta para obtener los datos del archivo principal y creamos objeto Archivo para asignarlo a la noticia
$base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$idnoticia." AND principal = 1 LIMIT 1;");

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
$base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$idnoticia." AND principal = 0 ;");

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
$base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$idnoticia." AND principal = 2 LIMIT 1;"); // las paginas tienen principal = 2

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



//ahora vamos a buscar una tarifa que concuerde con las caracteristicas de nuestra noticia

//tenemos toda la informacion para obtener las tarifas

//metemos las tarifas en un arreglo
$arreglo_tarifas = array();
$tarifas = 0;

//si hay una tarifa con el tamaño exacto de la nota creamos solo una  con el precio establecido
$base->execute_query("SELECT * FROM cuesta_prensa
                      WHERE
                          id_fuente = ".$noticia->getId_fuente()."
                      AND id_seccion = ".$noticia->getId_seccion()."
                      AND id_tipo_pagina = ".$noticia->getId_tipo_pagina()."
                      AND id_tamano_nota = ".$noticia->getId_tamano_nota()."
                      ORDER BY id_seccion, id_tipo_pagina LIMIT 1;");

if($base->num_rows()>0)
{
    $tarifas = 1;

    while($row_tarifa = $base->get_row_assoc())
    {
        $tarifa = new TarifaPrensa($row_tarifa);
        $base->execute_query2("SELECT * FROM seccion WHERE id_seccion=".$row_tarifa['id_seccion']." LIMIT 1");
        $seccion = new Seccion($base->get_row_assoc2());
        $tarifa->set_seccion($seccion);
        $precio_noticia = $tarifa->get_precio();
        $tarifa->setPrecio_noticia($precio_noticia);
        $arreglo_tarifas[$tarifa->get_id_fuente()."_".$tarifa->get_seccion()->get_id()."_".$tarifa->get_id_tipo_pagina()."_".$tarifa->get_id_tamano_nota()]=$tarifa;
    }
}

else // si no hubo una con el tamaño exacto vamos a leer de todos los tamaños
{
    $base->execute_query("SELECT * FROM cuesta_prensa
                          WHERE
                              id_fuente = ".$noticia->getId_fuente()."
                          AND id_seccion = ".$noticia->getId_seccion()."
                          AND id_tipo_pagina = ".$noticia->getId_tipo_pagina()."
                          ORDER BY id_seccion, id_tipo_pagina ;");

    if($base->num_rows()>0)
    {
        $tarifas = $base->num_rows();

        while($row_tarifa = $base->get_row_assoc())
        {
            $tarifa = new TarifaPrensa($row_tarifa);
            $base->execute_query2("SELECT * FROM seccion WHERE id_seccion=".$row_tarifa['id_seccion']." LIMIT 1 ;");
            $seccion = new Seccion($base->get_row_assoc2());
            $tarifa->set_seccion($seccion);

            if($tarifa->get_id_tamano_nota() <= $noticia->getId_tamano_nota())
            {
                $precio_noticia = $tarifa->get_precio();
            }
            if($tarifa->get_id_tamano_nota() > $noticia->getId_tamano_nota())
            {
                $factor = pow(2, $tarifa->get_id_tamano_nota() - $noticia->getId_tamano_nota());
                $precio_noticia = $tarifa->get_precio() * $factor;
            }

            $tarifa->setPrecio_noticia($precio_noticia);

            $arreglo_tarifas[$tarifa->get_id_fuente()."_".$tarifa->get_seccion()->get_id()."_".$tarifa->get_id_tipo_pagina()."_".$tarifa->get_id_tamano_nota()]=$tarifa;
        }
    }
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

$base->execute_query("SELECT * FROM ubicacion WHERE id_noticia = ".$idnoticia." LIMIT 1;");
$ubicacion = new Ubicacion($base->get_row_assoc());


//cerramos conexion 
$base->free_result();
if($tarifas > 0)
{
    $base->free_result2();
}
$base->close();

    $html = 
    '        <table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
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
                            <td valign="top"><table width="825" border="0">
                                    <tr>
                                        <td width="431" valign="top"><table width="430" border="0">
                                                <tr>
                                                    <td><span class="label2">Noticia #:</span> <span class="label5"><?php echo $idnoticia; ?></span></td>
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
                                                    <td class="label2">Tamaño: <span class="label3"><?php echo $noticia->getTamano_nota();?></span></td>
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
                                                    <td class="label3"><?php echo $uploader->get_nombre_completo()." (".$uploader->get_cargo().")";?></td>
                                                </tr>
                                        </table></td>
                                        <td width="384" valign="top" class="label2">Tarifas Relacionadas:<br />
                                            <?php if ($tarifas != 0) { // Muestra solo si hay tarifas ?>
                                            <table width="380" border="0" align="center">
                                                <tr class="header2">
                                                    <td><div align="center">Sección</div></td>
                                                    <td><div align="center">Tipo Página</div></td>
                                                    <td><div align="center">Tamaño Nota</div></td>
                                                    <td><div align="center">Precio</div></td>
                                                    <td><div align="center">Costo Estimado</div></td>
                                                </tr>';
                                               
                                                foreach ($arreglo_tarifas as  $tarifa_fuente) {
                                                    $html ='<tr class="label1">';
                                                    $html ='<td><div class="label2" align="center">'.$tarifa_fuente->get_seccion()->get_nombre().'</div></td>';
                                                    $html ='<td><div class="label2" align="center">'.$tarifa_fuente->get_tipo_pagina().'</div></td>';
                                                    $html ='<td><div class="label2" align="center">'.$tarifa_fuente->get_tamano_nota().'</div></td>';
                                                    $html ='<td><div class="label2" align="center">$ '.$tarifa_fuente->get_precio().'</div></td>';
                                                    $html ='<td><div class="label5" align="center">$ '.$tarifa_fuente->getPrecio_noticia().'</div></td>';
                                                    $html ='</tr>';
                                                }
                                            $html ='</table>
                                            <?php } // End If ?>
                                            <br />
                                            <br />
                                            <?php if ($num_principal != 0) { // Muestra solo si hay archivo principal ?>
                                            <div class="contenedor" id="nota"><a href="<?php echo "data/noticias/".$carpeta_tipo."/".$noticia->getArchivo_principal()->getNombre_archivo()?>" target="_blank"><?php echo "<img src=\''.$thumb_archivo_principal->getThumbnailPath().'\' alt=\'Hacer clic para Agrandar\' title=\'Noticia\'/>";?></a></div>
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
                                                    echo"<tr class="label1">";
                                                    echo"<td><div class="label2" align="center"><a target="_blank" href="data/noticias/'.$carpeta_tipo.'/'.$alterno->getNombre_archivo().'">'.$alterno->getNombre().'</a></div></td>";
                                                    if(($current_user->get_id() == $noticia->getId_usuario()) || ($current_user->get_tipo_usuario() == 1) || ($current_user->get_tipo_usuario() == 2)){
                                                        echo"<td><div class="label2" align="center"><a  onclick="if(!confirm(\'Está seguro de borrar el archivo '.$alterno->getNombre().'?\'))return false" href="borra_archivo_secundario.php?id_noticia='.$noticia->getId().'&id_adjunto='.$alterno->getId().'&id_tipo_fuente='.$noticia->getId_tipo_fuente().'">Borrar</a></div></td>";}
                                                    echo"</tr>";
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
                                                    <td><?php if($rows_pagina > 0){?><div align="center" class="contenedor2" id="pagina_nota"><a href="<?php echo "data/noticias/".$carpeta_tipo."/".$noticia->getArchivo_pagina()->getNombre_archivo()?>" target="_blank"><?php echo "<img src=\''.$thumb_archivo_contenedor->getThumbnailPath().'\' alt=\'Hacer clic para Agrandar\' title=\'Página Contenedora\'/>";?></a></div> <?php }// end if rowspagina >0?></td>
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
                            </table></td>
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
        </table>';
		break;

	case "5":
		//header("Location: ver_noticia_internet.php?id_noticia=".$idnoticia);
		break;
}
}

?>
