<?php
/*
 * Action para asignar una noticia al portal de un cliente, a la vez de mandar un correo a todos las cuentas seleccionadas
 *@autor: Oscar León Gochar
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Empresa.php");
include("phpclasses/Cuenta.php");
include("phpclasses/Noticia.php");
include("phpclasses/NoticiaElectronico.php");
include("phpclasses/NoticiaExtra.php");
include("phpclasses/TarifaPrensa.php");
include("phpclasses/Seccion.php");
include("phpclasses/Archivo.php");
include("phpclasses/Ubicacion.php");
include("phpdelegates/thumbnailer.php");
include("phpclasses/Tema.php");

// iniciamos conexion
$base = new OpmDB(genera_arreglo_BD());
$base->init();

// primero asignaremos la noticia, si ya esta asignada, se ignora la accion para no mandar error de clave duplicada
//$base->execute_query("INSERT IGNORE INTO `asigna` (`id_noticia`, `id_empresa`, `id_tema`, `id_tendencia`)
                                    //VALUES (".$_POST['id_noticia'].",".$_POST['id_empresa'].",".$_POST['id_tema'].",".$_POST['id_tendencia'].");");
									
//Ahora se mandara la noticia por correo a las cuentas seleccionadas
//
//Segun el tipo de fuente  al que pertenezca la noticia sera el formato y los datos q se necesiten
//
// Se verifica que haya cuentas a quien mandar, si no hay, redireccionamos. Si hay, revisamos que tipo de noticia es para armar el mail

if (isset($_POST['envia'])){
    $arreglo_envia = $_POST['envia']; // contiene las Id de las cuentas a las que se enviara la noticia
    $ncuentas        = count($arreglo_envia);
}

if($ncuentas <= 0) // NO hay cuentas
{
    header("Location: envia_noticia.php?id_noticia=".$_POST['id_noticia']);
    exit();
}

else // SI hay cuentas
{
    //metemos los datos de las cuentas en un arreglo de objetos Cuenta
    $arreglo_cuentas = array();
    foreach($arreglo_envia as $id_cuenta){
        $base->execute_query("SELECT * FROM cuenta WHERE id_cuenta = ".$id_cuenta." LIMIT 1;");
        $cuenta = new Cuenta($base->get_row_assoc());
        $arreglo_cuentas[$cuenta->get_id()]=$cuenta;
    }

    //armamos los elementos del correo
    //Se itera el arreglo de las cuentas para ver a quien se le manda el correo
	
	$base->execute_query("SELECT * FROM tema WHERE id_empresa=".$_POST['id_empresa']." ORDER BY nombre;");
	$arreglo_temas = array();
	$arreglo_temas2 = array();
	$j=0;
		while($row_tema = $base->get_row_assoc())
		{
			$tema = new Tema($row_tema);
			$arreglo_temas[$tema->get_id()]=$tema;
			$arreglo_temas2[$tema->get_id()]=$tema;
			$ids[$j]=$tema->get_id();
			$j++;
		}

    $array_to = array();
    foreach($arreglo_cuentas as $cuenta)
    {
        $array_to[] .=$cuenta->get_nombre_completo().' <'.$cuenta->get_email().'>';
    }
    $to = join(",", $array_to);

    //headers
    $headers  = "MIME-Version: 1.0\n";
	$headers .= "Return-Path: <noticias@opemedios.com.mx>\n";
    $headers .= "Content-type: text/html; charset=utf-8\n"; //iso-8859-1
    $headers .= "From: Noticias OPEMEDIOS <noticias@opemedios.com.mx>";
	
	$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 	
	$headdermessage = '
	<html>
		<head>
            <title>Operadora de Medios Informativos S.A. de C.V.</title>
        </head>
<body background="http://www.opemedios.com.mx/html/images/fondo.gif" bgcolor="#1D6C9F" text="#2b4f71" link="#933838" vlink="#933838" alink="#2b4f71" leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#C0C0C0">
  <tr bgcolor="#C0C0C0" valign="middle"> 
    <td align="left" valign="middle">
      <img src="http://opemedios.com.mx/notas/images/liverpool_logo.gif" width="190" height="53">
    </td>
    <td width="75%" align="right"><font color="#8d071b"><b>'.date("d").' de '.$meses[date("n")-1]. ' del '.date("Y").' &nbsp;'.date("h:i A").'</b></font>&nbsp;&nbsp;&nbsp;</td>    
  </tr>
  <tr> 
    <td width="25%" valign="top" bgcolor="#C0C0C0"> 
      <b>TEMAS:</b>
      <ul>';
	  
	//$tipo_fuente=$_POST['id_tipo_fuente'];
	$id_noticia_post=$_POST['id_noticia'];
	$message_for='';
	$message='';
	$error='';
	$k=0;
	$l=0;
	foreach ($arreglo_temas as $tema){
		$nombre = $tema->get_nombre();
		$headdermessage .= '<li><a href="#'.$nombre.'">'.$nombre.'</a></li>';
	}  
	$headdermessage .= '</ul>
						</td>
						<td width="75%">
						<table width="99%" cellspacing="0" cellpadding="0" align="center" border="0">';
	foreach ($arreglo_temas2 as $tema2){
		$cont_temas = 0;
		$id_tema = (string) $tema2->get_id();
		$headertema = '<tr bgcolor="#000000"><td><a name="'.$tema2->get_nombre().'"><font color="white">&nbsp;&nbsp;'.$tema2->get_nombre().'</font></a></td></tr>
						<tr><td>&nbsp;</td></tr>';
		$n=0;								
	foreach( $id_noticia_post as $key => $val) {
	//obtenemos el id_tema de la noticia en la tabla asigna
	$SQL="SELECT id_tema FROM asigna WHERE id_noticia = ".$val;
	$base->execute_query($SQL);
	$row_tema_noticia = $base->get_row_assoc();
	$id_tema_noticia = $row_tema_noticia["id_tema"];
	$numero = $base->num_rows ();
	
	//obtenemos el id_tipo_fuente de la noticia en la tabla noticia
	$SQL="SELECT id_tipo_fuente FROM noticia WHERE id_noticia = ".$val;
	$base->execute_query($SQL);
	$row_tipo_noticia = $base->get_row_assoc();
	$id_tipo_noticia = $row_tipo_noticia["id_tipo_fuente"];
	
	//$headertema .= '<tr bgcolor="#FFFFFF"><td>IDS: '.$id_tema_noticia.'='.$id_tema.'</td></tr>';
	
	$base->init();
	
	if ($numero == 0) {
		$error .= '<p>'.$val.' No fue asignada al cliente</p>';
		//exit();
	}
	else {
	 if ( strcmp($id_tema,$id_tema_noticia) == 0) {
	  //se encontro un tema
		$cont_temas++;
     
	 //ahora segun el tipo de fuente, se generará el mensaje en HTML
	 //$headertema .= '<tr bgcolor="#FFFFFF"><td>tipo_fuente: '.$id_tipo_noticia.'</td></tr>';
        switch(true){
            case ($id_tipo_noticia == 1 || $id_tipo_noticia == 2): // si la noticia es de medio electronico
                $tabla_tipo = "";
                $mensaje_archivo="";
                if($id_tipo_noticia == 1){
					$carpeta = "television";
                    $tabla_tipo = "noticia_tel";
                    $tabla_tipof = "fuente_tel";
                    $tabla_tipoc = "canal";
                    $mensaje_archivo="Descarga Video";
                    $tf = "Televisión";
                    $e_c = "Canal: ";
                }
                if($id_tipo_noticia == 2){
					$carpeta = "radio";
                    $tabla_tipo = "noticia_rad";
                    $mensaje_archivo="Descarga Audio";
                    $tabla_tipof = "fuente_rad";
                    $tabla_tipoc = "estacion";
                    $tf = "Radio";
                    $e_c = "Estación: ";
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
                          ".$tabla_tipof.".".$tabla_tipoc." AS canal_estacion_txt,
                          seccion.nombre AS seccion,
                          sector.nombre AS sector,
                          tipo_fuente.descripcion AS tipo_fuente,
                          tipo_autor.descripcion AS tipo_autor,
                          genero.descripcion AS genero,
                          tendencia.descripcion AS tendencia_monitorista,
                          ".$tabla_tipo.".hora AS hora,
                          ".$tabla_tipo.".duracion AS duracion,
						  ".$tabla_tipo.".costo AS costo
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN ".$tabla_tipof." ON (fuente.id_fuente = ".$tabla_tipof.".id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                         INNER JOIN ".$tabla_tipo." ON (noticia.id_noticia=".$tabla_tipo.".id_noticia)
                    WHERE
                         noticia.id_noticia =".$val.";");
						 
				if ($base->num_rows() == 0) {
					$error .= '<p>'.$val.' no esta en la clasificacion de Television o Radio</p>';
				}
				else {

                //creamos el objeto NoticiaElectronico con los datos que nos regrese la consulta
                $noticia = new NoticiaElectronico($base->get_row_assoc());

                //hacemos consulta para obtener los datos del archivo principal y creamos objeto Archivo para asignarlo a la noticia
                $base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$val." AND principal = 1 LIMIT 1;");

                if($base->num_rows() == 0){
                    $principal = 0;
					$link = "#";
                }
                else{
                    $principal = 1;
                    $archivo_principal = new Archivo($base->get_row_assoc());
                    $noticia->setArchivo_principal($archivo_principal);
					$link = 'http://sistema.opemedios.com.mx/data/noticias/'.$carpeta.'/'.$archivo_principal->getNombre_archivo();
                }			
                $message .= '						  
		  <tr>
          <td valign="top">
		  <!-- tabla desarrollo -->
                <table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-style: solid; border-width: 4px; border-color: #C0C0C0;">
                    <tr bgcolor="#FFFFFF">
                        <td colspan="3">
                            <div align="center"><br>
                                <table width="98%" cellspacing="3" cellpadding="3" border="0">
                                    <tr>
                                        <td><font face="Tahoma" size="2" color="red">Clave: <b>'.$noticia->getId().'</b></font><br>

                                            <font face="Tahoma" size="2">

                                                <font face="Tahoma"><h3><a href="'.$link.'">'.$noticia->getEncabezado().'</a></h3></font>
                                                '.$noticia->getSintesis().'<br><br>
												
												<font face="Tahoma" size="2" color="red">Costo/Beneficio: <b>$ '.number_format($noticia->getCosto(),2).'</b></font><br>												
                                                <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                                    <tr bgcolor="#ffede1">
                                                        <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Autor:</b> '.$noticia->getAutor().' ('.$noticia->getTipo_autor().')</td>
                                                        <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Fecha:</b> '.$noticia->getFecha_larga().'</td>
                                                    </tr>
                                                    <tr bgcolor="#ffede1">
                                                        <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Fuente:</b> '.$noticia->getFuente().' - '.$e_c.' '.$noticia->getCanal_estacion_txt().'</td>
                                                        <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Hora:</b> '.$noticia->getHora().'</td>
                                                    </tr>
                                                    <tr bgcolor="#ffede1">
                                                        <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Sección:</b> '.$noticia->getSeccion().'</td>
                                                        <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Duración:</b> '.$noticia->getDuracion().'</td>
                                                    </tr>
                                                    <tr bgcolor="#ffede1">
                                                        <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Género:</b> '.utf8_encode($noticia->getGenero()).'</td>
                                                        <td width="50%" rowspan="4" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Comentarios:</b> '.$noticia->getComentario().'</td>
                                                    </tr>
                                                    <tr bgcolor="#ffede1">
                                                        <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Sección:</b> '.$noticia->getSeccion().'</td>
                                                    </tr>
                                                    <tr bgcolor="#ffede1">
                                                        <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Tendencia:</b> '.$noticia->getTendencia_monitorista().'</td>
                                                    </tr>';

									if($principal > 0){
													$message.='<tr bgcolor="#ffede1">
                                                        <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Visualizar Archivo </b><a href="http://sistema.opemedios.com.mx/data/noticias/'.$carpeta.'/'.$archivo_principal->getNombre_archivo().'"> Ver aquí</a><br></td>
                                                               </tr>';															   
									}

								$message.='</table><br>';       
								$message.='</td>
                                                                </tr>
                                                                </table>															
																</div>
															</td>
                                                        </tr>
                                                    </table>                                                               
								</td>
								</tr>';
				}
                break;
            case ($id_tipo_noticia == 3 || $id_tipo_noticia == 4):  // noticia de medio impreso
                $tabla_tipo = "";
                $mensaje_archivo="";
                if($id_tipo_noticia == 3){
                    $url = "data/noticias/periodico/";// directorio donde se copian los archivos de las noticias
                    $carpeta = "periodico";
                    $tabla_tipo = "noticia_per";
                    $mensaje_archivo="Descarga Noticia";
                }
                if($id_tipo_noticia == 4){
                    $url = "data/noticias/revista/";// directorio donde se copian los archivos de las noticias
                    $tabla_tipo = "noticia_rev";
                    $carpeta = "revista";
                    $mensaje_archivo="Descarga Noticia";
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
                                    WHERE noticia.id_noticia = ".$val." LIMIT 1;");
				
				if ($base->num_rows() == 0) {
					$error .= '<p>'.$val.' no esta en la clasificación de Periódico o Revista</p>';
				}
				else {

                //creamos el objeto NoticiaElectronico con los datos que nos regrese la consulta
                $noticia = new NoticiaExtra($base->get_row_assoc(),$id_tipo_noticia);

                //hacemos consulta para obtener los datos del archivo principal y creamos objeto Archivo para asignarlo a la noticia
                $base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$val." AND principal = 1 LIMIT 1;");

                if($base->num_rows() == 0){
                    $principal = 0;
					$nombre_archivo_principal = "";
                }
                else{
                    $principal = 1;
                    $archivo_principal = new Archivo($base->get_row_assoc());
                    $noticia->setArchivo_principal($archivo_principal);
                    $thumbnail = new thumbnail($url.$archivo_principal->getNombre_archivo(),$url."thumbs",120,200,90,"_mailtn.");
					$nombre_archivo_principal = $archivo_principal->getNombre_archivo();
                }

                //hacemos consulta para obtener los datos del archivo de la pagina donde se publico la nota y creamos objeto Archivo para asignarlo a la noticia
                $base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$val." AND principal = 2 LIMIT 1;"); // las paginas tienen principal = 2

                if($base->num_rows() == 0){
                    $rows_pagina = 0;
                }
                else{
                    $rows_pagina = $base->num_rows();
                    $pagina = new Archivo($base->get_row_assoc());
                    $noticia->setArchivoPagina($pagina);
                }

                // creamos el objeto de ubicacion de la noticia

                $base->execute_query("SELECT * FROM ubicacion WHERE id_noticia = ".$val." LIMIT 1;");
                $ubicacion = new Ubicacion($base->get_row_assoc());
                $arr_color_ub = array(0=>"#E7EDF6", 1=>"#A60000");
				
				//ahora vamos a obtener el costo beneficio
				//tenemos toda la informacion para obtener las tarifas
				//metemos las tarifas en un arreglo
				$arreglo_tarifas = array();
				$tarifas = 0;
				
				//si hay una tarifa con el tamaño exacto de la nota creamos solo una  con el precio establecido
				/*$base->execute_query("SELECT * FROM cuesta_prensa
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
					$precio_noticia = "N/D";
				}*/

				//<font face="Tahoma" size="2" color="red">Costo/Beneficio: $<b> '.$precio_noticia.'</b></font><br><br>
				//<font face="Tahoma" size="2" color="red">Costo/Beneficio: <b>$ '.number_format($noticia->getCosto()).'</b></font><br>

                $message = '
								<tr>
									<td valign="top">
                                        <!-- tabla desarrollo -->
                                        <table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-style: solid; border-width: 4px; border-color: #C0C0C0;">
                                            <tr bgcolor="#FFFFFF">
                                                <td colspan="3">
                                                    <div align="center"><br>
                                                        <table width="96%" cellspacing="3" cellpadding="3" border="0">
                                                            <tr>
                                                                <td>
                                                                    <font face="Tahoma" size="2">
                                                                        <table width="100%" border="0" cellspacing="4" cellpadding="4">
                                                                            <tr>
                                                                                <td valign="top" width="112">
                                                                                    <img src="http://sistema.opemedios.com.mx/data/noticias/'.$carpeta.'/thumbs/'.$nombre_archivo_principal.'_mailtn.jpg" alt=""><br></td>
                                                                                <td valign="top">
																				<font face="Tahoma" size="2" color="red">Clave: <b>'.$noticia->getId().'</b></font><br>
																				<font face="Tahoma" size="2">
                                                                                        <font face="Tahoma"><h3><a href="http://sistema.opemedios.com.mx/data/noticias/'.$carpeta.'/'.$nombre_archivo_principal.'">'.$noticia->getEncabezado().'</a></h3></font>
                                                                                        '.$noticia->getSintesis().'<br><br>
																						<!-- aqui va el costo -->																						
																						<font face="Tahoma" size="2" color="red">Costo/Beneficio: <b>$ '.number_format($noticia->getCosto(),2).'</b></font><br>
                                                                                        </td>
                                                                                        </tr>
                                                                                        </table>
                                                                                        <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                                                                            <tr bgcolor="#ffede1">
                                                                                                <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Autor:</b> '.$noticia->getAutor().' ('.$noticia->getTipo_autor().')</td>
                                                                                                <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Fecha:</b> '.$noticia->getFecha_larga().'</td>
                                                                                            </tr>
                                                                                            <tr bgcolor="#ffede1">
                                                                                                <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Fuente:</b> '.$noticia->getFuente().'</td>
                                                                                                <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Página:</b> '.$noticia->getPagina().' ('.$noticia->getTipo_pagina().')</td>
                                                                                            </tr>
                                                                                            <tr bgcolor="#ffede1">
                                                                                                <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Sección:</b> '.$noticia->getSeccion().'</td>
                                                                                                <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Tamaño:</b> '.$noticia->getPorcentaje_pagina().' %</td>
                                                                                            </tr>
                                                                                            <tr bgcolor="#ffede1">
                                                                                                <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Género:</b> '.utf8_encode($noticia->getGenero()).'</td>
                                                                                                <td width="50%" rowspan="4" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Comentarios:</b> '.$noticia->getComentario().'</td>
                                                                                            </tr>
                                                                                            <tr bgcolor="#ffede1">
                                                                                                <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Sector:</b> '.$noticia->getSector().'</td>
                                                                                            </tr>
                                                                                            <tr bgcolor="#ffede1">
                                                                                                <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Tendencia:</b> '.$noticia->getTendencia_monitorista().'</td>
                                                                                            </tr>
                                                                                        </table>
																						<!-- Compartir Redes Sociales -->
																						<table width="580" border="0">
																							<tr>
																							<td><font face="Tahoma" size="2" colspan="2">Compartir en Redes Sociales:&nbsp;<a href="http://sistema.opemedios.com.mx/compartir_noticia_prensa.php?id_noticia='.$noticia->getId().'&id_tipo_fuente='.$noticia->getId_tipo_fuente().' " target="_blank"> COMPARTIR</a></font></td>
																							</tr>
																						</table>																					
                                                                                        <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                                                                            <tr>
                                                                                                <td width="80%" valign="top"><font face="Tahoma" size="2">          ';
                                if($principal > 0){
                                    $message.=                     'Archivo: <a href="http://sistema.opemedios.com.mx/data/noticias/'.$carpeta.'/'.$noticia->getArchivo_principal()->getNombre_archivo().'">Ver aquí</a><br>';
                                }

                                if($rows_pagina > 0){
                                    $message.=                     'Página Contenedora: <a href="http://sistema.opemedios.com.mx/data/noticias/'.$carpeta.'/'.$noticia->getArchivo_pagina()->getNombre_archivo().'">Ver aquí</a><br>';
                                }

                                                    $message.=                       					'<hr align="left" width="95%"><br>';
                                                    $message.=                            				'</td>
                                                                                                        <td valign="top"><font face="Tahoma" size="2"><b>Ubicación:</b><br><br>
                                                                                                                <!-- empieza tabla ubicación -->
                                                                                                                <table width="90" cellspacing="2" cellpadding="2" border="0">
                                                                                                                    <tr>
                                                                                                                        <td width="40" height="40" align="center" bgcolor="'.$arr_color_ub[$ubicacion->getUno()].'"><font face="Tahoma" size="1">1</td>
                                                                                                                        <td width="40" height="40" align="center" bgcolor="'.$arr_color_ub[$ubicacion->getDos()].'"><font face="Tahoma" size="1">2</td>
                                                                                                                        <td width="40" height="40" align="center" bgcolor="'.$arr_color_ub[$ubicacion->getTres()].'"><font face="Tahoma" size="1">3</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td width="40" height="40" align="center" bgcolor="'.$arr_color_ub[$ubicacion->getCuatro()].'"><font face="Tahoma" size="1">4</td>
                                                                                                                        <td width="40" height="40" align="center" bgcolor="'.$arr_color_ub[$ubicacion->getCinco()].'"><font face="Tahoma" size="1">5</td>
                                                                                                                        <td width="40" height="40" align="center" bgcolor="'.$arr_color_ub[$ubicacion->getSeis()].'"><font face="Tahoma" size="1">6</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td width="40" height="40" align="center" bgcolor="'.$arr_color_ub[$ubicacion->getSiete()].'"><font face="Tahoma" size="1">7</td>
                                                                                                                        <td width="40" height="40" align="center" bgcolor="'.$arr_color_ub[$ubicacion->getOcho()].'"><font face="Tahoma" size="1">8</td>
                                                                                                                        <td width="40" height="40" align="center" bgcolor="'.$arr_color_ub[$ubicacion->getNueve()].'"><font face="Tahoma" size="1">9</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td width="40" height="40" align="center" bgcolor="'.$arr_color_ub[$ubicacion->getDiez()].'"><font face="Tahoma" size="1">10</td>
                                                                                                                        <td width="40" height="40" align="center" bgcolor="'.$arr_color_ub[$ubicacion->getOnce()].'"><font face="Tahoma" size="1">11</td>
                                                                                                                        <td width="40" height="40" align="center" bgcolor="'.$arr_color_ub[$ubicacion->getDoce()].'"><font face="Tahoma" size="1">12</td>
                                                                                                                    </tr>
                                                                                                                </table>
                                                                                                                <!-- termina tabla ubicación -->
                                                                                                        </td>
                                                                                            </tr>
                                                                                        </table>  <br><br>
                                                                                        </td>
                                                                                        </tr>
                                                                                        </table></div><br>
                                                                                        </td>
                                                                                        </tr>
                                                                                        </table>
																	</td>
																</tr>';
				}
                break;

            case($id_tipo_noticia == 5):  // noticia de internet              
                $tabla_tipo = "noticia_int";
                $mensaje_archivo="Descarga Aqui";
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
										  ".$tabla_tipo.".costo AS costo
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
                                    WHERE noticia.id_noticia = ".$val." LIMIT 1;");
									
				if ($base->num_rows() == 0) {
					$error .= '<p>'.$val.' no esta en la clasificacion de Internet</p>';
				}
				else {

                //creamos el objeto NoticiaElectronico con los datos que nos regrese la consulta
                $noticia = new NoticiaExtra($base->get_row_assoc(),$id_tipo_noticia);

                //hacemos consulta para obtener los datos del archivo principal y creamos objeto Archivo para asignarlo a la noticia
                $base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$val." AND principal = 1 LIMIT 1;");

                if($base->num_rows() == 0){
                    $principal = 0;
                }
                else{
                    $principal = 1;
                    $archivo_principal = new Archivo($base->get_row_assoc());
                    $noticia->setArchivo_principal($archivo_principal);
                }
               
                $message = '
				<tr>
				<td valign="top">
                <!-- tabla desarrollo -->
                <table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-style: solid; border-width: 4px; border-color: #C0C0C0;">
                    <tr bgcolor="#FFFFFF">
                        <td colspan="3">
                            <div align="center"><br>
                                <table width="96%" cellspacing="3" cellpadding="3" border="0">
                                    <tr>
                                        <td>
										<font face="Tahoma" size="2" color="red">Clave: <b>'.$noticia->getId().'</b></font><br>
                                            <font face="Tahoma" size="2">
                                                <font face="Tahoma"><h3><a href="http://sistema.opemedios.com.mx/data/noticias/internet/'.$archivo_principal->getNombre_archivo().'">'.$noticia->getEncabezado().'</a></h3></font>
                                                '.$noticia->getSintesis().'<br><br>												
												<font face="Tahoma" size="2" color="red">Costo/Beneficio: <b>$ '.number_format($noticia->getCosto(),2).'</b></font><br>
                                                <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                                    <tr bgcolor="#ffede1">
                                                        <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Autor:</b> '.$noticia->getAutor().' ('.$noticia->getTipo_autor().')</td>
                                                        <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Fecha:</b> '.$noticia->getFecha_larga().'</td>
                                                    </tr>
                                                    <tr bgcolor="#ffede1">
                                                        <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Fuente:</b> '.$noticia->getFuente().'</td>
                                                        <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>URL:</b><a href="'.$noticia->getUrl().'">Ir a URL</a></td>
                                                    </tr>
                                                    <tr bgcolor="#ffede1">
                                                        <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Sección:</b> '.$noticia->getSeccion().'</td>
                                                        <td width="50%" rowspan="5" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Comentarios:</b> '.$noticia->getComentario().'</td>
                                                    </tr>
                                                    <tr bgcolor="#ffede1">
                                                        <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Género:</b> '.utf8_encode($noticia->getGenero()).'</td>
                                                    </tr>
                                                    <tr bgcolor="#ffede1">
                                                        <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Sector:</b> '.$noticia->getSector().'</td>
                                                    </tr>
                                                    <tr bgcolor="#ffede1">
                                                        <td width="50%" valign="top">&nbsp;<font face="Tahoma" size="1"><b>Tendencia:</b> '.$noticia->getTendencia_monitorista().'</td>
                                                    </tr>';
                if($principal > 0){
                    $message.=                     '<tr bgcolor="#ffede1">
                                                        <td colspan="2" valign="top">&nbsp;<font face="Tahoma" size="1">
															<a href="http://sistema.opemedios.com.mx/data/noticias/internet/'.$noticia->getArchivo_principal()->getNombre_archivo().'">Ver aquí</a><br>
														</td>
													</tr>';
                }

                              $message.=           '</table>';
                             $message.=                     '</td>
                                                                </tr>
                                                                </table>																
															</div><br>
                                                            </td>
                                                        </tr>
                                                    </table>
												</td>
											</tr>';
				}
                break;
            default:
                break;
            
        } // end switch
	 } //end del if para comparar temas
	} // end del if del error 
	 $message_for=$message_for.$headertema.$message;
	 $message='';
	 $headertema='';
	 $n++;
	} // end del for para las noticias
	  ///////////////////////////  Aqui ya se cuantas hay de esa categoria
	  if ($cont_temas == 0) {
		$message_for .= '<tr><td> No hay notas de esta Tema </td></tr><tr><td>&nbsp;</td></tr>';
	  }
	 $total_notas[$l] = $cont_temas;
	 $l++;
	 $cont_temas=0;
	} // end del tema
	$footermessage='</table></td></tr></table></body></html>';
    $subject = 'Operamedios: Bloque de Notas';
	$message_final= $headdermessage.$message_for.$footermessage;
	if ($error == '') {
		mail($to, $subject, $message_final, $headers);
		header("Location: mensaje.php?id_noticia=".$val."&mensaje=Se ha enviado por correo con Exito el Bloque de Noticias&error=".$error);
	}
	else {
		header("Location: mensaje.php?id_noticia=".$val."&mensaje=No se envio el correo revise los siguientes Errores:&error=".$error);
	}
	$base->free_result();
	$base->close();
    exit();
} // end else SI hay cuentas
?>
