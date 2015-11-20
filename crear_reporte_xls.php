<?php 
header('Content-type: application/vnd.ms-excel; charset=utf-8');
header("Content-Disposition: attachment; filename=archivo.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Genera reporte de las noticias que tiene un cliente de acuerdo a determinados parametros
//
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");

//creamos un DAO para obtener los datos de la fuente dada mediante el metodo GET
//recibe como parametro el resultado de la funcion
$base = new OpmDB(genera_arreglo_BD());
$base2 = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();
$base2->init();

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType)
    {
        case "text":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "long":
            break;
        case "int":
            $theValue = ($theValue != "") ? intval($theValue) : "NULL";
            break;
        case "palabrasclave":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "frasecompleta":
            $theValue = ($theValue != '') ? '"' . $theValue . '"' : 'NULL';
            break;
        case "textolimpio":
            $theValue = ($theValue != '') ?  $theValue  : 'NULL';
            break;
        case "double":
            $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
            break;
        case "date":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "defined":
            $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
            break;
    }
    return $theValue;
}
    $query = array();
    $jquery = array();

    $query[] .= "SELECT
                  noticia.id_noticia AS Clave,
                  noticia.encabezado AS Encabezado,
                  noticia.sintesis AS SintesisNoticia,
                  noticia.autor AS AutorNoticia,
                  noticia.fecha AS Fecha,
                  noticia.id_tipo_fuente AS TipoFuente,
                  fuente.nombre AS NombreFuente,
                  fuente.logo AS LogoFuente,
                  seccion.nombre AS NombreSeccion";
    $query[] .= 'FROM
                 noticia
                 INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                 INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )';
$jquery_from .= 'FROM
                 noticia
                 INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                 INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )';
    
   if($_GET['type']=='monitorista'){
    $type_query = ' noticia.id_usuario = '.$_GET['row_id'].' AND'  ;
    }
   if($_GET['type']=='cliente'){
    $type_query = ' asigna.id_empresa = '.$_GET['row_id'].' AND'  ;
    }

    $query[] .= 'WHERE'.$type_query ;// aqui ojo
    $jquery[] .= 'WHERE'.$type_query ;// aqui ojo

    $fecha1 = date("Y-m-d",mktime(0,0,0,$_GET['fecha1_Month_ID'],$_GET['fecha1_Day_ID'],$_GET['fecha1_Year_ID']));
    $fecha2 = date("Y-m-d",mktime(0,0,0,$_GET['fecha2_Month_ID'],$_GET['fecha2_Day_ID'],$_GET['fecha2_Year_ID']));

    $query[] .= "(noticia.fecha BETWEEN '".$fecha1."' AND '".$fecha2."')";
    $jquery[] .= "(noticia.fecha BETWEEN '".$fecha1."' AND '".$fecha2."')";

    $sector = $_GET['id_sector'];
    $genero = $_GET['id_genero'];
    $tipoautor = $_GET['id_tipo_autor'];
    $tendencia = $_GET['id_tendencia_monitorista'];
    $tipofuente = $_GET['id_tipo_fuente'];
    $tema = $_GET['id_tema'];


    if($sector != 0) {$query[] .= 'AND noticia.id_sector = '.$sector ;$jquery[] .= 'AND noticia.id_sector = '.$sector ;}
    if($genero != 0) {$query[] .= 'AND noticia.id_genero = '.$genero ;$jquery[] .= 'AND noticia.id_genero = '.$genero ;}
    if($tipoautor != 0) {$query[] .= 'AND noticia.id_tipo_autor = '.$tipoautor ;$jquery[] .= 'AND noticia.id_tipo_autor = '.$tipoautor ;}
    if($tendencia != 0) {$query[] .= 'AND noticia.id_tendencia_monitorista = '.$tendencia ;$jquery[] .= 'AND noticia.id_tendencia_monitorista = '.$tendencia ;}
    if($tema != 0) {$query[] .= 'AND asigna.id_tema = '.$tema ;$jquery[] .= 'AND asigna.id_tema = '.$tema ;}

    if($tipofuente != 0) {$query[] .= 'AND noticia.id_tipo_fuente = '.$tipofuente ;$jquery[] .= 'AND noticia.id_tipo_fuente = '.$tipofuente ;}
    if(isset($_GET['id_fuente']) && $_GET['id_fuente']!= 0){$query[] .= 'AND noticia.id_fuente = '.$_GET['id_fuente'] ;$jquery[] .= 'AND noticia.id_fuente = '.$_GET['id_fuente'] ;}
    if(isset($_GET['id_seccion']) && $_GET['id_seccion']!= 0){$query[] .= 'AND noticia.id_seccion = '.$_GET['id_seccion'] ;$jquery[] .= 'AND noticia.id_seccion = '.$_GET['id_seccion'] ;}

    $query[] .= $order;

