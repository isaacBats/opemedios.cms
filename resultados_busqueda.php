<?php require_once('Connections/bitacora.php'); ?>
<?php

//llamamos el codigo de sesion para usuario nivel 3 = monitorista
include("phpdelegates/rest_access_3.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

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

if(isset($_GET['busqueda'])&& $_GET['busqueda'] == true)
{
    $string = "";
    $matchSELECT = "";
    $matchWHERE = "";
    $order="";

    if($_GET['txt_buscar']!= "")// si esta lleno
    {
        if($_GET['tipo_busqueda'] == 2)// Palabras Clave
        {
            $string .= "'";
            $string_evita = "";

            $string .= sprintf("%s",GetSQLValueString($_GET['txt_buscar'], "textolimpio"));

            if($_GET['txt_evitar']!="")//  contiene algun valor
            {

                $split = split(" ",$_GET['txt_evitar']);
                foreach ($split as $array => $value)
                {
                    $string_evita .= ' -'.GetSQLValueString($value, "textolimpio").' ';
                }
                $string_evita = substr($string_evita,0,(strLen($string_evita)-1)); // borra el ultimo espacio

            }

           $string .= $string_evita;
           $string .= "'";

           $matchSELECT = sprintf(",((1.5 * (MATCH(noticia.encabezado) AGAINST(%s IN BOOLEAN MODE))) +
                                          (0.9 * (MATCH(noticia.sintesis) AGAINST(%s IN BOOLEAN MODE))) +
                                          (0.5 * (MATCH(noticia.autor) AGAINST(%s IN BOOLEAN MODE)))) AS Relevance",
                $string,
                $string,
                $string);

            $matchWHERE = sprintf("MATCH(noticia.`encabezado`,noticia.`sintesis`,noticia.`autor`)AGAINST(%s IN BOOLEAN MODE) AND",$string);

            $order = "ORDER BY Relevance DESC, Clave DESC";

        }

        if($_GET['tipo_busqueda'] == 1)//frase completa
        {
            $string = sprintf("'%s'",GetSQLValueString($_GET['txt_buscar'], "frasecompleta"));//el string lleva comilla doble dentro de la comilla simple

            $matchSELECT = sprintf(",((1.5 * (MATCH(noticia.encabezado) AGAINST(%s IN BOOLEAN MODE))) +
                                   (0.9 * (MATCH(noticia.sintesis) AGAINST(%s IN BOOLEAN MODE))) +
                                   (0.5 * (MATCH(noticia.autor) AGAINST(%s IN BOOLEAN MODE)))) AS Relevance",
                $string,
                $string,
                $string);

            $matchWHERE = sprintf("MATCH(noticia.`encabezado`,noticia.`sintesis`,noticia.`autor`)AGAINST(%s IN BOOLEAN MODE) AND",$string);

            $order = "ORDER BY Relevance DESC, Clave DESC";

        }

    } // end if si esta lleno
    else
    {
        $order = "ORDER BY Clave DESC";
    }

    // ahora armamos el query

    $query = array();

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
    $query[] .= $matchSELECT;

    $query[] .= 'FROM
                 noticia
                 INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                 INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)';
    $query[] .= 'WHERE';

    $query[] .= $matchWHERE;


    $fecha1 = date("Y-m-d",mktime(0,0,0,$_GET['fecha1_Month_ID'],$_GET['fecha1_Day_ID'],$_GET['fecha1_Year_ID']));
    $fecha2 = date("Y-m-d",mktime(0,0,0,$_GET['fecha2_Month_ID'],$_GET['fecha2_Day_ID'],$_GET['fecha2_Year_ID']));

    $query[] .= "(noticia.fecha BETWEEN '".$fecha1."' AND '".$fecha2."')";

    $sector = $_GET['id_sector'];
    $genero = $_GET['id_genero'];
    $tipoautor = $_GET['id_tipo_autor'];
    $tendencia = $_GET['id_tendencia_monitorista'];
    $tipofuente = $_GET['id_tipo_fuente'];


    if($sector != 0) {$query[] .= 'AND noticia.id_sector = '.$sector ;}
    if($genero != 0) {$query[] .= 'AND noticia.id_genero = '.$genero ;}
    if($tipoautor != 0) {$query[] .= 'AND noticia.id_tipo_autor = '.$tipoautor ;}
    if($tendencia != 0) {$query[] .= 'AND noticia.id_tedencia_monitorista = '.$tendencia ;}

    if($tipofuente != 0) {$query[] .= 'AND noticia.id_tipo_fuente = '.$tipofuente ;}
    if(isset($_GET['id_fuente']) && $_GET['id_fuente']!= 0){$query[] .= 'AND noticia.id_fuente = '.$_GET['id_fuente'] ;}
    if(isset($_GET['id_seccion']) && $_GET['id_seccion']!= 0){$query[] .= 'AND noticia.id_seccion = '.$_GET['id_seccion'] ;}

    $query[] .= $order;

    $query_entero = join(" ", $query);

}




$currentPage = $_SERVER["PHP_SELF"];

$maxRows_resultados = 50;
$pageNum_resultados = 0;
if (isset($_GET['pageNum_resultados'])) {
  $pageNum_resultados = $_GET['pageNum_resultados'];
}
$startRow_resultados = $pageNum_resultados * $maxRows_resultados;

mysql_select_db($database_bitacora, $bitacora);
$query_resultados = $query_entero;
$query_limit_resultados = sprintf("%s LIMIT %d, %d", $query_resultados, $startRow_resultados, $maxRows_resultados);
$resultados = mysql_query($query_limit_resultados, $bitacora) or die(mysql_error());
$row_resultados = mysql_fetch_assoc($resultados);

