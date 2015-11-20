<?php 

/*
 * Action para asignar una noticia al portal de un cliente, a la vez de mandar un correo a todos las cuentas seleccionadas
 *
 *
 *@autor: Josue Morado Manríquez
 *
 */
 
// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Empresa.php");
include("phpclasses/Cuenta.php");
include("phpclasses/Noticia.php");
include("phpclasses/Tema.php");
include("phpclasses/SuperNoticia.php");
include("phpclasses/NoticiaElectronico.php");
include("phpclasses/NoticiaExtra.php");
include("phpclasses/Seccion.php");
include("phpclasses/Archivo.php");
include("phpdelegates/thumbnailer.php");

function getFecha_larga($f)
    {
        $arreglo_meses = array(
            1=>"Enero",
            2=>"Febrero",
            3=>"Marzo",
            4=>"Abril",
            5=>"Mayo",
            6=>"Junio",
            7=>"Julio",
            8=>"Agosto",
            9=>"Septiembre",
            10=>"Octubre",
            11=>"Noviembre",
            12=>"Diciembre",);

        $dia = substr($f,8,2);
        $mes = date("n",mktime(00,00,00,substr($f,5,2),01,2000));
        $año = substr($f,0,4);

        return $dia." de ".$arreglo_meses[$mes].", ".$año;
    }
	
	
// funcion para imprimir la noticia dependiendo su tipo de fuente