$query_entero = join(" ", $query);

$query_from = strstr($query_entero, 'FROM');
$jquery_where = join(" ", $jquery);

if($_GET['id_tipo_autor'] != 0){
$base->execute_query("SELECT descripcion AS name FROM tipo_autor WHERE id_tipo_autor = ".$tipoautor);
$ntipoautor =  $base->get_row_assoc();
}else{ $ntipoautor['name'] = 'Todos';}

if($_GET['id_sector']!=0){
$base->execute_query("SELECT nombre  AS name FROM sector WHERE id_sector = ".$_GET['id_sector']);
$nsector = $base->get_row_assoc();
}else{ $nsector['name'] = 'Todos';}

if($_GET['id_genero']!=0){
$base->execute_query("SELECT descripcion AS name FROM genero WHERE id_genero = ".$_GET['id_genero']);
$ngenero = $base->get_row_assoc();
}else{ $ngenero['name'] = 'Todos';}

if($_GET['id_tendencia_monitorista']!=0){
$base->execute_query("SELECT descripcion AS name FROM tendencia WHERE id_tendencia = ".$_GET['id_tendencia_monitorista']);
$ntendencia = $base->get_row_assoc();
}else{ $ntendencia['name'] = 'Todos';}

if($_GET['id_tipo_fuente']!=0){
$base->execute_query("SELECT descripcion AS name FROM tipo_fuente WHERE id_tipo_fuente = ".$_GET['id_tipo_fuente']);
$ntipofuente = $base->get_row_assoc();
}else{ $ntipofuente['name'] = 'Todos';}

if(isset($_GET['id_fuente'])){
$base->execute_query("SELECT nombre AS name FROM fuente WHERE id_fuente = ".$_GET['id_fuente']);
$nfuente = $base->get_row_assoc();
}else{ $nfuente['name'] = 'Todos';}

if($_GET['id_tema']!=0){
$base->execute_query("SELECT nombre AS name FROM tema WHERE id_tema = ".$_GET['id_tema']);
$ntema = $base->get_row_assoc();
}else{ $ntema['name'] = 'Todos';}

if($_GET['id_seccion']!=""){
$base->execute_query("SELECT nombre AS name FROM seccion WHERE id_seccion = ".$_GET['id_seccion']);
$nseccion = $base->get_row_assoc();
}else{ $nseccion['name'] = 'Todos';}

$base->execute_query("SELECT nombre AS name FROM empresa WHERE id_empresa = ".$_GET['row_id']);
$nempresa = $base->get_row_assoc();

//
// variables y funciones para la parte 2.1
//

$tf_nn = 0; // numero de noticias que tiene un tipo de fuente
$tf_ns = 0; // numero de secciones que tiene un tipo de fuente
$array_fuente_count = array(); // referencia a el id de una fuente
$array_fuente_nombre = array(); // nombre de la fuente
$array_fuente_nn = array(); // numero de noticias que tiene una fuente
$array_fuente_ns = array(); // numero de secciones que tiene una fuente
$array_secc_count = array(); // referencia al id de una seccion
$array_secc_nombre = array(); // los nombres de las secciones
$array_secc_nn = array(); // numero de noticias que tiene una seccion
$_2_1_totales = 0; // noticias totales
// auxiliares de iteracion
$f_count = 1;
$s_count = 1;
$s_count_f = 1;
$flag_inicio_tf = true;

// obtenemos las noticias totales

$query = array();

    $query[] .= "SELECT COUNT(noticia.id_noticia) AS totales
                FROM
                 noticia
                 INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                 INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
