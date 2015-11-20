<?php
//llamamos el codigo de sesion para usuario nivel 3 = monitorista
include("phpdelegates/rest_access_3.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");
include 'charts/php-ofc-library/open-flash-chart.php';

// llamamos las clases a utilizar
include("phpclasses/Usuario.php");

//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");

//creamos un DAO para obtener los datos de la fuente dada mediante el metodo GET
//recibe como parametro el resultado de la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();

//obtenemos la informacion del usuario y creamos el objeto
$base->execute_query("SELECT * FROM usuario WHERE username = '".$session_usr."'");// session_usr se encuentra en los inclides de sesion
$current_user = new Usuario($base->get_row_assoc());

function getFecha_larga($fecha)
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

        $dia = substr($fecha,8,2);
        $mes = date("n",mktime(00,00,00,substr($fecha,5,2),01,2000));
        $año = substr($fecha,0,4);

        return $dia." de ".$arreglo_meses[$mes].", ".$año;
    }
	
	

if($_POST['type']=='cliente'){

$base->execute_query("SELECT id_tema, nombre FROM tema where id_empresa = ".$_POST['row_id']);
$arreglo_temas = array();
while($tema = $base->get_row_assoc())
{
    $arreglo_temas[$tema['id_tema']] = $tema["nombre"];
}
$query = "SELECT * FROM empresa WHERE id_empresa = ".$_POST['row_id'];
$base->execute_query($query);
$empresa = $base->get_row_assoc();
$type_usr = 'Cliente';
}
if($_POST['type']=='monitorista'){
$query = "SELECT Usr.id_usuario, CONCAT(Usr.nombre,' ',Usr.apellidos) AS nombre,
            Usr.id_usuario
            FROM usuario Usr WHERE id_usuario = ".$_POST['row_id'];
$base->execute_query($query);
$usuario = $base->get_row_assoc();
$type_usr = 'Monitorista';
}

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


///////////////////////////////////////////////////////////////////
///////////////////////////GRAFICAS/////////////////////////////////
////////////////////////////////////////////////////////////////////


//grafica 1: Pastel: Numero de noticias por Tema

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
	

$title = new title( 'Numero de Noticias por Tema. <b>Del '.getFecha_larga($fecha1).' al '.getFecha_larga($fecha2).'</b>');
$title->set_style('color: #000000; font-size: 15px; font-family: Tahoma,Geneva');
$d = array();
foreach($array_temas_nn as $id => $nn)
{
	$d[]= new pie_value(intval($nn), $array_temas_nombre[$id].': '.$nn);
}


$pie = new pie();
$pie->alpha(0.6)
    ->add_animation( new pie_fade(10) )
    ->add_animation( new pie_bounce(10) )
    //->start_angle( 270 )
    ->start_angle( 0 )
    ->tooltip( '#percent#' )
	->gradient_fill()
    ->colours(array("#305aa6","63cd4e","#c10e0e","#71CAF2","#D4FA00s","#CE942E","#3A0108","#09635C"));

$pie->set_values( $d );

$chart1 = new open_flash_chart();
$chart1->set_title( $title );
$chart1->add_element( $pie );
$chart1->set_bg_colour('#f3f6ff');

//////////////////////////////////////////////////////////////

//grafica 2: Barra Horizontal: Numero de noticias por Tipo de Fuente

// obtenemos informacion

$tf_nn = 0; // numero de noticias que tiene un tipo de fuente
$_2_1_totales = 0; // total de noticias

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
	
	
	function num_not_tf($id_tipo_fuente)
	{
		global $base, $jquery_where;
		
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

    $res = $row['COUNT(*)'];
	
	
	
	return intval($res);
	
	} // end function
	
	// grafica
	
	//$x_labels = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

$title = new title( 'Numero de noticias por Tipo de Fuente.  <b>Del '.getFecha_larga($fecha1).' al '.getFecha_larga($fecha2).'</b>' );

$hbar = new hbar( '#86BBEF' );
$hbar->set_tooltip( 'Noticias: #val#' );
$hbar->set_values( array(num_not_tf(1),num_not_tf(2),num_not_tf(3),num_not_tf(4),num_not_tf(5)) );

$chart2 = new open_flash_chart();
$chart2->set_title( $title );
$chart2->add_element( $hbar );

$x = new x_axis();
$x->set_offset( true );
$x->set_steps(5);
// now set up the x axis labels
$labels = new x_axis_labels();
// we have points at every X location, so label each X location
$labels->set_steps(5);
$labels->visible_steps(5);
// finally attach the label definition to the x axis
$x->set_labels($labels);
//$x->set_range( 0, 10 );
//$x->set_labels_from_array( $x_labels );
$chart2->set_x_axis( $x );

