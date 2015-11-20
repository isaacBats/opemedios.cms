<?php require_once('Connections/bitacora.php'); ?>
<?php

//llamamos el codigo de sesion para usuario nivel 3 = monitorista
include("phpdelegates/rest_access_3.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/Noticia.php");
include("phpclasses/Usuario.php");

//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");

//creamos un DAO para obtener los datos de la empresa dada mediante el metodo GET
//recibe como parametro el resultado de la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();
//obtenemos la informacion del usuario y creamos el objeto
$base->execute_query("SELECT * FROM usuario WHERE username = '".$session_usr."'");// session_usr se encuentra en los inclides de sesion
$current_user = new Usuario($base->get_row_assoc());

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
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
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_noticias = 100;
$pageNum_noticias = 0;
if (isset($_GET['pageNum_noticias'])) {
  $pageNum_noticias = $_GET['pageNum_noticias'];
}
$startRow_noticias = $pageNum_noticias * $maxRows_noticias;

$parametro_noticias = "1,2,3,4,5";
$orden = "-1";
$tipo_orden = "DESC";
if (isset($_GET['tipo'])) {
  $parametro_noticias = $_GET['tipo'];
  $orden = $_GET['orden'];
}
if($_GET['tipo'] == 6)
{
	$parametro_noticias = "1,2";
}
if ($orden == 0) {
$tipo_orden = "ASC";
}

//die($parametro_noticias);
mysql_select_db($database_bitacora, $bitacora);
$query_noticias = sprintf("SELECT noticia.id_noticia AS Clave,   noticia.encabezado AS Encabezado,   noticia.fecha AS Fecha,   noticia.id_tipo_fuente AS TipoFuente,   fuente.nombre AS NombreFuente,   fuente.logo AS LogoFuente,   seccion.nombre AS NombreSeccion FROM  noticia  INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)  INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion) WHERE   noticia.fecha BETWEEN (CURDATE() - INTERVAL 1 DAY) AND CURDATE() AND noticia.id_tipo_fuente IN (%s) ORDER BY Clave %s", $parametro_noticias, $tipo_orden);
$query_limit_noticias = sprintf("%s LIMIT %d, %d", $query_noticias, $startRow_noticias, $maxRows_noticias);
//die($query_limit_noticias);
//$noticias = mysql_query("SET time_zone = '-06:00'");
$noticias = mysql_query($query_limit_noticias, $bitacora) or die(mysql_error());
$row_noticias = mysql_fetch_assoc($noticias);

if (isset($_GET['totalRows_noticias'])) {
  $totalRows_noticias = $_GET['totalRows_noticias'];
} else {
   $all_noticias = mysql_query($query_noticias,  $bitacora) or die(mysql_error());
  $totalRows_noticias = mysql_num_rows($all_noticias);
}
$totalPages_noticias = ceil($totalRows_noticias/$maxRows_noticias)-1;

$queryString_noticias = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_noticias") == false && 
        stristr($param, "totalRows_noticias") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_noticias = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_noticias = sprintf("&totalRows_noticias=%d%s", $totalRows_noticias, $queryString_noticias);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>noticias hoy</title>
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
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
</head>