";
    $query[] .= $jquery_where;

    $query_entero = join(" ",$query);

    $base->execute_query($query_entero);

    $row = $base->get_row_assoc();

    $_2_1_totales = $row['totales'];


// funcion que llena las variables anteriores dependiendo del tipo de fuente

function llenaArreglos($id_tipo_fuente)
{
    global $tf_nn, $tf_ns, $array_fuente_count, $array_fuente_nombre, $array_fuente_nn, $array_fuente_ns,
           $array_secc_count, $array_secc_nombre, $array_secc_nn, $jquery_where, $base;

    // tf_nn
    $query = array();

    $query[] .= "SELECT COUNT(*)
                FROM
                 noticia
                 INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                 INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
";
    $query[] .= $jquery_where;
    $query[] .= "AND noticia.id_tipo_fuente = ".$id_tipo_fuente;

    $query_entero = join(" ",$query);

    $base->execute_query($query_entero);

    $row = $base->get_row_assoc();

    $tf_nn = $row['COUNT(*)'];

    // tf_ns

    $query = array();
    $query[] .= "SELECT COUNT(seccion.id_seccion)
                FROM
                 noticia
                 INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                 INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )";
    $query[] .= $jquery_where;
    $query[] .= "AND noticia.id_tipo_fuente = ".$id_tipo_fuente." ";
    $query[] .= "GROUP BY seccion.id_seccion";

    $query_entero = join(" ",$query);

    $base->execute_query($query_entero);

    $tf_ns = $base->num_rows();

    //
    //$array_fuente_count = array(); // referencia a el id de una fuente
    //$array_fuente_nombre
    //
    $query = array();
    $query[] .= "SELECT fuente.id_fuente AS id_fuente, fuente.nombre AS nombre
                FROM
                 noticia
                 INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                 INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )";
    $query[] .= $jquery_where;
    $query[] .= "AND noticia.id_tipo_fuente = ".$id_tipo_fuente." ";
    $query[] .= "GROUP BY fuente.id_fuente ORDER BY nombre";

    $query_entero = join(" ",$query);

    $base->execute_query($query_entero);

    while($row = $base->get_row_assoc())
    {
        $array_fuente_nombre[$row['id_fuente']] = $row['nombre'];
    }
    $i = 1;
    foreach($array_fuente_nombre as $id => $nombre)
    {
        $array_fuente_count[$i] = $id;
        $i++;
    }

    //$array_fuente_nn = array(); // numero de noticias que tiene una fuente

    $query = array();
    $query[] .= "SELECT fuente.id_fuente AS id_fuente, COUNT(fuente.id_fuente) AS count
                FROM
                 noticia
                 INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                 INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )";
    $query[] .= $jquery_where;
    $query[] .= "AND noticia.id_tipo_fuente = ".$id_tipo_fuente." ";
    $query[] .= "GROUP BY fuente.id_fuente ";

    $query_entero = join(" ",$query);

    $base->execute_query($query_entero);

    while($row = $base->get_row_assoc())
    {
        $array_fuente_nn[$row['id_fuente']] = $row['count'];
    }


//$array_fuente_ns = array(); // numero de secciones que tiene una fuente

    $query = array();
    $query[] .= "SELECT fuente.id_fuente AS id_fuente, COUNT(DISTINCT seccion.id_seccion) AS secciones
                FROM
                 noticia
                 INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                 INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )";
    $query[] .= $jquery_where;
    $query[] .= "AND noticia.id_tipo_fuente = ".$id_tipo_fuente." ";
    $query[] .= "GROUP BY fuente.id_fuente ";

    $query_entero = join(" ",$query);

    $base->execute_query($query_entero);

    while($row = $base->get_row_assoc())
    {
        $array_fuente_ns[$row['id_fuente']] = $row['secciones'];
    }