$y = new y_axis();
$y->set_offset( true );
$y->set_labels( array( "Internet: ".num_not_tf(5),"Revista: ".num_not_tf(4),"Periodico: ".num_not_tf(3),"Radio: ".num_not_tf(2),"Televisión: ".num_not_tf(1)) );
$chart2->add_y_axis( $y );

$tooltip = new tooltip();
//
// LOOK:
//
$tooltip->set_hover();
//
//
//
$tooltip->set_stroke( 1 );
$tooltip->set_colour( "#000000" );
$tooltip->set_background_colour( "#ffffff" ); 
$chart2->set_tooltip( $tooltip );

///////////////////////////////////////////////////////////////////
///////             GRAFICAS 3,4,5,6,7             ////////////////////////////


$f_totales = 0; // total de noticias
$array_fuente_count = array(); // referencia a el id de una fuente
$array_fuente_nombre = array(); // nombre de la fuente
$array_fuente_nn = array(); // numero de noticias que tiene una fuente

//arreglo de tamaño de las graficas dependiendo del numero de fuentes
$array_height = array();


function llenaArreglos($id_tipo_fuente)
{
	global $f_totales, 
		   $array_fuente_count, 
		   $array_fuente_nombre, 
		   $array_fuente_nn, 
		   $array_height,
		   $jquery_where, 
		   $base;
		   
	//limpiamos variables
	$array_fuente_nombre = array();
	$array_fuente_nn = array();
	
	//$f_totales
	$query = array();

	$query[] .= "SELECT COUNT(*)
				FROM
				 noticia
				 INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
				 INNER JOIN asigna ON (noticia.id_noticia=asigna.id_noticia)";
	$query[] .= $jquery_where;
	$query[] .= "AND noticia.id_tipo_fuente = ".$id_tipo_fuente;

	$query_entero = join(" ",$query);

	$base->execute_query($query_entero);

	$row = $base->get_row_assoc();

	$f_totales = intval($row['COUNT(*)']);
	
	//
	
	
	
	

	$query = array();
    $query[] .= "SELECT fuente.id_fuente AS id_fuente, fuente.nombre AS nombre, COUNT(fuente.id_fuente) AS count
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
	
	$array_height[$id_tipo_fuente] = intval($base->num_rows() * 20 + 100);

	while($row = $base->get_row_assoc())
	{
		$array_fuente_nombre[].= $row['nombre'].": ".$row['count'];
		$array_fuente_nn[].= intval($row['count']);
	}
	
	//die(print_r($array_fuente_nombre)."<br><br>".print_r($array_fuente_nn));
	

		   
}// end function llena arreglos





//////////////////////   GRAFICA 3: TELEVISION

llenaArreglos(1); 
 
$title = new title( 'Noticias de Televisíon (Total: '.$f_totales.').  <b>Del '.getFecha_larga($fecha1).' al '.getFecha_larga($fecha2).'</b>' );

$hbar = new hbar( '#A5D915' );
$hbar->set_tooltip( 'Noticias: #val#' );
$hbar->set_values($array_fuente_nn);


$chart3 = new open_flash_chart();
$chart3->set_title( $title );
$chart3->add_element( $hbar );

$x = new x_axis();
$x->set_offset( true );
$x->set_steps(5);
// now set up the x axis labels
$labels = new x_axis_labels();
// we have points at every X location, so label each X location
$labels->set_steps(2);
$labels->visible_steps(5);
// finally attach the label definition to the x axis
$x->set_labels($labels);
//$x->set_range( 0, 10 );
//$x->set_labels_from_array( $x_labels );
$chart3->set_x_axis( $x );

$y = new y_axis();
$y->set_offset( true );
$y->set_labels(array_reverse($array_fuente_nombre));
$chart3->add_y_axis( $y );

$tooltip = new tooltip();
//
// LOOK:
//
$tooltip->set_hover();
//
//
$tooltip->set_stroke( 1 );
$tooltip->set_colour( "#000000" );
$tooltip->set_background_colour( "#ffffff" ); 
$chart3->set_tooltip( $tooltip );



//////////////////////   GRAFICA 4: RADIO

llenaArreglos(2); 
 
$title = new title( 'Noticias de Radio (Total: '.$f_totales.').  <b>Del '.getFecha_larga($fecha1).' al '.getFecha_larga($fecha2).'</b>' );