<body onload="MM_preloadImages('images/images/menusec_02_television2.png','images/images/menusec_02_radio2.png','images/images/menusec2_periodico.png','images/images/menusec2_02_revista.png','images/images/menusec2_02_internet.png')">
<!-- <p><?php echo $query_noticias; ?></p> -->
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr>
    <td width="1000" height="500" valign="top" background="images/images/BackGround_02bg.jpg"><table width="1000" border="0" cellpadding="0" cellspacing="0" background="images/images/BackGround_02.jpg">
      <tr valign="top">
  <td height="25" colspan="3"><?php include("includes/mainmenu.php");?></td>
      </tr>
      <tr valign="middle">
        <td width="15" height="25">&nbsp;</td>
        <td width="533" height="25" class="label2">Noticias --&gt; <span class="label4">Noticias de Hoy</span></td>
        <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
      </tr>
    </table>
      <table width="1000" border="0" cellspacing="0" cellpadding="0" valign="top">
        <tr>
          <td width="160">&nbsp;</td>
          <td width="840">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top">
			<table width="152" border="0" align="center" cellpadding="0" cellspacing="0" valign="top">
            <tr>
              <td><img src="images/images/menusec_01_filtrarpor.png" width="152" height="28" /></td>
            </tr>
            <tr>
              <td><a href="noticiashoy2.php?tipo=1" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('television','','images/images/menusec_02_television2.png',1)"><img src="images/images/menusec_02_television_1.png" name="television" width="152" height="23" border="0" id="television" /></a></td>
            </tr>
            <tr>
              <td><a href="noticiashoy2.php?tipo=2" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('radio','','images/images/menusec_02_radio2.png',1)"><img src="images/images/menusec_02_radio1.png" name="radio" width="152" height="23" border="0" id="radio" /></a></td>
            </tr>
            <tr>
              <td><a href="noticiashoy2.php?tipo=3" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('periodico','','images/images/menusec2_periodico.png',1)"><img src="images/images/menusec_02_periodico1.png" name="periodico" width="152" height="23" border="0" id="periodico" /></a></td>
            </tr>
            <tr>
              <td><a href="noticiashoy2.php?tipo=4" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('revista','','images/images/menusec2_02_revista.png',1)"><img src="images/images/menusec_02_revista1.png" name="revista" width="152" height="23" border="0" id="revista" /></a></td>
            </tr>
            <tr>
              <td><a href="noticiashoy2.php?tipo=5" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('internet','','images/images/menusec2_02_internet.png',1)"><img src="images/images/menusec_02_internet1.png" name="internet" width="152" height="23" border="0" id="internet" /></a></td>
            </tr>
            <tr>
              <td><img src="images/images/menusec_03.png" width="152" height="17" /></td>
            </tr>
            <tr>
                <td class="label1"><a href="noticiashoy2.php?tipo=6">Tele y Radio</a></td>
            </tr>
          </table>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
          <p>&nbsp;</p></td>
          <td valign="top"><table width="825" border="3" cellpadding="0" cellspacing="0">
              <tr class="header2">
                <td><div align="center"></div></td>
                <td><div align="center">Noticia&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="noticiashoy2.php?tipo=<?php echo $parametro_noticias ?>&orden=0"><img src="images/up_flecha.png" width="20" height="18"/></a><a href="noticiashoy2.php?tipo=<?php echo $parametro_noticias ?>&orden=1"><img src="images/down_flecha.png" width="32" height="18" /></a></div></td>
                <td><div align="center">Fuente&nbsp;&nbsp;&nbsp;<a href="noticiashoy2_fuente.php?tipo=<?php echo $parametro_noticias ?>&orden=0"><img src="images/up_flecha.png" width="20" height="18"/></a><a href="noticiashoy2_fuente.php?tipo=<?php echo $parametro_noticias ?>&orden=1"><img src="images/down_flecha.png" width="32" height="18" /></a></div></td>
                <td><div align="center">Enviado a:&nbsp;&nbsp;&nbsp;<a href="noticiashoy2.php?tipo=<?php echo $parametro_noticias ?>"><img src="images/up_flecha.png" width="20" height="18"/></a><a href="noticiashoy2.php?tipo=<?php echo $parametro_noticias ?>"><img src="images/down_flecha.png" width="32" height="18" /></a></div></td>
              </tr>
              <?php do {
				  
				  if(mysql_num_rows($noticias) > 0)
					{
						$base->execute_query("SELECT
											empresa.id_empresa AS id_empresa,
											empresa.nombre AS nombre_empresa
											FROM asigna
											INNER JOIN noticia on (asigna.id_noticia = noticia.id_noticia)
											INNER JOIN empresa on (asigna.id_empresa = empresa.id_empresa)
											WHERE asigna.id_noticia = ".$row_noticias['Clave']);
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
              <tr class="label2">
                <td><div align="center"><img src="images/icons/<?php echo $row_noticias['TipoFuente']; ?>.png" name="icon" id="icon" /><span class="label5"><br />
                </span></div></td>
                <td><div align="center" class="label1"><span class="label5"><a href="ver_noticia_selector.php?id_noticia=<?php echo $row_noticias['Clave']; ?>&amp;id_tipo_fuente=<?php echo $row_noticias['TipoFuente']; ?>"><?php echo $row_noticias['Clave']; ?></a></span><br />
                        <?php echo $row_noticias['Encabezado']; ?></div></td>
                <td><div align="center"><?php echo $row_noticias['NombreFuente']; ?></div></td>
                <td><div align="center"><?php echo $msjenviado; ?></div></td>
              </tr>
              <?php } while ($row_noticias = mysql_fetch_assoc($noticias)); ?>
            </table>
              <div align="center" class="label2"> Registros <?php echo ($startRow_noticias + 1) ?> - <?php echo min($startRow_noticias + $maxRows_noticias, $totalRows_noticias) ?> de <?php echo $totalRows_noticias ?> <br />
                  <table border="0">
                    <tr>
                      <td><?php if ($pageNum_noticias > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_noticias=%d%s", $currentPage, 0, $queryString_noticias); ?>">Primero</a>
                          <?php } // Show if not first page ?>
                      </td>
                      <td><?php if ($pageNum_noticias > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_noticias=%d%s", $currentPage, max(0, $pageNum_noticias - 1), $queryString_noticias); ?>">Anterior</a>
                          <?php } // Show if not first page ?>
                      </td>
                      <td><?php if ($pageNum_noticias < $totalPages_noticias) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_noticias=%d%s", $currentPage, min($totalPages_noticias, $pageNum_noticias + 1), $queryString_noticias); ?>">Siguiente</a>
                          <?php } // Show if not last page ?>
                      </td>
                      <td><?php if ($pageNum_noticias < $totalPages_noticias) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_noticias=%d%s", $currentPage, $totalPages_noticias, $queryString_noticias); ?>">Ultimo</a>
                          <?php } // Show if not last page ?>
                      </td>
                    </tr>
                  </table>
              </div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    <p>&nbsp;</p></td>
  </tr>
</table>
<script type="text/javascript">
<!--
var MenuBar1 = new Spry.Widget.MenuBar("MenuPrincipal", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>
</body>
</html>
<?php
mysql_free_result($noticias);
//cerramos conexion
$base->free_result();
$base->close();
?>