//$array_secc_nn = array(); // numero de noticias por seccion

    $query = array();
    $query[] .= "SELECT seccion.id_seccion AS id_seccion, COUNT(noticia.id_noticia) AS noticias
                FROM
                 noticia
                 INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                 INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )";
    $query[] .= $jquery_where;
    $query[] .= "AND noticia.id_tipo_fuente = ".$id_tipo_fuente." ";
    $query[] .= "GROUP BY fuente.id_fuente, seccion.id_seccion ORDER BY fuente.nombre,seccion.nombre";

    $query_entero = join(" ",$query);

    $base->execute_query($query_entero);

    while($row = $base->get_row_assoc())
    {
        $array_secc_nn[$row['id_seccion']] = $row['noticias'];
    }


    //$array_secc_nombre = array(); // identificador de secciones
    //$array_secc_count = array(); // identificador de secciones

    $query = array();
    $query[] .= "SELECT seccion.id_seccion AS id_seccion, seccion.nombre AS nombre
                FROM
                 noticia
                 INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                 INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )";
    $query[] .= $jquery_where;
    $query[] .= "AND noticia.id_tipo_fuente = ".$id_tipo_fuente." ";
    $query[] .= "GROUP BY fuente.id_fuente, seccion.id_seccion ORDER BY fuente.nombre,seccion.nombre";

    $query_entero = join(" ",$query);

    $base->execute_query($query_entero);

    while($row = $base->get_row_assoc())
    {
        $array_secc_nombre[$row['id_seccion']] = $row['nombre'];
    }
    $i = 1;
    foreach($array_secc_nombre as $id => $nombre)
    {
        $array_secc_count[$i] = $id;
        $i++;
    }

    //echo "tf_nn:".$tf_nn." tf_ns:".$tf_ns;
    //echo "<br><br>";
    //print_r($array_fuente_count);
    //echo "<br><br>";
   // print_r($array_secc_count);

} // end function llenaArreglos

//
// Función que regresa el tr correspondiente mediante variables estáticas
//
//

function imprimeTr($id_tipo_fuente)
{
    global $tf_nn, $tf_ns, $array_fuente_count, $array_fuente_nombre, $array_fuente_nn, $array_fuente_ns,
           $array_secc_count, $array_secc_nombre, $array_secc_nn;

    global $f_count,
           $s_count,
           $s_count_f,
           $flag_inicio_tf;

    $new_back = array();
    $array_tf_nombre = array(1=>"Televisión",2=>"Radio",3=>"Periódico",4=>"Revista",5=>"Internet");

    if($flag_inicio_tf)
    {
        $new_back[].= '<tr>';
        $new_back[].= '<td rowspan="'.$tf_ns.'">'.$array_tf_nombre[$id_tipo_fuente].': '.$tf_nn.'</td>';
        $new_back[].= '<td rowspan="'.$array_fuente_ns[$array_fuente_count[$f_count]].'">'.$array_fuente_nombre[$array_fuente_count[$f_count]].': '.$array_fuente_nn[$array_fuente_count[$f_count]].'</td>';
        $new_back[].= '<td>'.$array_secc_nombre[$array_secc_count[$s_count]].': '.$array_secc_nn[$array_secc_count[$s_count]].'</td>';
        $new_back[].= '</tr>';

        $f_count++;
        $s_count++;
        $s_count_f++;

        $output = join(" ",$new_back);
        $flag_inicio_tf = false;
        return $output;
    } // end if

    if(!$flag_inicio)
    {
        if($array_fuente_ns[$array_fuente_count[$f_count-1]] >= $s_count_f)
        {
            $new_back[].= '<tr>';
            $new_back[].= '<td>'.$array_secc_nombre[$array_secc_count[$s_count]].': '.$array_secc_nn[$array_secc_count[$s_count]].'</td>';
            $new_back[].= '</tr>';

            $s_count++;
            $s_count_f++;

            $output = join(" ",$new_back);
            return $output;
        } // end if $array_fuente_ns[$array_fuente_count[$f_count-1]] >= $s_count_f
        else
        {
            $new_back[].= '<tr>';
            $new_back[].= '<td rowspan="'.$array_fuente_ns[$array_fuente_count[$f_count]].'">'.$array_fuente_nombre[$array_fuente_count[$f_count]].': '.$array_fuente_nn[$array_fuente_count[$f_count]].'</td>';
            $new_back[].= '<td>'.$array_secc_nombre[$array_secc_count[$s_count]].': '.$array_secc_nn[$array_secc_count[$s_count]].'</td>';
            $new_back[].= '</tr>';

            $f_count++;
            $s_count++;
            $s_count_f = 2;

            $output = join(" ",$new_back);
            return $output;
        } // end else
    } // end if count > 1

}// end function imprime tr