$hbar = new hbar( '#E48D11' );
$hbar->set_tooltip( 'Noticias: #val#' );
$hbar->set_values($array_fuente_nn);


$chart4 = new open_flash_chart();
$chart4->set_title( $title );
$chart4->add_element( $hbar );

$x = new x_axis();
$x->set_offset( true );
$x->set_steps(5);
// now set up the x axis labels
$labels = new x_axis_labels();
// we have points at every X location, so label each X location
$labels->set_steps(2);
$labels->visible_steps(5);
// finally attach the label definition to the x axis
$x->set_labels($labels);
//$x->set_range( 0, 10 );
//$x->set_labels_from_array( $x_labels );
$chart4->set_x_axis( $x );

$y = new y_axis();
$y->set_offset( true );
$y->set_labels(array_reverse($array_fuente_nombre));
$chart4->add_y_axis( $y );

$tooltip = new tooltip();
//
// LOOK:
//
$tooltip->set_hover();
//
//
$tooltip->set_stroke( 1 );
$tooltip->set_colour( "#000000" );
$tooltip->set_background_colour( "#ffffff" ); 
$chart4->set_tooltip( $tooltip );





//////////////////////   GRAFICA 5: PERIODICO

llenaArreglos(3); 
 
$title = new title( 'Noticias de Periodico (Total: '.$f_totales.').  <b>Del '.getFecha_larga($fecha1).' al '.getFecha_larga($fecha2).'</b>' );

$hbar = new hbar( '#A1AAC1' );
$hbar->set_tooltip( 'Noticias: #val#' );
$hbar->set_values($array_fuente_nn);


$chart5 = new open_flash_chart();
$chart5->set_title( $title );
$chart5->add_element( $hbar );

$x = new x_axis();
$x->set_offset( true );
$x->set_steps(5);
// now set up the x axis labels
$labels = new x_axis_labels();
// we have points at every X location, so label each X location
$labels->set_steps(2);
$labels->visible_steps(5);
// finally attach the label definition to the x axis
$x->set_labels($labels);
//$x->set_range( 0, 10 );
//$x->set_labels_from_array( $x_labels );
$chart5->set_x_axis( $x );

$y = new y_axis();
$y->set_offset( true );
$y->set_labels(array_reverse($array_fuente_nombre));
$chart5->add_y_axis( $y );

$tooltip = new tooltip();
//
// LOOK:
//
$tooltip->set_hover();
//
//
$tooltip->set_stroke( 1 );
$tooltip->set_colour( "#000000" );
$tooltip->set_background_colour( "#ffffff" );
$chart5->set_tooltip( $tooltip );


//////////////////////   GRAFICA 6: REVISTA

llenaArreglos(4); 
 
$title = new title( 'Noticias de Revista (Total: '.$f_totales.').  <b>Del '.getFecha_larga($fecha1).' al '.getFecha_larga($fecha2).'</b>' );

$hbar = new hbar( '#8A3538' );
$hbar->set_tooltip( 'Noticias: #val#' );
$hbar->set_values($array_fuente_nn);


$chart6 = new open_flash_chart();
$chart6->set_title( $title );
$chart6->add_element( $hbar );

$x = new x_axis();
$x->set_offset( true );
$x->set_steps(5);
// now set up the x axis labels
$labels = new x_axis_labels();
// we have points at every X location, so label each X location
$labels->set_steps(2);
$labels->visible_steps(5);
// finally attach the label definition to the x axis
$x->set_labels($labels);
//$x->set_range( 0, 10 );
//$x->set_labels_from_array( $x_labels );
$chart6->set_x_axis( $x );

$y = new y_axis();
$y->set_offset( true );
$y->set_labels(array_reverse($array_fuente_nombre));
$chart6->add_y_axis( $y );

$tooltip = new tooltip();
//
// LOOK:
//
$tooltip->set_hover();
//
//
$tooltip->set_stroke( 1 );
$tooltip->set_colour( "#000000" );
$tooltip->set_background_colour( "#ffffff" ); 
$chart6->set_tooltip( $tooltip );


//////////////////////   GRAFICA 7: INTERNET

llenaArreglos(5); 
 
$title = new title( 'Noticias de Internet (Total: '.$f_totales.').  <b>Del '.getFecha_larga($fecha1).' al '.getFecha_larga($fecha2).'</b>' );

$hbar = new hbar( '#99BD4A' );
$hbar->set_tooltip( 'Noticias: #val#' );
$hbar->set_values($array_fuente_nn);


