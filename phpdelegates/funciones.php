<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function getFecha_actual() {
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

    $dia = substr(date("Y-m-d"),8,2);
    $mes = date("n",mktime(00,00,00,substr(date("Y-m-d"),5,2),01,2000));
    $año = substr(date("Y-m-d"),0,4);

    return $dia." de ".$arreglo_meses[$mes]." de ".$año;
}


//funcion que convierte una hora en segundos, con el fin de comparar horarios al momento de buscar tarifas relacionadas
function strtimetosec($time) {
    if(strlen($time) != 8) {
        return -1;
    }
    else {
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
                else {
                    return false;
                }
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }

    }
}

function WordLimiter($text,$limit=20){
    $explode = explode(' ',$text);
    $string  = '';

    $dots = ' <strong> ...[contin&uacute;a]</strong>';
    if(count($explode) <= $limit){
        $dots = '';
    }
    for($i=0;$i<$limit;$i++){
        $string .= $explode[$i]." ";
    }
    if ($dots) {
        $string = substr($string, 0, strlen($string));
    }

    return $string.$dots;
}

function muestra_noticia(OpmDB $dao,$id_notic,$id_tipo_fuente,$rowcolor) {

//arreglo de los colores de la tabla
    $colores = array(1=>"#EAF1FF",
        2=>"#FCF5F1");

    //dependiendo del tipo de fuente se genera la tabla

    switch($id_tipo_fuente) {
        case 1:

        //metemos los horarios de las tarifas  en un arreglo
            $dao->execute_query("SELECT * FROM horario");
            $arreglo_horarios = array();
            while($row_horarios = $dao->get_row_assoc()) {
                $horario = new Horario($row_horarios);
                $arreglo_horarios[$horario->get_id()]=$horario;
            }

            // armamos el query de la noticia de TV
            $query ="   SELECT
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
                          fuente.nombre AS fuente,
                          seccion.nombre AS seccion,
                          sector.nombre AS sector,
                          tipo_fuente.descripcion AS tipo_fuente,
                          tipo_autor.descripcion AS tipo_autor,
                          genero.descripcion AS genero,
                          tendencia.descripcion AS tendencia,
                          asigna.id_tendencia AS id_tendencia,
                          noticia_tel.hora AS hora,
                          noticia_tel.duracion AS duracion,
						  noticia_tel.costo AS costo,
                          tema.nombre AS tema,
                          asigna.id_tema AS id_tema,
                          fuente_tel.canal AS canal

                        FROM
                         asigna
                         INNER JOIN noticia ON (asigna.id_noticia=noticia.id_noticia)
                         INNER JOIN empresa ON (asigna.id_empresa=empresa.id_empresa)
                         INNER JOIN fuente ON (fuente.id_fuente=noticia.id_fuente)
                         INNER JOIN fuente_tel ON (fuente_tel.id_fuente=fuente.id_fuente)
                         INNER JOIN noticia_tel ON (noticia_tel.id_noticia=noticia.id_noticia)
                         INNER JOIN genero ON (genero.id_genero=noticia.id_genero)
                         INNER JOIN tipo_autor ON (tipo_autor.id_tipo_autor=noticia.id_tipo_autor)
                         INNER JOIN tema ON (tema.id_tema=asigna.id_tema)
                         INNER JOIN tendencia ON (tendencia.id_tendencia=asigna.id_tendencia)
                         INNER JOIN sector ON (sector.id_sector=noticia.id_sector)
                         INNER JOIN seccion ON (seccion.id_seccion=noticia.id_seccion)
                         INNER JOIN tipo_fuente ON (tipo_fuente.id_tipo_fuente=noticia.id_tipo_fuente)

                         WHERE
                         noticia.id_noticia = ".$id_notic;

            $dao->execute_query($query);
            $noticia = new SuperNoticia($dao->get_row_assoc());

            //costo-beneficio
			
			if($noticia->getCosto() == "")
			{
				$c_b = "N/D";
			}
			else
			{
				$c_b = $noticia->getCosto(); 
				$_SESSION['suma_costo']+= $noticia->getCosto();
			}
	
           
            // generamos la salida:
            $new_back = array();
            $new_back[].= '<table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="'.$colores[$rowcolor].'">';
            $new_back[].= '<tr bgcolor="#ffede1">
                                <td class="desarrollo1">&nbsp; <b>Televisi&oacute;n</b> | <b>Tema:</b> '.$noticia->getTema().' | <b>Fecha:</b> '.$noticia->getFecha_larga().' | <b>Hora:</b> '.$noticia->getHora().' | <b>Canal:</b> '.$noticia->getCanal().'</td>
                                <td align="right" class="desarrollo1"><b>Clave:</b>'.$noticia->getId().'&nbsp;</td>
                                <td width="150" align="right" bgcolor="#c0d2e6" class="desarrollo1"><b>Costo/Beneficio: $'.$c_b.'</b>&nbsp;</td>
                           </tr>';
            $new_back[].= '<tr>
                                <td colspan="3" class="desarrollo">
                                <div align="justify" style="margin-left: 10px; margin-right: 10px; margin-top: 10px; margin-bottom: 5px;">

                                <a href="noticia_detalle_electronico.php?id_noticia='.$noticia->getId().'&id_tipo_fuente='.$noticia->getId_tipo_fuente().'" class="titulo-news"><b>'.$noticia->getEncabezado().'</b></a><br>
                                <img src="images/trans.gif" width="1" height="5" alt=""><br>
                                '.$noticia->getSintesis().' <br></div>
                        <div align="right"></div>
                                </td>
                        </tr>';
            $new_back[].= '<tr>
                                <td colspan="3" class="desarrollo2">
                                <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
                        <img src="images/trans.gif" width="1" height="5" alt=""><br>

                                &nbsp; <b>Fuente:</b> '.$noticia->getFuente().' | <b>Autor:</b> '.$noticia->getAutor().'  |  <b>Secci&oacute;n:</b> '.$noticia->getSeccion().'  | <b>G&eacute;nero:</b> '.utf8_encode($noticia->getGenero()).'  |  <b>Tendencia:</b> '.$noticia->getTendencia().'  | <b>Sector:</b> '.$noticia->getSector().'<br>

                                <img src="images/trans.gif" width="1" height="12" alt=""><br>


                                 </td>
                        </tr>
                        </table><br>';

            $output = join("", $new_back);

            break;  // end case 1, television

        case 2: // radio

        //metemos los horarios de las tarifas  en un arreglo
            $dao->execute_query("SELECT * FROM horario");
            $arreglo_horarios = array();
            while($row_horarios = $dao->get_row_assoc()) {
                $horario = new Horario($row_horarios);
                $arreglo_horarios[$horario->get_id()]=$horario;
            }

            // armamos el query de la noticia de radio
            $query ="   SELECT
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
                          fuente.nombre AS fuente,
                          seccion.nombre AS seccion,
                          sector.nombre AS sector,
                          tipo_fuente.descripcion AS tipo_fuente,
                          tipo_autor.descripcion AS tipo_autor,
                          genero.descripcion AS genero,
                          tendencia.descripcion AS tendencia,
                          asigna.id_tendencia AS id_tendencia,
                          noticia_rad.hora AS hora,
                          noticia_rad.duracion AS duracion,
						  noticia_rad.costo AS costo,
                          tema.nombre AS tema,
                          asigna.id_tema AS id_tema,
                          fuente_rad.estacion AS estacion

                        FROM
                         asigna
                         INNER JOIN noticia ON (asigna.id_noticia=noticia.id_noticia)
                         INNER JOIN empresa ON (asigna.id_empresa=empresa.id_empresa)
                         INNER JOIN fuente ON (fuente.id_fuente=noticia.id_fuente)
                         INNER JOIN fuente_rad ON (fuente_rad.id_fuente=fuente.id_fuente)
                         INNER JOIN noticia_rad ON (noticia_rad.id_noticia=noticia.id_noticia)
                         INNER JOIN genero ON (genero.id_genero=noticia.id_genero)
                         INNER JOIN tipo_autor ON (tipo_autor.id_tipo_autor=noticia.id_tipo_autor)
                         INNER JOIN tema ON (tema.id_tema=asigna.id_tema)
                         INNER JOIN tendencia ON (tendencia.id_tendencia=asigna.id_tendencia)
                         INNER JOIN sector ON (sector.id_sector=noticia.id_sector)
                         INNER JOIN seccion ON (seccion.id_seccion=noticia.id_seccion)
                         INNER JOIN tipo_fuente ON (tipo_fuente.id_tipo_fuente=noticia.id_tipo_fuente)

                         WHERE
                         noticia.id_noticia = ".$id_notic;

            $dao->execute_query($query);
            $noticia = new SuperNoticia($dao->get_row_assoc());

            

           //costo-beneficio
			
			if($noticia->getCosto() == "")
			{
				$c_b = "N/D";
			}
			else
			{
				$c_b = $noticia->getCosto(); 
				$_SESSION['suma_costo']+= $noticia->getCosto();
			}

            // generamos la salida:

            $new_back = array();
            $new_back[].= '<table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="'.$colores[$rowcolor].'">';
            $new_back[].= '<tr bgcolor="#ffede1">
                                <td class="desarrollo1">&nbsp; <b>Radio</b> | <b>Tema:</b> '.$noticia->getTema().' | <b>Fecha:</b> '.$noticia->getFecha_larga().' | <b>Hora:</b> '.$noticia->getHora().' | <b>Estaci&oacute;n:</b> '.$noticia->getEstacion().'</td>
                                <td align="right" class="desarrollo1"><b>Clave:</b>'.$noticia->getId().'&nbsp;</td>
                                <td width="150" align="right" bgcolor="#c0d2e6" class="desarrollo1"><b>Costo/Beneficio: $'.$c_b.'</b>&nbsp;</td>
                           </tr>';
            $new_back[].= '<tr>
                                <td colspan="3" class="desarrollo">
                                <div align="justify" style="margin-left: 10px; margin-right: 10px; margin-top: 10px; margin-bottom: 5px;">

                                <a href="noticia_detalle_electronico.php?id_noticia='.$noticia->getId().'&id_tipo_fuente='.$noticia->getId_tipo_fuente().'" class="titulo-news"><b>'.$noticia->getEncabezado().'</b></a><br>
                                <img src="images/trans.gif" width="1" height="5" alt=""><br>
                                '.$noticia->getSintesis().' <br></div>
                        <div align="right"></div>
                                </td>
                        </tr>';
            $new_back[].= '<tr>
                                <td colspan="3" class="desarrollo2">
                                <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
                        <img src="images/trans.gif" width="1" height="5" alt=""><br>

                                &nbsp; <b>Fuente:</b> '.$noticia->getFuente().' | <b>Autor:</b> '.$noticia->getAutor().'  |  <b>Secci&oacute;n:</b> '.$noticia->getSeccion().'  | <b>G&eacute;nero:</b> '.utf8_encode($noticia->getGenero()).'  |  <b>Tendencia:</b> '.$noticia->getTendencia().'  | <b>Sector:</b> '.$noticia->getSector().'<br>

                                <img src="images/trans.gif" width="1" height="12" alt=""><br>


                                 </td>
                        </tr>
                        </table><br>';

            $output = join("", $new_back);

            break;  // end case 2, radio

        case 3: // periodico

        //hacemos consulta para la noticia de periodico
            $query = "SELECT
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
                          fuente.nombre AS fuente,
                          seccion.nombre AS seccion,
                          sector.nombre AS sector,
                          tipo_fuente.descripcion AS tipo_fuente,
                          tipo_autor.descripcion AS tipo_autor,
                          genero.descripcion AS genero,
                          noticia_per.pagina AS pagina,
                          noticia_per.id_tipo_pagina AS id_tipo_pagina,
                          noticia_per.porcentaje_pagina AS porcentaje_pagina,
                          tipo_pagina.descripcion AS tipo_pagina,
                          tema.nombre AS tema,
                          asigna.id_tema AS id_tema,
                          fuente_per.tiraje AS tiraje,
                          tendencia.descripcion AS tendencia,
                          asigna.id_tendencia AS id_tendencia
                    FROM
                         asigna
                         INNER JOIN noticia ON (asigna.id_noticia=noticia.id_noticia)
                         INNER JOIN empresa ON (asigna.id_empresa=empresa.id_empresa)
                         INNER JOIN fuente ON (fuente.id_fuente=noticia.id_fuente)
                         INNER JOIN fuente_per ON (fuente_per.id_fuente=fuente.id_fuente)
                         INNER JOIN noticia_per ON (noticia_per.id_noticia=noticia.id_noticia)
                         INNER JOIN genero ON (genero.id_genero=noticia.id_genero)
                         INNER JOIN tipo_autor ON (tipo_autor.id_tipo_autor=noticia.id_tipo_autor)
                         INNER JOIN tema ON (tema.id_tema=asigna.id_tema)
                         INNER JOIN tendencia ON (tendencia.id_tendencia=asigna.id_tendencia)
                         INNER JOIN sector ON (sector.id_sector=noticia.id_sector)
                         INNER JOIN seccion ON (seccion.id_seccion=noticia.id_seccion)
                         INNER JOIN tipo_fuente ON (tipo_fuente.id_tipo_fuente=noticia.id_tipo_fuente)
                         INNER JOIN tipo_pagina ON (tipo_pagina.id_tipo_pagina=noticia_per.id_tipo_pagina)

                         WHERE
                         noticia.id_noticia = ".$id_notic;


            $dao->execute_query($query);
            $noticia = new SuperNoticia($dao->get_row_assoc());

            // buscamos el costo/beneficio
			
			
			//metemos las tarifas en un arreglo
			$arreglo_tarifas = array();
			$tarifas = 0;
			
			//si hay una tarifa con el tamaño exacto de la nota creamos solo una  con el precio establecido
			$dao->execute_query("SELECT * FROM cuesta_prensa
								  WHERE
									  id_fuente = ".$noticia->getId_fuente()."
								  AND id_seccion = ".$noticia->getId_seccion()."
								  AND id_tipo_pagina = ".$noticia->getId_tipo_pagina().";");
			
			if($dao->num_rows()>0)
			{
				$tarifas = 1;
			
				while($row_tarifa = $dao->get_row_assoc())
				{
					$tarifa = new TarifaPrensa($row_tarifa);
					$dao->execute_query2("SELECT * FROM seccion WHERE id_seccion=".$row_tarifa['id_seccion']." LIMIT 1");
					$seccion = new Seccion($dao->get_row_assoc2());
					$tarifa->set_seccion($seccion);
					$precio_noticia = $tarifa->get_precio() * ($noticia->getPorcentaje_pagina()/100);
					$tarifa->setPrecio_noticia($precio_noticia);
					$arreglo_tarifas[$tarifa->get_id_fuente()."_".$tarifa->get_seccion()->get_id()."_".$tarifa->get_id_tipo_pagina()]=$tarifa;
				}
				$c_b = $precio_noticia;
				$_SESSION['suma_costo'] += $precio_noticia;
			}
			
			else // si no hubo una con el tamaño exacto ya no se hace nada
			{
				$tarifas = 0;
				$precio_noticia = "N/D";
			}
			
			

            // generamos salida
            $new_back = array();
            $new_back[].= '<table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="'.$colores[$rowcolor].'">';
            $new_back[].= '<tr bgcolor="#ffede1">
                                <td class="desarrollo1">&nbsp; <b>Periodico</b> | <b>Tema:</b> '.$noticia->getTema().' | <b>Fecha:</b> '.$noticia->getFecha_larga().' | <b>P&aacute;gina:</b> '.$noticia->getPagina().' ('.$noticia->getTipo_pagina().') | <b>Tamaño:</b> '.$noticia->getPorcentaje_pagina().' %</td>
                                <td align="right" class="desarrollo1"><b>Clave:</b>'.$noticia->getId().'&nbsp;</td>
                                <td width="150" align="right" bgcolor="#c0d2e6" class="desarrollo1"><b>Costo/Beneficio: $'.$c_b.'</b>&nbsp;</td>
                           </tr>';
            $new_back[].= '<tr>
                                <td colspan="3" class="desarrollo">
                                <div align="justify" style="margin-left: 10px; margin-right: 10px; margin-top: 10px; margin-bottom: 5px;">

                                <a href="noticia_detalle_prensa.php?id_noticia='.$noticia->getId().'&id_tipo_fuente='.$noticia->getId_tipo_fuente().'" class="titulo-news"><b>'.$noticia->getEncabezado().'</b></a><br>
                                <img src="images/trans.gif" width="1" height="5" alt=""><br>
                                '.WordLimiter($noticia->getSintesis(), 130).' <br></div>
                        <div align="right"></div>
                                </td>
                        </tr>';
            $new_back[].= '<tr>
                                <td colspan="3" class="desarrollo2">
                                <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
                        <img src="images/trans.gif" width="1" height="5" alt=""><br>

                                &nbsp; <b>Fuente:</b> '.$noticia->getFuente().' | <b>Autor:</b> '.$noticia->getAutor().'  |  <b>Secci&oacute;n:</b> '.$noticia->getSeccion().'  | <b>G&eacute;nero:</b> '.utf8_encode($noticia->getGenero()).'  |  <b>Tendencia:</b> '.$noticia->getTendencia().'  | <b>Sector:</b> '.$noticia->getSector().'<br>

                                <img src="images/trans.gif" width="1" height="12" alt=""><br>


                                 </td>
                        </tr>
                        </table><br>';

            $output = join("", $new_back);


            break; // end case 3, periodico

        case 4: // revista

        //hacemos consulta para la noticia de periodico
            $query = "SELECT
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
                          fuente.nombre AS fuente,
                          seccion.nombre AS seccion,
                          sector.nombre AS sector,
                          tipo_fuente.descripcion AS tipo_fuente,
                          tipo_autor.descripcion AS tipo_autor,
                          genero.descripcion AS genero,
                          noticia_rev.pagina AS pagina,
                          noticia_rev.id_tipo_pagina AS id_tipo_pagina,
                          noticia_rev.porcentaje_pagina AS porcentaje_pagina,
                          tipo_pagina.descripcion AS tipo_pagina,
                          tema.nombre AS tema,
                          asigna.id_tema AS id_tema,
                          fuente_rev.tiraje AS tiraje,
                          tendencia.descripcion AS tendencia,
                          asigna.id_tendencia AS id_tendencia
                    FROM
                         asigna
                         INNER JOIN noticia ON (asigna.id_noticia=noticia.id_noticia)
                         INNER JOIN empresa ON (asigna.id_empresa=empresa.id_empresa)
                         INNER JOIN fuente ON (fuente.id_fuente=noticia.id_fuente)
                         INNER JOIN fuente_rev ON (fuente_rev.id_fuente=fuente.id_fuente)
                         INNER JOIN noticia_rev ON (noticia_rev.id_noticia=noticia.id_noticia)
                         INNER JOIN genero ON (genero.id_genero=noticia.id_genero)
                         INNER JOIN tipo_autor ON (tipo_autor.id_tipo_autor=noticia.id_tipo_autor)
                         INNER JOIN tema ON (tema.id_tema=asigna.id_tema)
                         INNER JOIN tendencia ON (tendencia.id_tendencia=asigna.id_tendencia)
                         INNER JOIN sector ON (sector.id_sector=noticia.id_sector)
                         INNER JOIN seccion ON (seccion.id_seccion=noticia.id_seccion)
                         INNER JOIN tipo_fuente ON (tipo_fuente.id_tipo_fuente=noticia.id_tipo_fuente)
                         INNER JOIN tipo_pagina ON (tipo_pagina.id_tipo_pagina=noticia_rev.id_tipo_pagina)

                         WHERE
                         noticia.id_noticia = ".$id_notic;


            $dao->execute_query($query);
            $noticia = new SuperNoticia($dao->get_row_assoc());
		
			
			// buscamos el costo/beneficio
			
			
			//metemos las tarifas en un arreglo
			$arreglo_tarifas = array();
			$tarifas = 0;
			
			//si hay una tarifa con el tamaño exacto de la nota creamos solo una  con el precio establecido
			$dao->execute_query("SELECT * FROM cuesta_prensa
								  WHERE
									  id_fuente = ".$noticia->getId_fuente()."
								  AND id_seccion = ".$noticia->getId_seccion()."
								  AND id_tipo_pagina = ".$noticia->getId_tipo_pagina().";");
			
			if($dao->num_rows()>0)
			{
				$tarifas = 1;
			
				while($row_tarifa = $dao->get_row_assoc())
				{
					$tarifa = new TarifaPrensa($row_tarifa);
					$dao->execute_query2("SELECT * FROM seccion WHERE id_seccion=".$row_tarifa['id_seccion']." LIMIT 1");
					$seccion = new Seccion($dao->get_row_assoc2());
					$tarifa->set_seccion($seccion);
					$precio_noticia = $tarifa->get_precio() * ($noticia->getPorcentaje_pagina()/100);
					$tarifa->setPrecio_noticia($precio_noticia);
					$arreglo_tarifas[$tarifa->get_id_fuente()."_".$tarifa->get_seccion()->get_id()."_".$tarifa->get_id_tipo_pagina()]=$tarifa;
				}
				$c_b = $precio_noticia;
				$_SESSION['suma_costo'] += $precio_noticia;
			}
			
			else // si no hubo una con el tamaño exacto ya no se hace nada
			{
				$tarifas = 0;
				$precio_noticia = "N/D";
			}
			

            // generamos salida
           
            $new_back = array();
            $new_back[].= '<table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="'.$colores[$rowcolor].'">';
            $new_back[].= '<tr bgcolor="#ffede1">
                                <td class="desarrollo1">&nbsp; <b>Revista</b> | <b>Tema:</b> '.$noticia->getTema().' | <b>Fecha:</b> '.$noticia->getFecha_larga().' | <b>P&aacute;gina:</b> '.$noticia->getPagina().' ('.$noticia->getTipo_pagina().') | <b>Tamaño:</b> '.$noticia->getPorcentaje_pagina().' %</td>
                                <td align="right" class="desarrollo1"><b>Clave:</b>'.$noticia->getId().'&nbsp;</td>
                                <td width="150" align="right" bgcolor="#c0d2e6" class="desarrollo1"><b>Costo/Beneficio: $'.$c_b.'</b>&nbsp;</td>
                           </tr>';
            $new_back[].= '<tr>
                                <td colspan="3" class="desarrollo">
                                <div align="justify" style="margin-left: 10px; margin-right: 10px; margin-top: 10px; margin-bottom: 5px;">

                                <a href="noticia_detalle_prensa.php?id_noticia='.$noticia->getId().'&id_tipo_fuente='.$noticia->getId_tipo_fuente().'" class="titulo-news"><b>'.$noticia->getEncabezado().'</b></a><br>
                                <img src="images/trans.gif" width="1" height="5" alt=""><br>
                                '.$noticia->getSintesis().' <br></div>
                        <div align="right"></div>
                                </td>
                        </tr>';
            $new_back[].= '<tr>
                                <td colspan="3" class="desarrollo2">
                                <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
                        <img src="images/trans.gif" width="1" height="5" alt=""><br>

                                &nbsp; <b>Fuente:</b> '.$noticia->getFuente().' | <b>Autor:</b> '.$noticia->getAutor().'  |  <b>Secci&oacute;n:</b> '.$noticia->getSeccion().'  | <b>G&eacute;nero:</b> '.utf8_encode($noticia->getGenero()).'  |  <b>Tendencia:</b> '.$noticia->getTendencia().'  | <b>Sector:</b> '.$noticia->getSector().'<br>

                                <img src="images/trans.gif" width="1" height="12" alt=""><br>


                                 </td>
                        </tr>
                        </table><br>';

            $output = join("", $new_back);


            break; // end case 4, revista

        case 5: // internet

        //hacemos consulta para la noticia de internet
            $query = "SELECT
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
                          fuente.nombre AS fuente,
                          seccion.nombre AS seccion,
                          sector.nombre AS sector,
                          tipo_fuente.descripcion AS tipo_fuente,
                          tipo_autor.descripcion AS tipo_autor,
                          genero.descripcion AS genero,
                          noticia_int.url AS url,
                          tema.nombre AS tema,
                          asigna.id_tema AS id_tema,
                          tendencia.descripcion AS tendencia,
                          asigna.id_tendencia AS id_tendencia
                    FROM
                         asigna
                         INNER JOIN noticia ON (asigna.id_noticia=noticia.id_noticia)
                         INNER JOIN empresa ON (asigna.id_empresa=empresa.id_empresa)
                         INNER JOIN fuente ON (fuente.id_fuente=noticia.id_fuente)
                         INNER JOIN fuente_int ON (fuente_int.id_fuente=fuente.id_fuente)
                         INNER JOIN noticia_int ON (noticia_int.id_noticia=noticia.id_noticia)
                         INNER JOIN genero ON (genero.id_genero=noticia.id_genero)
                         INNER JOIN tipo_autor ON (tipo_autor.id_tipo_autor=noticia.id_tipo_autor)
                         INNER JOIN tema ON (tema.id_tema=asigna.id_tema)
                         INNER JOIN tendencia ON (tendencia.id_tendencia=asigna.id_tendencia)
                         INNER JOIN sector ON (sector.id_sector=noticia.id_sector)
                         INNER JOIN seccion ON (seccion.id_seccion=noticia.id_seccion)
                         INNER JOIN tipo_fuente ON (tipo_fuente.id_tipo_fuente=noticia.id_tipo_fuente)

                         WHERE
                         noticia.id_noticia = ".$id_notic;


            $dao->execute_query($query);
            $noticia = new SuperNoticia($dao->get_row_assoc());

            // no existe costo/beneficio, procedemos a generar output

            $c_b = "N/D";

            // generamos salida
            $new_back = array();
            $new_back[].= '<table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="'.$colores[$rowcolor].'">';
            $new_back[].= '<tr bgcolor="#ffede1">
                                <td class="desarrollo1">&nbsp; <b>Internet</b> | <b>Tema:</b> '.$noticia->getTema().' | <b>Fecha:</b> '.$noticia->getFecha_larga().' | <b>URL: </b><a target="_blank" href="'.$noticia->getUrl().'">Ir a URL</a></td>
                                <td align="right" class="desarrollo1"><b>Clave:</b>'.$noticia->getId().'&nbsp;</td>
                                <td width="150" align="right" bgcolor="#c0d2e6" class="desarrollo1"><b>Costo/Beneficio: $'.$c_b.'</b>&nbsp;</td>
                           </tr>';
            $new_back[].= '<tr>
                                <td colspan="3" class="desarrollo">
                                <div align="justify" style="margin-left: 10px; margin-right: 10px; margin-top: 10px; margin-bottom: 5px;">

                                <a href="noticia_detalle_internet.php?id_noticia='.$noticia->getId().'" class="titulo-news"><b>'.$noticia->getEncabezado().'</b></a><br>
                                <img src="images/trans.gif" width="1" height="5" alt=""><br>
                                '.$noticia->getSintesis().' <br></div>
                        <div align="right"></div>
                                </td>
                        </tr>';
            $new_back[].= '<tr>
                                <td colspan="3" class="desarrollo2">
                                <img src="images/pix-azul.gif" width="100%" height="1" alt=""><br>
                        <img src="images/trans.gif" width="1" height="5" alt=""><br>

                                &nbsp; <b>Fuente:</b> '.$noticia->getFuente().' | <b>Autor:</b> '.$noticia->getAutor().'  |  <b>Secci&oacute;n:</b> '.$noticia->getSeccion().'  | <b>G&eacute;nero:</b> '.utf8_encode($noticia->getGenero()).'  |  <b>Tendencia:</b> '.$noticia->getTendencia().'  | <b>Sector:</b> '.$noticia->getSector().'<br>

                                <img src="images/trans.gif" width="1" height="12" alt=""><br>


                                 </td>
                        </tr>
                        </table><br>';

            $output = join("", $new_back);


            break; // end case 5, internet



    } // end switch


























    return  $output;


} // end function

?>