///
// Seccion 2.2 del reporte
//

$array_sector_nombre = array();
$array_sector_nn = array();
$array_genero_nombre = array();
$array_genero_nn = array();
$array_tautor_nombre = array();
$array_tautor_nn = array();
$array_tendencia_nombre = array();
$array_tendencia_nn = array();

// obtenemos datos de los sectores

    $query = array();
    $query[] .= "SELECT sector.id_sector AS id_sector, sector.nombre AS nombre, COUNT(noticia.id_noticia) AS noticias
                FROM noticia
                INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )";
    $query[] .= $jquery_where;
    $query[] .= "GROUP BY sector.nombre
                ORDER BY sector.nombre";

    $query_entero = join(" ",$query);

    $base->execute_query($query_entero);

    while($row = $base->get_row_assoc())
    {
        $array_sector_nombre[$row['id_sector']] = $row['nombre'];
        $array_sector_nn[$row['id_sector']] = $row['noticias'];
    }

// obtenemos datos de los generos

    $query = array();
    $query[] .= "SELECT genero.id_genero AS id_genero, genero.descripcion AS nombre, COUNT(noticia.id_noticia) AS noticias
                FROM noticia
                INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )";
    $query[] .= $jquery_where;
    $query[] .= "GROUP BY genero.descripcion
                 ORDER BY genero.descripcion";

    $query_entero = join(" ",$query);

    $base->execute_query($query_entero);

    while($row = $base->get_row_assoc())
    {
        $array_genero_nombre[$row['id_genero']] = utf8_encode($row['nombre']);
        $array_genero_nn[$row['id_genero']] = $row['noticias'];
    }

// obtenemos datos de los tipos de autor

    $query = array();
    $query[] .= "SELECT tipo_autor.id_tipo_autor AS id_tipo_autor, tipo_autor.descripcion AS nombre, COUNT(noticia.id_noticia) AS noticias
                FROM noticia
                INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )";
    $query[] .= $jquery_where;
    $query[] .= "GROUP BY tipo_autor.descripcion
                ORDER BY tipo_autor.descripcion";

    $query_entero = join(" ",$query);

    $base->execute_query($query_entero);

    while($row = $base->get_row_assoc())
    {
        $array_tautor_nombre[$row['id_tipo_autor']] = $row['nombre'];
        $array_tautor_nn[$row['id_tipo_autor']] = $row['noticias'];
    }

// obtenemos datos de las tendencias

    $query = array();
    $query[] .= "SELECT tendencia.id_tendencia AS id_tendencia, tendencia.descripcion AS nombre, COUNT(noticia.id_noticia) AS noticias
                FROM noticia
                INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
                INNER JOIN tendencia ON (asigna.id_tendencia=tendencia.id_tendencia)";
    $query[] .= $jquery_where;
    $query[] .= "GROUP BY tendencia.descripcion
                ORDER BY tendencia.descripcion";

    $query_entero = join(" ",$query);

    $base->execute_query($query_entero);

    while($row = $base->get_row_assoc())
    {
        $array_tendencia_nombre[$row['id_tendencia']] = $row['nombre'];
        $array_tendencia_nn[$row['id_tendencia']] = $row['noticias'];
    }


// 3.1  noticias por tema

$array_temas_nombre = array();
$array_temas_nn = array();

// obtenemos datos de los temas

    $query = array();
    $query[] .= "SELECT tema.id_tema AS id_tema, tema.nombre AS nombre, COUNT(noticia.id_noticia) AS noticias
                FROM noticia
                INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia )
                INNER JOIN tema ON (asigna.id_tema = tema.id_tema)";
    $query[] .= $jquery_where;
    $query[] .= "GROUP BY tema.id_tema
                ORDER BY noticias DESC";

    $query_entero = join(" ",$query);

    $base->execute_query($query_entero);

    while($row = $base->get_row_assoc())
    {
        $array_temas_nombre[$row['id_tema']] = $row['nombre'];
        $array_temas_nn[$row['id_tema']] = $row['noticias'];
    }