if (isset($_GET['totalRows_resultados'])) {
  $totalRows_resultados = $_GET['totalRows_resultados'];
} else {
  $all_resultados = mysql_query($query_resultados, $bitacora);
  $totalRows_resultados = mysql_num_rows($all_resultados);
}
$totalPages_resultados = ceil($totalRows_resultados/$maxRows_resultados)-1;

$queryString_resultados = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_resultados") == false && 
        stristr($param, "totalRows_resultados") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_resultados = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_resultados = sprintf("&totalRows_resultados=%d%s", $totalRows_resultados, $queryString_resultados);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Búsqueda Avanzada</title>
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
                            <td width="533" height="25" class="label2">Noticias --&gt; Búsqueda Avanzada --&gt; <span class="label4">Resultados</span></td>
                            <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                        </tr>
                    </table>
                  <br />
                  <table width="990" border="3" align="center" cellpadding="0" cellspacing="0">
                  <tr class="header2">
                    <td><div align="center"> </div></td>
                    <td><div align="center">Noticia</div></td>
                    <td><div align="center">Síntesis</div></td>
                    <td><div align="center">Autor</div></td>
                    <td width="90"><div align="center">Fuente:</div></td>
                    <td><div align="center">Enviado a:</div></td>
                    <td><div align="center">P</div></td>
                  </tr>
                  <?php do { 
				  
				  if(mysql_num_rows($resultados) > 0)
					{
						$base->execute_query("SELECT
											empresa.id_empresa AS id_empresa,
											empresa.nombre AS nombre_empresa
											FROM asigna
											INNER JOIN noticia on (asigna.id_noticia = noticia.id_noticia)
											INNER JOIN empresa on (asigna.id_empresa = empresa.id_empresa)
											WHERE asigna.id_noticia = ".$row_resultados['Clave']);
						if($base->num_rows() > 0)
						{
							$enviado = $base->num_rows();
							$msjarray = array();
							while($row = $base->get_row_assoc())
							{
								$msjarray[].='<font color="#00CC00">'.$row['nombre_empresa'].'</font>';
							}
							$msjenviado = join("<br><hr>", $msjarray);
							
						}
						else
						{
							$enviado = 0;
							$msjenviado = '<font color="#FF0000">No enviado</font>';
						}
					
					}// end if numrows
				  
				  
				  
				  
				  ?>
                    <tr class="label1">
                      <td><div align="center"><img name="icon" src="images/icons/<?php echo $row_resultados['TipoFuente']; ?>.png" /></div></td>
                      <td><div align="center"><span class="label5"><a href="<?php echo "ver_noticia_selector.php?id_noticia=".$row_resultados['Clave']."&id_tipo_fuente=".$row_resultados['TipoFuente']; ?>"><?php echo $row_resultados['Clave']; ?></a></span> <br />
                        <?php echo $row_resultados['Encabezado']; ?></div></td>
                      <td><div align="center" class="label2"><?php echo $row_resultados['SintesisNoticia']; ?></div></td>
                      <td><div align="center"><?php echo $row_resultados['AutorNoticia']; ?></div></td>
                      <td width="90"><div align="center"><?php echo $row_resultados['Fecha']; ?></div>
                      <div align="center"><br />
                        <img name="logo" src="data/fuentes/<?php echo $row_resultados['LogoFuente']; ?>" width="64" height="40"/><br />
                        <?php echo $row_resultados['NombreFuente']; ?><br />
                        <br />
                          <span class="label2">Sección:<br />
                          <?php echo $row_resultados['NombreSeccion']; ?></span></div>
                      </td>
                      <td>
                      <?php echo $msjenviado; ?>
                      </td>
                      <td><div align="center"><?php echo $row_resultados['Relevance']; ?></div></td>
                    </tr>
                    <?php } while ($row_resultados = mysql_fetch_assoc($resultados)); ?>
</table>
              
                  <div align="center" class="label2">
                    Resultados <?php echo ($startRow_resultados + 1) ?> - <?php echo min($startRow_resultados + $maxRows_resultados, $totalRows_resultados) ?> de <?php echo $totalRows_resultados ?>
                    <table border="0">
  <tr>
    <td><?php if ($pageNum_resultados > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_resultados=%d%s", $currentPage, 0, $queryString_resultados); ?>">Primero</a>
        <?php } // Show if not first page ?>    </td>
    <td><?php if ($pageNum_resultados > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_resultados=%d%s", $currentPage, max(0, $pageNum_resultados - 1), $queryString_resultados); ?>">Anterior</a>
        <?php } // Show if not first page ?>    </td>
    <td><?php if ($pageNum_resultados < $totalPages_resultados) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_resultados=%d%s", $currentPage, min($totalPages_resultados, $pageNum_resultados + 1), $queryString_resultados); ?>">Siguiente</a>
        <?php } // Show if not last page ?>    </td>
    <td><?php if ($pageNum_resultados < $totalPages_resultados) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_resultados=%d%s", $currentPage, $totalPages_resultados, $queryString_resultados); ?>">Ultimo</a>
        <?php } // Show if not last page ?>    </td>
  </tr>
</table>
</div>
              </p></p></td>
            </tr>
        </table>
        <?php include("includes/init_menu_principal.php");?>
    </body>
</html>
<?php
mysql_free_result($resultados);
//cerramos conexion
$base->free_result();
$base->close();
?>