$chart7 = new open_flash_chart();
$chart7->set_title( $title );
$chart7->add_element( $hbar );

$x = new x_axis();
$x->set_offset( true );
$x->set_steps(5);
// now set up the x axis labels
$labels = new x_axis_labels();
// we have points at every X location, so label each X location
$labels->set_steps(2);
$labels->visible_steps(5);
// finally attach the label definition to the x axis
$x->set_labels($labels);
//$x->set_range( 0, 10 );
//$x->set_labels_from_array( $x_labels );
$chart7->set_x_axis( $x );

$y = new y_axis();
$y->set_offset( true );
$y->set_labels(array_reverse($array_fuente_nombre));
$chart7->add_y_axis( $y );

$tooltip = new tooltip();
//
// LOOK:
//
$tooltip->set_hover();
//
//
$tooltip->set_stroke( 1 );
$tooltip->set_colour( "#000000" );
$tooltip->set_background_colour( "#ffffff" ); 
$chart7->set_tooltip( $tooltip );


















//cerramos conexion
$base->free_result();
$base->close();
//echo 'this'.$_POST['row_id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.suaorg/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Gráficas de Analisis</title>
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
        
<script type="text/javascript" src="charts/js/swfobject.js"></script>
<script type="text/javascript" src="charts/js/json/json2.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<style type="text/css">
	.resize{width: 950px; height: 350px; background:#000000; padding:4px}
	#resize2{width: 950px; height: 300px; background:#000000; padding:4px}
	#resize3{width: 950px; height: <?php echo $array_height[1];?>px; background:#000000; padding:4px}
	#resize4{width: 950px; height: <?php echo $array_height[2];?>px; background:#000000; padding:4px}
	#resize5{width: 950px; height: <?php echo $array_height[3];?>px; background:#000000; padding:4px}
	#resize6{width: 950px; height: <?php echo $array_height[4];?>px; background:#000000; padding:4px}
	#resize7{width: 950px; height: <?php echo $array_height[5];?>px; background:#000000; padding:4px}
</style>
  
<script type="text/javascript">
swfobject.embedSWF(
  "open-flash-chart.swf", "chart1", "100%", "100%","9.0.0", "expressInstall.swf",
  {"get-data":"get_data_1"});
  
  swfobject.embedSWF(
  "open-flash-chart.swf", "chart2", "100%", "100%","9.0.0","expressInstall.swf",
  {"get-data":"get_data_2"});
  
  swfobject.embedSWF(
  "open-flash-chart.swf", "chart3", "100%", "100%","9.0.0","expressInstall.swf",
  {"get-data":"get_data_3"});
  
  swfobject.embedSWF(
  "open-flash-chart.swf", "chart4", "100%", "100%","9.0.0","expressInstall.swf",
  {"get-data":"get_data_4"});
  
  swfobject.embedSWF(
  "open-flash-chart.swf", "chart5", "100%", "100%","9.0.0","expressInstall.swf",
  {"get-data":"get_data_5"});
  
  swfobject.embedSWF(
  "open-flash-chart.swf", "chart6", "100%", "100%","9.0.0","expressInstall.swf",
  {"get-data":"get_data_6"});
  
  swfobject.embedSWF(
  "open-flash-chart.swf", "chart7", "100%", "100%","9.0.0","expressInstall.swf",
  {"get-data":"get_data_7"});
  
  function ofc_ready()
{
    //alert('ofc_ready');
}

$(document).ready(function() {
    $("#resize1").resizable();
	$("#resize2").resizable();
	$("#resize3").resizable();
	$("#resize4").resizable();
	$("#resize5").resizable();
	$("#resize6").resizable();
	$("#resize7").resizable();
  });

function get_data_1()
{
	// alert( 'reading data 1' );
	return JSON.stringify(data1);
}
 
function get_data_2()
{
	// alert( 'reading data 2' );
	// alert(JSON.stringify(data_2));
	return JSON.stringify(data2);
}

function get_data_3()
{
	// alert( 'reading data 2' );
	// alert(JSON.stringify(data_2));
	return JSON.stringify(data3);
}
function get_data_4()
{
	return JSON.stringify(data4);
}
function get_data_5()
{
	return JSON.stringify(data5);
}
function get_data_6()
{
	return JSON.stringify(data6);
}
function get_data_7()
{
	return JSON.stringify(data7);
}
 
 
var data1 = <?php echo $chart1->toPrettyString(); ?>;
 
//
// to help debug:
//
//alert(JSON.stringify(data1));
 
var data2 = <?php echo $chart2->toPrettyString(); ?>;
var data3 = <?php echo $chart3->toPrettyString(); ?>;
var data4 = <?php echo $chart4->toPrettyString(); ?>;
var data5 = <?php echo $chart5->toPrettyString(); ?>;
var data6 = <?php echo $chart6->toPrettyString(); ?>;
var data7 = <?php echo $chart7->toPrettyString(); ?>;

  
</script>

<script type="text/javascript">

OFC = {};

OFC.jquery = {
    name: "jQuery",
    version: function(src) { return $('#'+ src)[0].get_version() },
    rasterize: function (src, dst) { $('#'+ dst).replaceWith(OFC.jquery.image(src)) },
    image: function(src) { return "<img src='data:image/png;base64," + $('#'+src)[0].get_img_binary() + "' />"},
    popup: function(src) {
        var img_win = window.open('', 'Charts: Export as Image')
        with(img_win.document) {
            write('<html><head><title>Charts: Export as Image<\/title><\/head><body>' + OFC.jquery.image(src) + '<\/body><\/html>') }
		// stop the 'loading...' message
		img_win.document.close();
     }
}

// Using an object as namespaces is JS Best Practice. I like the Control.XXX style.
//if (!Control) {var Control = {}}
//if (typeof(Control == "undefined")) {var Control = {}}
if (typeof(Control == "undefined")) {var Control = {OFC: OFC.jquery}}


// By default, right-clicking on OFC and choosing "save image locally" calls this function.
// You are free to change the code in OFC and call my wrapper (Control.OFC.your_favorite_save_method)
// function save_image() { alert(1); Control.OFC.popup('my_chart') }
function save_image() { alert(1); OFC.jquery.popup('my_chart') }
function moo() { alert(99); };
</script>


        
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
                          <td width="533" height="25" class="label2">Gráficas --&gt; Clientes --&gt; <span class="label4">Gráficas de Análisis</span></td>
                          <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                  </tr>
                    </table>
<table width="1000" border="0">
                        
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3"><hr /></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1">Número de Noticias por Tema:</td>
        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1" align="center">
                          	<div id="resize1" class="resize">
                            	<div id="chart1"></div>
                            </div><br>
                            <INPUT TYPE=BUTTON OnClick="OFC.jquery.popup('chart1')" VALUE="Guardar Imagen">
                          </td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1" align="center"><hr /></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1" >Numero de Noticias por Tipo de Fuente</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1" align="center" >
                          <div id="resize2">
                            	<div id="chart2"></div>
                            </div><br>
                            <INPUT TYPE=BUTTON OnClick="OFC.jquery.popup('chart2')" VALUE="Guardar Imagen">
                          </td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1" align="center"><hr /></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1"><p class="label4">Noticias por Fuente</p>
                          <p>(Televisión)</p></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1" align="center">
                          <div class="resize" id="resize3">
                            	<div id="chart3"></div>
                            </div><br>
                            <INPUT TYPE=BUTTON OnClick="OFC.jquery.popup('chart3')" VALUE="Guardar Imagen">
                          </td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1" align="center">&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1">(Radio)</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1" align="center">
                          <div class="resize" id="resize4">
                            	<div id="chart4"></div>
                            </div><br>
                            <INPUT TYPE=BUTTON OnClick="OFC.jquery.popup('chart4')" VALUE="Guardar Imagen">
                          </td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1" align="center">&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1">(Periodico)</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1" align="center">
                          <div class="resize" id="resize5">
                            	<div id="chart5"></div>
                            </div><br>
                            <INPUT TYPE=BUTTON OnClick="OFC.jquery.popup('chart5')" VALUE="Guardar Imagen">
                          </td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1" align="center">&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1">(Revista)</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1" align="center">
                          <div class="resize" id="resize6">
                            	<div id="chart6"></div>
                            </div><br>
                            <INPUT TYPE=BUTTON OnClick="OFC.jquery.popup('chart6')" VALUE="Guardar Imagen">
                          </td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1" align="center">&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1">(Internet)</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1" align="center">
                          <div class="resize" id="resize7">
                            	<div id="chart7"></div>
                            </div><br>
                            <INPUT TYPE=BUTTON OnClick="OFC.jquery.popup('chart7')" VALUE="Guardar Imagen">
                          </td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1" align="center">&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1" align="center">&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1" align="center">&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1" align="center">&nbsp;</td>
                        </tr>
                </table></td>
            </tr>
        </table>
        <?php include("includes/init_menu_principal.php");?>
    </body>
</html>