// funcion para limitar palabras

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


// aqui comenzamos  a armar el html

//============================================================+
// File name   : pdf_not_cli.php
//
// Description : Genera el Reporte de las Noticias que tiene
//               un determinado cliente segun determinados
//               parámetros
//
// Author: Josué Morado
//         
//
//============================================================+

// Imprimimos HTML
// parametros del reporte



setlocale(LC_TIME, 'Spanish');
$htmlcode = '<p>
<span align="right" style="font-weight: bold; font-size:medium;">Reporte generado el '.strftime('%d de %B del %Y',strtotime( date ( "d-m-Y" , time ()))).'</span><br>
<span style="font-weight: bold; font-size:large;">I. Par&aacute;metros del Reporte:</span><br><br>
<p>'.$query.'</p>
<table border="1" cellspacing="2" cellpadding="2">
	<tr style="background-color:#fddde0;">
		<th align="center" colspan="1">Monitoreo efectuado del <strong>'.strftime('%d de %B del %Y',strtotime($fecha1)).'</strong> al <strong>'.strftime('%d de %B del %Y',strtotime($fecha2)).'</strong></th>
	</tr>
	<tr>
		<td>
			<table cellspacing ="0">
				<tr style="background-color:#eaf5f6;">
					<th style="font-weight:bold;" align="center" colspan="1">Tema:</th>
					<th style="font-weight:bold;" align="center" colspan="1">Tipo de Fuente</th>
					<th style="font-weight:bold;" align="center" colspan="1">Fuente</th>
					<th style="font-weight:bold;" align="center" colspan="1">Sección</th>
				</tr>
				<tr>
					<td align="center">'.$ntema['name'].'</td>
					<td align="center">'.$ntipofuente['name'].'</td>
					<td align="center">'.$nfuente['name'].'</td>
					<td align="center">'.$nseccion['name'].'</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table cellspacing ="0">
				<tr style="background-color:#eaf5f6;">
					<th style="font-weight:bold;" align="center" colspan="1">Sector</th>
					<th style="font-weight:bold;" align="center" colspan="1">Género</th>
					<th style="font-weight:bold;" align="center" colspan="1">Tipo de Autor</th>
					<th style="font-weight:bold;" align="center" colspan="1">Tendencia</th>
				</tr>
				<tr>
					<td align="center">'.$nsector['name'].'</td>
					<td align="center">'.$ngenero['name'].'</td>
					<td align="center">'.$ntipoautor['name'].'</td>
					<td align="center">'.$ntendencia['name'] .'</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br><br>

</p>';

 echo utf8_decode($htmlcode);


//estadisticas  de tipofuente/fuente/seccion

$htmlcode = '<p>
<span style="font-weight: bold; font-size:large;">II. Estadísticas:</span><br><br>
<span style="font-size:medium; text-decoration:underline;">2.1 Tipo de Fuente / Fuente / Sección: (Número de Noticias)</span><br><br>
<table border="1" cellspacing="0" cellpadding="3">
 <thead>
  <tr cellspacing="0" border="0" style="background-color:#eaf5f6; font-weight:bold;">
    <td>Tipo de Fuente</td>
    <td>Fuente</td>
    <td>Sección</td>
  </tr>
 </thead>
   ';

for($i=1;$i<=5;$i++)
{
    $s_count_f = 1;
    
    llenaArreglos($i);

    for($j=1;$j<=$tf_ns;$j++)
    {
        $htmlcode.= imprimeTr($i);
    }
    $flag_inicio_tf = true;
}

$htmlcode.= '
          <tr style="background-color:#fddde0; font-weight:bold;">
                <td colspan="3" align="center">TOTAL DE NOTICIAS: '.$_2_1_totales.'</td>
          </tr>
        </table>
        <br><br>

        </p>';


 echo utf8_decode($htmlcode);


//estadisticas  de otros atributos