function imprime_noticia_resumen($dao,$id_noticia,$id_tipo_fuente,$fecha,$hora1,$hora2)
{
	$output = '';
	switch($id_tipo_fuente)
	{
		case 1: 
		       $query=             "SELECT
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
                          fuente_tel.canal AS canal,
                          seccion.nombre AS seccion,
                          sector.nombre AS sector,
                          tipo_fuente.descripcion AS tipo_fuente,
                          tipo_autor.descripcion AS tipo_autor,
                          genero.descripcion AS genero,
                          tendencia.descripcion AS tendencia_monitorista,
                          noticia_tel.hora AS hora,
                          noticia_tel.duracion AS duracion,
						  noticia_tel.costo AS costo
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN fuente_tel ON (fuente.id_fuente = fuente_tel.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                         INNER JOIN noticia_tel ON (noticia.id_noticia=noticia_tel.id_noticia)
						 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
                    WHERE
					     asigna.id_empresa = ".$_POST['row_id']." AND
						 asigna.id_noticia = ".$id_noticia." AND
					     noticia.fecha = '".$fecha."' AND
                         noticia_tel.hora BETWEEN'".$hora1."' AND '".$hora2."'
				     ORDER BY hora ASC;";
						 
						 //die($query);
				$dao->execute_query($query);
				
				while($row = $dao->get_row_assoc())
				{
					$noticia = new SuperNoticia($row);
				}
				
					$dao->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");

							if($dao->num_rows() == 0)
							{
								$principal = 0;
							}
							else
							{
								$principal = 1;
								$archivo_principal = new Archivo($dao->get_row_assoc());
							}
				
				$output='<tr>
						  <td width="16%" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #333;">Television<br>'.$noticia->getHora().' hrs</td>
						  <td width="84%"><span style="font-family: Tahoma, Geneva, sans-serif; font-weight: bold; font-size: x-small; color: #900;"><a href="http://sistema.operamedios.com.mx/data/noticias/television/'.$archivo_principal->getNombre_archivo().'">'.$noticia->getEncabezado().'</a></span><br>
							<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #666;">'.$noticia->getFuente().' -  Canal '.$noticia->getCanal().'</span><br>
							<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small;">'.$noticia->getSintesis().'</span><br>
							<br>
						  </td>
						</tr>';
				
			   
			   break;
	    case 2: 
		
		$query=          "SELECT
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
                          fuente_rad.estacion AS estacion,
                          seccion.nombre AS seccion,
                          sector.nombre AS sector,
                          tipo_fuente.descripcion AS tipo_fuente,
                          tipo_autor.descripcion AS tipo_autor,
                          genero.descripcion AS genero,
                          tendencia.descripcion AS tendencia_monitorista,
                          noticia_rad.hora AS hora,
                          noticia_rad.duracion AS duracion,
						  noticia_rad.costo AS costo
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN fuente_rad ON (fuente.id_fuente = fuente_rad.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                         INNER JOIN noticia_rad ON (noticia.id_noticia=noticia_rad.id_noticia)
						 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
                    WHERE
					     asigna.id_empresa = ".$_POST['row_id']." AND
						 asigna.id_noticia = ".$id_noticia." AND
					     noticia.fecha = '".$fecha."' AND
                         noticia_rad.hora BETWEEN'".$hora1."' AND '".$hora2."'
				    ORDER BY hora ASC;";
						 
						 //die($query);
				$dao->execute_query($query);
				
				while($row = $dao->get_row_assoc())
				{
					$noticia = new SuperNoticia($row);
				}
				
				$dao->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");

							if($dao->num_rows() == 0)
							{
								$principal = 0;
							}
							else
							{
								$principal = 1;
								$archivo_principal = new Archivo($dao->get_row_assoc());
							}
							
							
			$output=	'<tr>
						  <td width="16%" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #333;">Radio<br>'.$noticia->getHora().' hrs</td>
						  <td width="84%"><p><span style="font-family: Tahoma, Geneva, sans-serif; font-weight: bold; font-size: x-small; color: #900;"><a href="http://sistema.operamedios.com.mx/data/noticias/radio/'.$archivo_principal->getNombre_archivo().'">'.$noticia->getEncabezado().'</a></span><br>
							<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #666;">'.$noticia->getFuente().' - '.$noticia->getEstacion().'</span><br>
							<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small;">'.$noticia->getSintesis().'<br>
							<br>
							</span></p></td>
						</tr>';
				
		
			   break;
			   
			//periodico   
	    case 3: 
		       $query=             "SELECT
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
                          noticia_per.pagina AS pagina,
						  noticia_per.porcentaje_pagina AS porcentaje_pagina
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                         INNER JOIN noticia_per ON (noticia.id_noticia=noticia_per.id_noticia)
						 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
                    WHERE
					     asigna.id_empresa = ".$_POST['row_id']." AND
						 asigna.id_noticia = ".$id_noticia." AND
					     noticia.fecha = '".$fecha."';";
						 
						 //die($query);
						 
				$dao->execute_query($query);
				
				while($row = $dao->get_row_assoc())
				{
					$noticia = new SuperNoticia($row);
				}
				
				$dao->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");

				if($dao->num_rows() == 0)
				{
					$principal = 0;
				}
				else
				{
					$principal = 1;
					$archivo_principal = new Archivo($dao->get_row_assoc());
				}
			   
			   $output=	'<tr>
								  <td width="16%" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #333;">Periodico<br>'.$noticia->getFecha().' </td>
								  <td width="84%"><p><span style="font-family: Tahoma, Geneva, sans-serif; font-weight: bold; font-size: x-small; color: #900;"><a href="http://sistema.operamedios.com.mx/data/noticias/periodico/'.$archivo_principal->getNombre_archivo().'">'.$noticia->getEncabezado().'</a></span><br>
									<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #666;">'.$noticia->getFuente().'</span><br>
									<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small;">'.$noticia->getSintesis().'<br>
									<br>
									</span></p></td>
								</tr>';
			   
			   
			   
			   
			   
			   break;
			   
			  //revista 
	    case 4: 
		      
			  $query=             "SELECT
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
                          noticia_rev.pagina AS pagina,
						  noticia_rev.porcentaje_pagina AS porcentaje_pagina
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                         INNER JOIN noticia_rev ON (noticia.id_noticia=noticia_rev.id_noticia)
						 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
                    WHERE
					     asigna.id_empresa = ".$_POST['row_id']." AND
						 asigna.id_noticia = ".$id_noticia." AND
					     noticia.fecha = '".$fecha."';";
						 
						 //die($query);
						 
				$dao->execute_query($query);
				
				while($row = $dao->get_row_assoc())
				{
					$noticia = new SuperNoticia($row);
				}
				
				$dao->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");

				if($dao->num_rows() == 0)
				{
					$principal = 0;
				}
				else
				{
					$principal = 1;
					$archivo_principal = new Archivo($dao->get_row_assoc());
				}
			   
			   $output=	'<tr>
								  <td width="16%" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #333;">Revista<br>'.$noticia->getFecha().' </td>
								  <td width="84%"><p><span style="font-family: Tahoma, Geneva, sans-serif; font-weight: bold; font-size: x-small; color: #900;"><a href="http://sistema.operamedios.com.mx/data/noticias/periodico/'.$archivo_principal->getNombre_archivo().'">'.$noticia->getEncabezado().'</a></span><br>
									<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #666;">'.$noticia->getFuente().'</span><br>
									<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small;">'.$noticia->getSintesis().'<br>
									<br>
									</span></p></td>
								</tr>';

			  
			   break;
			   
			   
		case 5: 
		
		$query=             "SELECT
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
                          noticia_int.url AS url,
						  noticia_int.hora_publicacion AS hora_publicacion
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                         INNER JOIN noticia_int ON (noticia.id_noticia=noticia_int.id_noticia)
						 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
                    WHERE
					     asigna.id_empresa = ".$_POST['row_id']." AND
						 asigna.id_noticia = ".$id_noticia." AND
					     noticia.fecha = '".$fecha."' AND
                         noticia_int.hora_publicacion BETWEEN'".$hora1."' AND '".$hora2."'
						 ORDER BY hora_publicacion ASC;";
						 
						 //die($query);
				$dao->execute_query($query);
						 
						 
		$total_int = $dao->num_rows();
		
			
			while($row = $dao->get_row_assoc())
			{
				$noticia = new SuperNoticia($row);
			} 
			
			
			$dao->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");

							if($dao->num_rows() == 0)
							{
								$principal = 0;
							}
							else
							{
								$principal = 1;
								$archivo_principal = new Archivo($dao->get_row_assoc());
							}
							
							
			$output.=	'<tr>
						  <td width="16%" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #333;">Internet<br>'.$noticia->getHora_publicacion().' hrs</td>
						  <td width="84%"><p><span style="font-family: Tahoma, Geneva, sans-serif; font-weight: bold; font-size: x-small; color: #900;"><a href="http://sistema.operamedios.com.mx/data/noticias/internet/'.$archivo_principal->getNombre_archivo().'">'.$noticia->getEncabezado().'</a></span><br>
							<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #666;">'.$noticia->getFuente().'</span><br>
							<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small;">'.$noticia->getSintesis().'<br>
							<br>
							</span></p></td>
						</tr>';
				
		
			   break;    
	}
	
	return $output;
	
}// end function 

// iniciamos conexion

$base = new OpmDB(genera_arreglo_BD());
$base->init();


// Se verifica que haya cuentas a quien mandar, si no hay, redireccionamos. Si hay, revisamos que tipo de noticia es para armar el mail

if (isset($_POST['envia']))
{
    $arreglo_envia = $_POST['envia']; // contiene las Id de las cuentas a las que se enviara la noticia
    $ncuentas        = count($arreglo_envia);
}

if($ncuentas <= 0) // NO hay cuentas
{
	header( 'refresh: 3; url=/envio_bloque_noticias.php');
	echo '<h2>Error, no se seleccionaron cuentas... regresando</h2>';
    exit();
}
else // si hay cuentas
{
	
	$idcliente = $_POST['row_id'];
	
	//////////////
	////////////// Si se selecciono ordenar por tipo de fuente, procedemos a este, si no, vamos hasta la otra parte
	//////////////
	
	if(isset($_POST['tipo_ordenacion']) && $_POST['tipo_ordenacion'] == 1)
	{
		
		// ahora validamos que haya noticias en ese rango de horas en tele
	
	//obtenemos fecha
	$fecha = date("Y-m-d",mktime(0,0,0,$_POST['fecha_Month_ID'],$_POST['fecha_Day_ID'],$_POST['fecha_Year_ID']));
	$hora1= date("H:i:s",mktime($_POST['hora_HH1'],$_POST['hora_MM1'],$_POST['hora_SS1'],1,1,2000));
	$hora2= date("H:i:s",mktime($_POST['hora_HH2'],$_POST['hora_MM2'],$_POST['hora_SS2'],1,1,2000));
	
	
	$query=             "SELECT
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
                          fuente_tel.canal AS canal,
                          seccion.nombre AS seccion,
                          sector.nombre AS sector,
                          tipo_fuente.descripcion AS tipo_fuente,
                          tipo_autor.descripcion AS tipo_autor,
                          genero.descripcion AS genero,
                          tendencia.descripcion AS tendencia_monitorista,
                          noticia_tel.hora AS hora,
                          noticia_tel.duracion AS duracion,
						  noticia_tel.costo AS costo
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN fuente_tel ON (fuente.id_fuente = fuente_tel.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                         INNER JOIN noticia_tel ON (noticia.id_noticia=noticia_tel.id_noticia)
						 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
                    WHERE
					     asigna.id_empresa = ".$_POST['row_id']." AND
					     noticia.fecha = '".$fecha."' AND
                         noticia_tel.hora BETWEEN'".$hora1."' AND '".$hora2."'
				     ORDER BY hora ASC;";
						 
						 //die($query);
				$base->execute_query($query);
						 
	$total_tel = $base->num_rows();
	
	
	if($total_tel > 0)
	{
		$arreglo_noticias_tele = array();
		while($row = $base->get_row_assoc())
		{
			$noticia = new SuperNoticia($row);
			$arreglo_noticias_tele [$noticia->getId()] = $noticia;
		}
		
	} // end if tel = 0
	
					 
						 
	//ahora en radio 					 
	$query=             "SELECT
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
                          fuente_rad.estacion AS estacion,
                          seccion.nombre AS seccion,
                          sector.nombre AS sector,
                          tipo_fuente.descripcion AS tipo_fuente,
                          tipo_autor.descripcion AS tipo_autor,
                          genero.descripcion AS genero,
                          tendencia.descripcion AS tendencia_monitorista,
                          noticia_rad.hora AS hora,
                          noticia_rad.duracion AS duracion,
						  noticia_rad.costo AS costo
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN fuente_rad ON (fuente.id_fuente = fuente_rad.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                         INNER JOIN noticia_rad ON (noticia.id_noticia=noticia_rad.id_noticia)
						 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
                    WHERE
					     asigna.id_empresa = ".$_POST['row_id']." AND
					     noticia.fecha = '".$fecha."' AND
                         noticia_rad.hora BETWEEN'".$hora1."' AND '".$hora2."'
				    ORDER BY hora ASC;";
						 
						 //die($query);
				$base->execute_query($query);
						 
						 
		$total_rad = $base->num_rows();
		
		if($total_rad > 0)
		{
			$arreglo_noticias_radio = array();
			while($row = $base->get_row_assoc())
			{
				$noticia = new SuperNoticia($row);
				$arreglo_noticias_radio [$noticia->getId()] = $noticia;
			}
			
		} // end if rad = 0
		
		
		//ahora en internet					 
	$query=             "SELECT
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
                          noticia_int.url AS url,
						  noticia_int.hora_publicacion AS hora_publicacion
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                         INNER JOIN noticia_int ON (noticia.id_noticia=noticia_int.id_noticia)
						 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
                    WHERE
					     asigna.id_empresa = ".$_POST['row_id']." AND
					     noticia.fecha = '".$fecha."' AND
                         noticia_int.hora_publicacion BETWEEN'".$hora1."' AND '".$hora2."'
						 ORDER BY hora_publicacion ASC;";
						 
						 //die($query);
				$base->execute_query($query);
						 
						 
		$total_int = $base->num_rows();
		
		if($total_int > 0)
		{
			$arreglo_noticias_internet = array();
			while($row = $base->get_row_assoc())
			{
				$noticia = new SuperNoticia($row);
				$arreglo_noticias_internet [$noticia->getId()] = $noticia;
			}
			
		} // end if int = 0
		
		
		$total_per = 0;
		$total_rev = 0;
		
		//impresos
		if( $_POST['muestra_impresos'] == 1)
		{
			
			//periodico				 
	$query=             "SELECT
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
                          noticia_per.pagina AS pagina,
						  noticia_per.porcentaje_pagina AS porcentaje_pagina
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                         INNER JOIN noticia_per ON (noticia.id_noticia=noticia_per.id_noticia)
						 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
                    WHERE
					     asigna.id_empresa = ".$_POST['row_id']." AND
					     noticia.fecha = '".$fecha."';";
						 
						 //die($query);
				$base->execute_query($query);
						 
						 
		$total_per = $base->num_rows();
		
		if($total_per > 0)
		{
			$arreglo_noticias_periodico = array();
			while($row = $base->get_row_assoc())
			{
				$noticia = new SuperNoticia($row);
				$arreglo_noticias_periodico [$noticia->getId()] = $noticia;
			}
			
		} // end if per = 0
		
		
		//revista
							 
	$query=             "SELECT
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
                          noticia_rev.pagina AS pagina,
						  noticia_rev.porcentaje_pagina AS porcentaje_pagina
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                         INNER JOIN noticia_rev ON (noticia.id_noticia=noticia_rev.id_noticia)
						 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
                    WHERE
					     asigna.id_empresa = ".$_POST['row_id']." AND
					     noticia.fecha = '".$fecha."';";
						 
						 //die($query);
				$base->execute_query($query);
						 
						 
		$total_rev = $base->num_rows();
		
		if($total_rev > 0)
		{
			$arreglo_noticias_revista = array();
			while($row = $base->get_row_assoc())
			{
				$noticia = new SuperNoticia($row);
				$arreglo_noticias_revista [$noticia->getId()] = $noticia;
			}
			
		} // end if rev = 0
		
		
			
		} // end if muestra impresos
		
		$total = $total_tel + $total_rad + $total_int + $total_per + $total_rev;	
	
		//die("Tele: ".$total_tel." Radio: ".$total_rad." Internet: ".$total_int." Periodico: ".$total_per." Revista: ".$total_rev." TOTAL:".$total);
		
		
		
		if($total <= 0) // NO hay noticias
		{
			header( 'refresh: 3; url=/envio_bloque_noticias.php');
			echo '<h2>No hay noticias en el rango especificado... regresando</h2>';
			exit();
		}
		else // si hay noticias
		{ 
			
			//metemos los datos de las cuentas en un arreglo de objetos Cuenta
			$arreglo_cuentas = array();
			foreach($arreglo_envia as $id_cuenta)
			{
				$base->execute_query("SELECT * FROM cuenta WHERE id_cuenta = ".$id_cuenta." LIMIT 1;");
				$cuenta = new Cuenta($base->get_row_assoc());
				$arreglo_cuentas[$cuenta->get_id()]=$cuenta;
			}
		
		
		    // hacemos thumbnail de logo del cliente
			$url_logo = "data/empresas";
			// leemos logo
			$base->execute_query("SELECT logo FROM empresa WHERE id_empresa = ".$_POST['row_id'].";");
			$res = $base->get_row_assoc();
			$logo = $res['logo'];
			$mime = substr($logo,-3,3);
			//die($logo."<br>".mime."<br>".$url_logo.$logo);
			$thumbnail = new thumbnail($url_logo."/".$logo,$url_logo,194,79,90,"_tn.");
			if($logo != "default.jpg")
			{
					$logo_mail = "http://sistema.operamedios.com.mx/data/empresas/".$logo."_tn.".$mime;
			}
			else
			{
				$logo_mail = "http://www.operamedios.com.mx/html/images/logo.gif";
			}
		
		
			//armamos los elementos del correo
			//
			//Se itera el arreglo de las cuentas para ver a quien se le manda el correo
			
			
		
			$array_to = array();
			foreach($arreglo_cuentas as $cuenta)
			{
				$array_to[] .=$cuenta->get_nombre_completo().' <'.$cuenta->get_email().'>';
			}
			$to = join(",", $array_to);
		
			//headers
			$headers  = "MIME-Version: 1.0\n";
			$headers .= "Return-Path: <noticias@operamedios.com.mx>\n";
			$headers .= "Content-type: text/html; charset=utf-8\n"; //iso-8859-1
			$headers .= "From: Noticias OPEMEDIOS <noticias@operamedios.com.mx>";
			
			
			$message = '
						<html>
						<head>
							<title>Operadora de Medios Informativos S.A. de C.V.</title>
						</head>
						
						<body background="http://www.operamedios.com.mx/html/images/fondo.gif" bgcolor="#1D6C9F" text="#2b4f71" link="#933838" vlink="#933838" alink="#2b4f71" leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0">
						
						<div align="center"><font face="Tahoma" size="1"><br>
						</font> 
						
						 
						<!-- tabla top -->
						<table width="600" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="#FFFFFF">
							<td width="194" valign="top">
							<img src="'.$logo_mail.'" alt=""><br></td>
							<td align="right" valign="top">
							  <p><img src="http://www.operamedios.com.mx/html/images/top.gif" alt=""><br>
							  <b><font face="Tahoma" size="2">Resumen de Noticias - '.getFecha_larga($fecha).'</font></b>&nbsp;<br>
							  <span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small;">'.$hora1.'hrs a '.$hora2.' hrs</span>&nbsp;&nbsp;</p></td>
							
						</tr>
						</table>
						
						<!-- tabla desarrollo -->
						<table width="600" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="#FFFFFF">
							<td colspan="3">
							<div align="center"><br>
							
						<table width="94%" border="0" cellspacing="4" cellpadding="4">
						<tr>
							<td colspan="2">
							<img src="http://www.operamedios.com.mx/intranet/images/trans.gif" width="1" height="15" alt=""><span style="font-family: Tahoma, Geneva, sans-serif; font-size: small;">TELEVISION</span><br>
						<img src="http://www.operamedios.com.mx/intranet/images/pix-azul.gif" width="100%" height="1" alt=""><br>
						<img src="http://www.operamedios.com.mx/intranet/images/trans.gif" width="1" height="15" alt=""></td>
						</tr>
						';
						
						
						foreach($arreglo_noticias_tele as $noticia)
						{
							// leemos el archivo adjunto
							
							$base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");

							if($base->num_rows() == 0)
							{
								$principal = 0;
							}
							else
							{
								$principal = 1;
								$archivo_principal = new Archivo($base->get_row_assoc());
							}
							
							
			$message.=	'<tr>
						  <td width="16%" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #333;">'.$noticia->getHora().' hrs</td>
						  <td width="84%"><p><span style="font-family: Tahoma, Geneva, sans-serif; font-weight: bold; font-size: x-small; color: #900;"><a href="http://sistema.operamedios.com.mx/data/noticias/television/'.$archivo_principal->getNombre_archivo().'">'.$noticia->getEncabezado().'</a></span><br>
							<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #666;">'.$noticia->getFuente().' - '.$noticia->getCanal().'</span><br>
							<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small;">'.$noticia->getSintesis().'<br>
							<br>
							</span></p></td>
						</tr>';
							
							
							
						}// end foreach arrgeo noticias tele
						
						
				
						
			$message.=	'<tr>
							<td colspan="2">
							<img src="http://www.operamedios.com.mx/intranet/images/trans.gif" width="1" height="15" alt=""><span style="font-family: Tahoma, Geneva, sans-serif">RADIO</span><br>
						<img src="http://www.operamedios.com.mx/intranet/images/pix-azul.gif" width="100%" height="1" alt=""><br></td>
						</tr> ';
						
						
						foreach($arreglo_noticias_radio as $noticia)
						{
							// leemos el archivo adjunto
							
							$base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");

							if($base->num_rows() == 0)
							{
								$principal = 0;
							}
							else
							{
								$principal = 1;
								$archivo_principal = new Archivo($base->get_row_assoc());
							}
							
							
			$message.=	'<tr>
						  <td width="16%" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #333;">'.$noticia->getHora().' hrs</td>
						  <td width="84%"><p><span style="font-family: Tahoma, Geneva, sans-serif; font-weight: bold; font-size: x-small; color: #900;"><a href="http://sistema.operamedios.com.mx/data/noticias/radio/'.$archivo_principal->getNombre_archivo().'">'.$noticia->getEncabezado().'</a></span><br>
							<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #666;">'.$noticia->getFuente().' - '.$noticia->getEstacion().'</span><br>
							<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small;">'.$noticia->getSintesis().'<br>
							<br>
							</span></p></td>
						</tr>';
						
						} // end foreach
						
						
						//internet
						
						
						$message.=	'<tr>
							<td colspan="2">
							<img src="http://www.operamedios.com.mx/intranet/images/trans.gif" width="1" height="15" alt=""><span style="font-family: Tahoma, Geneva, sans-serif">INTERNET</span><br>
						<img src="http://www.operamedios.com.mx/intranet/images/pix-azul.gif" width="100%" height="1" alt=""><br></td>
						</tr> ';
						
						
						foreach($arreglo_noticias_internet as $noticia)
						{
							// leemos el archivo adjunto
							
							$base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");

							if($base->num_rows() == 0)
							{
								$principal = 0;
							}
							else
							{
								$principal = 1;
								$archivo_principal = new Archivo($base->get_row_assoc());
							}
							
							
			$message.=	'<tr>
						  <td width="16%" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #333;">'.$noticia->getHora_publicacion().' hrs</td>
						  <td width="84%"><p><span style="font-family: Tahoma, Geneva, sans-serif; font-weight: bold; font-size: x-small; color: #900;"><a href="http://sistema.operamedios.com.mx/data/noticias/internet/'.$archivo_principal->getNombre_archivo().'">'.$noticia->getEncabezado().'</a></span><br>
							<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #666;">'.$noticia->getFuente().'</span><br>
							<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small;">'.$noticia->getSintesis().'<br>
							<br>
							</span></p></td>
						</tr>';
						
						} // end foreach
						
						
						// si se selecciono mostrar impresos, procedemos a imprimirlos en el correo
						
						if( $_POST['muestra_impresos'] == 1)
						{
							
							if($total_per > 0)
							{
								
								//periodico
						
								$message.=	'<tr>
									<td colspan="2">
									<img src="http://www.operamedios.com.mx/intranet/images/trans.gif" width="1" height="15" alt=""><span style="font-family: Tahoma, Geneva, sans-serif">PERIODICO</span><br>
								<img src="http://www.operamedios.com.mx/intranet/images/pix-azul.gif" width="100%" height="1" alt=""><br></td>
								</tr> ';
								
								
								foreach($arreglo_noticias_periodico as $noticia)
								{
									// leemos el archivo adjunto
									
									$base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");
		
									if($base->num_rows() == 0)
									{
										$principal = 0;
									}
									else
									{
										$principal = 1;
										$archivo_principal = new Archivo($base->get_row_assoc());
									}
									
									
					$message.=	'<tr>
								  <td width="16%" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #333;">'.$noticia->getFecha().' </td>
								  <td width="84%"><p><span style="font-family: Tahoma, Geneva, sans-serif; font-weight: bold; font-size: x-small; color: #900;"><a href="http://sistema.operamedios.com.mx/data/noticias/periodico/'.$archivo_principal->getNombre_archivo().'">'.$noticia->getEncabezado().'</a></span><br>
									<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #666;">'.$noticia->getFuente().'</span><br>
									<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small;">'.$noticia->getSintesis().'<br>
									<br>
									</span></p></td>
								</tr>';
								
								} // end foreach
								
								
							} // end if si hay periodico
							
						
						if($total_rev > 0)
						{
							// revista
						
							$message.=	'<tr>
								<td colspan="2">
								<img src="http://www.operamedios.com.mx/intranet/images/trans.gif" width="1" height="15" alt=""><span style="font-family: Tahoma, Geneva, sans-serif">REVISTA</span><br>
							<img src="http://www.operamedios.com.mx/intranet/images/pix-azul.gif" width="100%" height="1" alt=""><br></td>
							</tr> ';
							
							
							foreach($arreglo_noticias_revista as $noticia)
							{
								// leemos el archivo adjunto
								
								$base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");
	
								if($base->num_rows() == 0)
								{
									$principal = 0;
								}
								else
								{
									$principal = 1;
									$archivo_principal = new Archivo($base->get_row_assoc());
								}
								
								
				$message.=	'<tr>
							  <td width="16%" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #333;">'.$noticia->getFecha().' </td>
							  <td width="84%"><p><span style="font-family: Tahoma, Geneva, sans-serif; font-weight: bold; font-size: x-small; color: #900;"><a href="http://sistema.operamedios.com.mx/data/noticias/revista/'.$archivo_principal->getNombre_archivo().'">'.$noticia->getEncabezado().'</a></span><br>
								<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #666;">'.$noticia->getFuente().'</span><br>
								<span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small;">'.$noticia->getSintesis().'<br>
								<br>
								</span></p></td>
							</tr>';
							
							} // end foreach
						
							
						} // end if total rev > 0
						
						
		} // end if muestraimpresos = 1
									
						
	     	$message.='</table></td>
						</tr>
						</table>
						
						<!-- tabla pie -->
						
						<table width="600" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="#FFFFFF">
							<td width="10">&nbsp;</td>
							<td valign="top"><br><font face="Tahoma" size="1">
							<b>Operadora de Medios Informativos, S.A. de C.V.</b><br>
						contacto@opemedios.com.mx </font><br>
						
						</td>
						</tr>
						<tr bgcolor="#FFFFFF">
							<td colspan="2"><img src="http://www.operamedios.com.mx/html/images/pie.gif" alt=""></td>
						</tr>
						</table><br>
						
						</div>
						
						</body>
						</html><br>';
						
						
			 	} // end else si hay noticias	
		
		
		
		
		
	} // end if  tipo ordenacion == 1
	else // tipo ordenacion !1 <==> 2
	{
		
		//metemos los datos de las cuentas en un arreglo de objetos Cuenta
			$arreglo_cuentas = array();
			foreach($arreglo_envia as $id_cuenta)
			{
				$base->execute_query("SELECT * FROM cuenta WHERE id_cuenta = ".$id_cuenta." LIMIT 1;");
				$cuenta = new Cuenta($base->get_row_assoc());
				$arreglo_cuentas[$cuenta->get_id()]=$cuenta;
			}
		
		////////////////////// proceso de ordenacion por temas
		
		//obtenemos rango de fecha y horas,
		
		$fecha = date("Y-m-d",mktime(0,0,0,$_POST['fecha_Month_ID'],$_POST['fecha_Day_ID'],$_POST['fecha_Year_ID']));
	    $hora1= date("H:i:s",mktime($_POST['hora_HH1'],$_POST['hora_MM1'],$_POST['hora_SS1'],1,1,2000));
	    $hora2= date("H:i:s",mktime($_POST['hora_HH2'],$_POST['hora_MM2'],$_POST['hora_SS2'],1,1,2000));
		
		// obtenemos los temas del cliente
		
		
		// hacemos thumbnail de logo del cliente
			$url_logo = "data/empresas";
			// leemos logo
			$base->execute_query("SELECT logo FROM empresa WHERE id_empresa = ".$_POST['row_id'].";");
			$res = $base->get_row_assoc();
			$logo = $res['logo'];
			$mime = substr($logo,-3,3);
			//die($logo."<br>".mime."<br>".$url_logo.$logo);
			$thumbnail = new thumbnail($url_logo."/".$logo,$url_logo,194,79,90,"_tn.");
			if($logo != "default.jpg")
			{
					$logo_mail = "http://sistema.operamedios.com.mx/data/empresas/".$logo."_tn.".$mime;
			}
			else
			{
				$logo_mail = "http://www.operamedios.com.mx/html/images/logo.gif";
			}
		
		
			//armamos los elementos del correo
			//
			//Se itera el arreglo de las cuentas para ver a quien se le manda el correo
			
			
		
			$array_to = array();
			foreach($arreglo_cuentas as $cuenta)
			{
				$array_to[] .=$cuenta->get_nombre_completo().' <'.$cuenta->get_email().'>';
			}
			$to = join(",", $array_to);
		
			//headers
			$headers  = "MIME-Version: 1.0\n";
			$headers .= "Return-Path: <noticias@operamedios.com.mx>\n";
			$headers .= "Content-type: text/html; charset=utf-8\n"; //iso-8859-1
			$headers .= "From: Noticias OPEMEDIOS <noticias@operamedios.com.mx>";
			
			
			$message = '
						<html>
						<head>
							<title>Operadora de Medios Informativos S.A. de C.V.</title>
						</head>
						
						<body background="http://www.operamedios.com.mx/html/images/fondo.gif" bgcolor="#1D6C9F" text="#2b4f71" link="#933838" vlink="#933838" alink="#2b4f71" leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0">
						
						<div align="center"><font face="Tahoma" size="1"><br>
						</font> 
						
						 
						<!-- tabla top -->
						<table width="600" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="#FFFFFF">
							<td width="194" valign="top">
							<img src="'.$logo_mail.'" alt=""><br></td>
							<td align="right" valign="top">
							  <p><img src="http://www.operamedios.com.mx/html/images/top.gif" alt=""><br>
							  <b><font face="Tahoma" size="2">Resumen de Noticias - '.getFecha_larga($fecha).'</font></b>&nbsp;<br>
							  <span style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small;">'.$hora1.'hrs a '.$hora2.' hrs</span>&nbsp;&nbsp;</p></td>
							
						</tr>
						</table>
						
						<!-- tabla desarrollo -->
						<table width="600" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="#FFFFFF">
							<td colspan="3">
							<div align="center"><br>
							
						<table width="94%" border="0" cellspacing="4" cellpadding="4">
						
						';
		
		
		
		//leemeos temas del cliente
		$base->execute_query("SELECT * FROM tema WHERE id_empresa=".$idcliente);
		
		$arreglo_temas = array();
		while($row_tema = $base->get_row_assoc())
		{
			$tema = new Tema($row_tema);
			$arreglo_temas[$tema->get_id()]=$tema;
		}
		
		
		foreach($arreglo_temas as $tema)
		{
			
			// añadimos encabezado de tema en el correo
			$message .= '<tr>
							<td colspan="2">
							<img src="http://www.operamedios.com.mx/intranet/images/trans.gif" width="1" height="15" alt=""><span style="font-family: Tahoma, Geneva, sans-serif; font-size: 14px;">'.$tema->get_nombre().'</span><br>
						<img src="http://www.operamedios.com.mx/intranet/images/pix-azul.gif" width="100%" height="1" alt=""><br>
						<img src="http://www.operamedios.com.mx/intranet/images/trans.gif" width="1" height="15" alt=""></td>
						</tr>
			';
			
			//creamos el arreglo odnde se alamacenaran los id de noticia a mostrar asi como su tipo de fuente
			$arreglo_noticias_temas = array();
			// seleccionamos las noticias de medios impresos si se mando la orden
			if( $_POST['muestra_impresos'] == 1)
			{
			
				//periodico				 
	        $query=      "SELECT
                          noticia.id_noticia AS id_noticia,
                          noticia.id_tipo_fuente AS id_tipo_fuente
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                         INNER JOIN noticia_per ON (noticia.id_noticia=noticia_per.id_noticia)
						 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
                    WHERE
					     asigna.id_empresa = ".$_POST['row_id']." AND
						 asigna.id_tema = ".$tema->get_id()." AND
					     noticia.fecha = '".$fecha."';";
						 
						 //die($query);
				$base->execute_query($query);
						 
				$total_per = $base->num_rows();
				
				if($total_per > 0)
				{
					while($row = $base->get_row_assoc())
					{
						$arreglo_noticias_temas[$row['id_noticia']] = $row['id_tipo_fuente'];
					}
					
				} // end if per = 0
				
		
		//revista
							 
	$query=             "SELECT
                          noticia.id_noticia AS id_noticia,
						  noticia.id_tipo_fuente AS id_tipo_fuente
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                         INNER JOIN noticia_rev ON (noticia.id_noticia=noticia_rev.id_noticia)
						 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
                    WHERE
					     asigna.id_empresa = ".$_POST['row_id']."  AND
						 asigna.id_tema = ".$tema->get_id()."  AND
					     noticia.fecha = '".$fecha."';";
						 
						 //die($query);
				$base->execute_query($query);
						 
						 
			$total_rev = $base->num_rows();
			
			if($total_rev > 0)
			{
				while($row = $base->get_row_assoc())
				{
					$arreglo_noticias_temas[$row['id_noticia']] = $row['id_tipo_fuente'];
				}
				
			} // end if rev = 0
				
						
	 } // end if muestra impresos
	 
	 
	 // procedemos a seleccionar las noticias de los medios electronicos, de acuerdo a los parametros de horario establecidos
					
			$query=      "SELECT
                          noticia.id_noticia AS id_noticia,
                          noticia.id_tipo_fuente AS id_tipo_fuente
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN fuente_tel ON (fuente.id_fuente = fuente_tel.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                         INNER JOIN noticia_tel ON (noticia.id_noticia=noticia_tel.id_noticia)
						 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
                    WHERE
					     asigna.id_empresa = ".$_POST['row_id']."  AND
						 asigna.id_tema = ".$tema->get_id()." AND
					     noticia.fecha = '".$fecha."' AND
                         noticia_tel.hora BETWEEN'".$hora1."' AND '".$hora2."'
				     ORDER BY hora ASC;";
						 
						 //die($query);
				$base->execute_query($query);
						 
	$total_tel = $base->num_rows();
	
	
	if($total_tel > 0)
	{
		while($row = $base->get_row_assoc())
				{
					$arreglo_noticias_temas[$row['id_noticia']] = $row['id_tipo_fuente'];
				}
		
	} // end if tel = 0
	
	
						  
						 
						 
	//ahora en radio 					 
	$query=             "SELECT
                          noticia.id_noticia AS id_noticia,
                          noticia.id_tipo_fuente AS id_tipo_fuente
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN fuente_rad ON (fuente.id_fuente = fuente_rad.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                         INNER JOIN noticia_rad ON (noticia.id_noticia=noticia_rad.id_noticia)
						 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
                    WHERE
					     asigna.id_empresa = ".$_POST['row_id']."  AND
						 asigna.id_tema = ".$tema->get_id()." AND
					     noticia.fecha = '".$fecha."' AND
                         noticia_rad.hora BETWEEN'".$hora1."' AND '".$hora2."'
				    ORDER BY hora ASC;";
						 
						 //die($query);
				$base->execute_query($query);
						 
						 
		$total_rad = $base->num_rows();
		
		if($total_rad > 0)
		{
			while($row = $base->get_row_assoc())
				{
					$arreglo_noticias_temas[$row['id_noticia']] = $row['id_tipo_fuente'];
				}
			
		} // end if rad = 0
		
		
		//ahora en internet					 
	$query=             "SELECT
                          noticia.id_noticia AS id_noticia,
                          noticia.id_tipo_fuente AS id_tipo_fuente
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                         INNER JOIN noticia_int ON (noticia.id_noticia=noticia_int.id_noticia)
						 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
                    WHERE
					     asigna.id_empresa = ".$_POST['row_id']."  AND
						 asigna.id_tema = ".$tema->get_id()." AND
					     noticia.fecha = '".$fecha."' AND
                         noticia_int.hora_publicacion BETWEEN'".$hora1."' AND '".$hora2."'
						 ORDER BY hora_publicacion ASC;";
						 
						 //die($query);
				$base->execute_query($query);
						 
						 
		$total_int = $base->num_rows();
		
		if($total_int > 0)
		{
			while($row = $base->get_row_assoc())
				{
					$arreglo_noticias_temas[$row['id_noticia']] = $row['id_tipo_fuente'];
				}
			
		} // end if int = 0
			
			
			$n_noticias_temas  = count($arreglo_noticias_temas);
			
			if($n_noticias_temas <= 0)
			{
				$message.=	'<tr>
							  <td colspan="2" style="font-family: Tahoma, Geneva, sans-serif; font-size: x-small; color: #333;">No se Reportaron Noticias referentes a éste tema</td>
							  </tr>
							';
			}
			else
			{
				
				foreach($arreglo_noticias_temas as $id_noticia => $id_tipo_fuente)
				{
					$txtmsg = imprime_noticia_resumen($base,$id_noticia,$id_tipo_fuente,$fecha,$hora1,$hora2);
					$message.= $txtmsg;
				}
				
				
			} // end else n_noticias_temas <= 0  ..  si hay noticias del tema
			
			
		} // end foreach arreglo temas
		
		
		
		$message.='</table></td>
						</tr>
						</table>
						
						<!-- tabla pie -->
						
						<table width="600" border="0" cellspacing="0" cellpadding="0">
						<tr bgcolor="#FFFFFF">
							<td width="10">&nbsp;</td>
							<td valign="top"><br><font face="Tahoma" size="1">
							<b>Operadora de Medios Informativos, S.A. de C.V.</b><br>
						contacto@opemedios.com.mx </font><br>
						
						</td>
						</tr>
						<tr bgcolor="#FFFFFF">
							<td colspan="2"><img src="http://www.operamedios.com.mx/html/images/pie.gif" alt=""></td>
						</tr>
						</table><br>
						
						</div>
						
						</body>
						</html><br>';

	
		
		
	}// end else  tipo ordenacion !1 <==> 2
	
	
	
				// ya debemos de tener el message en html asi q solo procedemos a enviar, antes poniendo un if si hubo noticias			
																
			$subject = "Resumen de Noticias ".$fecha." -- ".$hora1." a ".$hora2."hrs ";
			
			
			if(isset($_POST['previsualizar']) && $_POST['previsualizar'] == 1)
			{
				$base->free_result();
				$base->close();
				die($message);
			}
			
			else
			{
				mail($to, $subject, $message, $headers);
			}
			
		
			 
		
		
			$base->free_result();
			$base->close();
			header("Location: envio_bloque_noticias.php?mensaje=Se ha enviado el resumen");
			exit();
																		
																
																
				
		
		
	
	
} // end else si hay cuentas



?>