$htmlcode = '<p>
<span style="font-size:medium; text-decoration:underline;">2.2 Otros Atributos: (Número de Noticias)</span><br><br>
	<table cellspacing ="0" cellpadding ="2" border="1">
		<thead>
				<tr style="background-color:#eaf5f6;">
					<td style="font-weight:bold;" align="center" colspan="1">Sector:</td>
					<td style="font-weight:bold;" align="center" colspan="1">Género</td>
					<td style="font-weight:bold;" align="center" colspan="1">Tipo de Autor:</td>
					<td style="font-weight:bold;" align="center" colspan="1">Tendencia:</td>
				</tr>
		</thead>
				<tr>
					<td align="center">
						<table border="0" cellspacing ="0" cellpadding ="2">

';
                                        foreach($array_sector_nombre as $value => $label)
                                        {
                                            $htmlcode.='<tr>';
                                            $htmlcode.='<td>'.$label.': '.$array_sector_nn[$value].'</td>';
                                            $htmlcode.='</tr>';
                                        }

                       $htmlcode.=    '
						</table>
					</td>
					<td align="center">
						<table border="0" cellspacing ="0" cellpadding ="2">';
							
					foreach($array_genero_nombre as $value => $label)
                                        {
                                            $htmlcode.='<tr>';
                                            $htmlcode.='<td>'.$label.': '.$array_genero_nn[$value].'</td>';
                                            $htmlcode.='</tr>';
                                        }
                                                
                             $htmlcode.=       '
                                                </table>
					</td>
					<td align="center">
						<table border="0" cellspacing ="0" cellpadding ="2">';

                                        foreach($array_tautor_nombre as $value => $label)
                                        {
                                            $htmlcode.='<tr>';
                                            $htmlcode.='<td>'.$label.': '.$array_tautor_nn[$value].'</td>';
                                            $htmlcode.='</tr>';
                                        }
				$htmlcode.=	'
				       </table>
					</td>
					<td align="center">
						<table border="0" cellspacing ="0" cellpadding ="2">';

					foreach($array_tendencia_nombre as $value => $label)
                                        {
                                            $htmlcode.='<tr>';
                                            $htmlcode.='<td>'.$label.': '.$array_tendencia_nn[$value].'</td>';
                                            $htmlcode.='</tr>';
                                        }

				$htmlcode.= '</table>
					</td>
				</tr>
	</table>
<br><br>
</p>';
echo utf8_decode($htmlcode);

// por tema

$htmlcode = '<p>
<span style="font-weight: bold; font-size:large;">III. Noticias:</span><br><br><br>
<span style="font-size:medium; text-decoration:underline;">3.1 Por Tema: (Número de Noticias)</span><br><br>
<table border="1" cellspacing="0" cellpadding="1">
	<thead>
	<tr style="background-color:#eaf5f6;">
		<td style="font-weight:bold;" align="center" colspan="1">Tema</td>
		<td style="font-weight:bold;" align="center" colspan="1">Noticias</td>
	</tr>
	</thead>';

        foreach($array_temas_nombre as $value => $label)
        {
            $htmlcode.='<tr>';
            $htmlcode.='<td>'.$label.'</td>';
            $htmlcode.='<td>'.$array_temas_nn[$value].'</td>';
            $htmlcode.='</tr>';
        }

$htmlcode.= 	'
            </table>
            <br><br>

            </p>';

 echo utf8_decode($htmlcode);


// Detalle Noticias
$htmlcode = '<p><span style="font-size:medium; text-decoration:underline;">3.2 Detalle Noticias:</span><br><br>';

        foreach($array_temas_nombre as $value => $label)
        {
            $htmlcode.='
            <table border="1" cellspacing="2" cellpadding="2">
              <thead>
			<tr style="background-color:#fddde0; font-weight:bold;">
				<td align="center">TEMA: '.$label.'</td>
			</tr>
              </thead>
			<tr align="center">
				<td>
				<table border="1" cellspacing="0" cellpadding="1">
					<tr align="center" style="background-color:#eaf5f6; font-weight:bold;">
						<td width="35">Medio</td>
						<td width="111">Fuente</td>
						<td width="30">Id Noticia</td>
						<td width="150">Encabezado</td>												
						<td width="150">Síntesis</td>
						<td width="30">Tendencia</td>
						<td width="30">Costo</td>
						<td width="75">Fecha</td>
					</tr>
                
                ';
            $query = array();
            $query[] .= "SELECT tipo_fuente.descripcion AS tf, noticia.id_tipo_fuente AS tipo, noticia.id_noticia AS id_noticia, noticia.fecha AS fecha, noticia.encabezado AS encabezado, noticia.sintesis AS sintesis, tendencia.descripcion AS nombre_tendencia, fuente.nombre AS fuente
                            FROM noticia
                            INNER JOIN asigna ON (noticia.id_noticia = asigna.id_noticia)
                            INNER JOIN fuente ON (fuente.id_fuente = noticia.id_fuente)
							INNER JOIN tendencia ON (tendencia.id_tendencia = noticia.id_tendencia_monitorista)
                            INNER JOIN tipo_fuente ON (tipo_fuente.id_tipo_fuente = noticia.id_tipo_fuente)";
            $query[] .= $jquery_where;
            $query[] .= "AND asigna.id_tema = ".$value;
            $query[] .= "ORDER BY noticia.fecha DESC, noticia.id_noticia DESC";
            $query_entero = join(" ",$query);
			// que hay en el SQL
			//$htmlcode.='<tr><td>'.$query_entero.'</td></tr>';
            $base->execute_query($query_entero);

            while($row = $base->get_row_assoc()){
                $htmlcode.= '<tr align="center" style="font-size:small;">
                                <td width="35">'.utf8_encode($row['tf']).'</td>
								<td width="111">'.$row['fuente'].'</td>
								<td width="30"><a href="http://sistema.opemedios.com.mx/ver_noticia_selector_ns.php?id_noticia='.$row['id_noticia'].'&id_tipo_fuente='.$row['tipo'].'">'.$row['id_noticia'].'</a></td>
								<td width="150">'.$row['encabezado'].'</td>
                                <td width="150">'.WordLimiter($row['sintesis'],100).'</td>                                
								<td width="30">'.$row['nombre_tendencia'].'</td>';
				if ($row['tipo'] == 1) {
					$SQL = 'Select costo from noticia_tel where id_noticia="'.$row['id_noticia'].'"';
					$base2->execute_query($SQL);
					$row2 = $base2->get_row_assoc();
					$htmlcode.= '<td width="30">'.number_format($row2['costo']).'</td>';
				}
				if ($row['tipo'] == 2) {
					$SQL = 'Select costo from noticia_rad where id_noticia="'.$row['id_noticia'].'"';
					$base2->execute_query($SQL);
					$row2 = $base2->get_row_assoc();
					$htmlcode.= '<td width="30">'.number_format($row2['costo']).'</td>';
				}
				if ($row['tipo'] == 3) {
					$SQL = 'Select costo from noticia_per where id_noticia="'.$row['id_noticia'].'"';
					$base2->execute_query($SQL);
					$row2 = $base2->get_row_assoc();
					$htmlcode.= '<td width="30">'.number_format($row2['costo']).'</td>';
				}
				if ($row['tipo'] == 4) {
					$SQL = 'Select costo from noticia_rev where id_noticia="'.$row['id_noticia'].'"';
					$base2->execute_query($SQL);
					$row2 = $base2->get_row_assoc();
					$htmlcode.= '<td width="30">'.number_format($row2['costo']).'</td>';
				}
				if ($row['tipo'] == 5) {
					$SQL = 'Select costo from noticia_int where id_noticia="'.$row['id_noticia'].'"';
					$base2->execute_query($SQL);
					$row2 = $base2->get_row_assoc();
					$htmlcode.= '<td width="30">'.number_format($row2['costo']).'</td>';
				}
				$htmlcode.= '<td width="75">'.$row['fecha'].'</td>
                            </tr>';
            }
            
            $htmlcode.= '
                </table>
			</td>
		</tr>
</table>

';
            
        } // end foreach tema

$htmlcode.= '</table>

<br><br>
</p>';


 echo utf8_decode($htmlcode);

$base->free_result();
$base->close();
$base2->free_result();
$base2->close();

// -----------------------------------------------------------------------------

//============================================================+
// END OF FILE
//============================================================+

?>