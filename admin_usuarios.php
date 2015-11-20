<?php require_once('Connections/bitacora.php'); ?><?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "accesodenegado.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?><?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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

$colname_welcome = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_welcome = $_SESSION['MM_Username'];
}
mysql_select_db($database_bitacora, $bitacora);
$query_welcome = sprintf("SELECT id_usuario, nombre, apellidos FROM usuario WHERE username = %s", GetSQLValueString($colname_welcome, "text"));
$welcome = mysql_query($query_welcome, $bitacora) or die(mysql_error());
$row_welcome = mysql_fetch_assoc($welcome);
$totalRows_welcome = mysql_num_rows($welcome);

$maxRows_usuarios_get = 50;
$pageNum_usuarios_get = 0;
if (isset($_GET['pageNum_usuarios_get'])) {
  $pageNum_usuarios_get = $_GET['pageNum_usuarios_get'];
}
$startRow_usuarios_get = $pageNum_usuarios_get * $maxRows_usuarios_get;

$colname_usuarios_get = "-1";
if (isset($_GET['tipo'])) {
  $colname_usuarios_get = $_GET['tipo'];
}
mysql_select_db($database_bitacora, $bitacora);
$query_usuarios_get = sprintf("SELECT Usr.id_usuario AS Clave, Usr.nombre AS Nombre, Usr.apellidos AS Apellidos, Tipo.descripcion AS Tipo_Usuario, Usr.cargo AS Cargo FROM usuario AS Usr, tipo_usuario AS Tipo WHERE Usr.id_tipo_usuario = %s AND Usr.id_tipo_usuario = Tipo.id_tipo_usuario", GetSQLValueString($colname_usuarios_get, "int"));
$query_limit_usuarios_get = sprintf("%s LIMIT %d, %d", $query_usuarios_get, $startRow_usuarios_get, $maxRows_usuarios_get);
$usuarios_get = mysql_query($query_limit_usuarios_get, $bitacora) or die(mysql_error());
$row_usuarios_get = mysql_fetch_assoc($usuarios_get);

if (isset($_GET['totalRows_usuarios_get'])) {
  $totalRows_usuarios_get = $_GET['totalRows_usuarios_get'];
} else {
  $all_usuarios_get = mysql_query($query_usuarios_get);
  $totalRows_usuarios_get = mysql_num_rows($all_usuarios_get);
}
$totalPages_usuarios_get = ceil($totalRows_usuarios_get/$maxRows_usuarios_get)-1;

$maxRows_usuarios_post = 50;
$pageNum_usuarios_post = 0;
if (isset($_GET['pageNum_usuarios_post'])) {
  $pageNum_usuarios_post = $_GET['pageNum_usuarios_post'];
}
$startRow_usuarios_post = $pageNum_usuarios_post * $maxRows_usuarios_post;

$parametro_usuarios_post = "-1";
if (isset($_POST['txt_buscar'])) {
  $parametro_usuarios_post = $_POST['txt_buscar'];
}
mysql_select_db($database_bitacora, $bitacora);
$query_usuarios_post = sprintf("SELECT Usr.id_usuario AS Clave, Usr.nombre AS Nombre, Usr.apellidos AS Apellidos, Tipo.descripcion AS Tipo_Usuario, Usr.cargo AS Cargo FROM usuario AS Usr, tipo_usuario AS Tipo WHERE (Usr.nombre LIKE %s OR Usr.apellidos LIKE %s OR Usr.cargo LIKE %s) AND Usr.id_tipo_usuario = Tipo.id_tipo_usuario ORDER BY Tipo_Usuario", GetSQLValueString("%" . $parametro_usuarios_post . "%", "text"),GetSQLValueString("%" . $parametro_usuarios_post . "%", "text"),GetSQLValueString("%" . $parametro_usuarios_post . "%", "text"));
$query_limit_usuarios_post = sprintf("%s LIMIT %d, %d", $query_usuarios_post, $startRow_usuarios_post, $maxRows_usuarios_post);
$usuarios_post = mysql_query($query_limit_usuarios_post, $bitacora) or die(mysql_error());
$row_usuarios_post = mysql_fetch_assoc($usuarios_post);

if (isset($_GET['totalRows_usuarios_post'])) {
  $totalRows_usuarios_post = $_GET['totalRows_usuarios_post'];
} else {
  $all_usuarios_post = mysql_query($query_usuarios_post);
  $totalRows_usuarios_post = mysql_num_rows($all_usuarios_post);
}
$totalPages_usuarios_post = ceil($totalRows_usuarios_post/$maxRows_usuarios_post)-1;

$queryString_usuarios_get = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_usuarios_get") == false && 
        stristr($param, "totalRows_usuarios_get") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_usuarios_get = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_usuarios_get = sprintf("&totalRows_usuarios_get=%d%s", $totalRows_usuarios_get, $queryString_usuarios_get);

$queryString_usuarios_post = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_usuarios_post") == false && 
        stristr($param, "totalRows_usuarios_post") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_usuarios_post = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_usuarios_post = sprintf("&totalRows_usuarios_post=%d%s", $totalRows_usuarios_post, $queryString_usuarios_post);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>administrar usuarios</title>
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

<body onload="MM_preloadImages('images/images/menusec2_02_monitoristas.png','images/images/menusec2_02_encararea.png','images/images/menusec2_02_administradores.png')">
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr>
    <td width="1000" height="500" valign="top" background="images/images/BackGround_02bg.jpg"><table width="1000" border="0" cellpadding="0" cellspacing="0" background="images/images/BackGround_02.jpg">
      <tr valign="top">
        <td height="25" colspan="3"><?php include("includes/mainmenu.php");?></td>
      </tr>
      <tr valign="middle">
        <td width="15" height="25">&nbsp;</td>
        <td width="533" height="25" class="label2">Usuarios --&gt; <span class="label4">Administrar Usuarios</span></td>
        <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $row_welcome['nombre']." ".$row_welcome['apellidos']; ?></span></td>
      </tr>
    </table>
      <table width="1000" border="0">
        <tr>
          <td width="160">&nbsp;</td>
          <td width="830"><div align="right" class="label2"><?php if(isset($_GET['updated']) && $_GET['updated'] == "true"){echo "Información de Usuario Modificada!..."; }?><?php if(isset($_GET['added']) && $_GET['added'] == "true"){echo "Usuario Agregado!..."; }?></div></td>
        </tr>
        <tr>
          <td valign="top"><form id="form_buscar" name="form_buscar" method="post" action="admin_usuarios.php">
            <table width="152" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td height="28" background="images/images/menusec_01_busqueda.png">&nbsp;</td>
              </tr>
              <tr>
                <td height="55" align="center" valign="middle" background="images/images/menusec_02_empty.png"><label>
                <input name="txt_buscar" type="text" class="textboxsec" id="txt_buscar" />
                <br />
                <input name="btnbuscar" type="submit" class="boton1" id="btnbuscar" value="Buscar" />
                <br />
</label></td>
              </tr>

              <tr>
                <td height="17"><img src="images/images/menusec2_03.png" width="152" height="17" /></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><img src="images/images/menusec2_01.png" width="152" height="28" /></td>
              </tr>
              <tr>
                <td><a href="admin_usuarios.php?tipo=3" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('monitoristas','','images/images/menusec2_02_monitoristas.png',1)"><img src="images/images/menusec_02_monitoristas.png" name="monitoristas" width="152" height="23" border="0" id="monitoristas" /></a></td>
              </tr>
              <tr>
                <td><a href="admin_usuarios.php?tipo=2" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('encargados','','images/images/menusec2_02_encararea.png',1)"><img src="images/images/menusec_02_encargarea.png" name="encargados" width="152" height="23" border="0" id="encargados" /></a></td>
              </tr>
              <tr>
                <td><a href="admin_usuarios.php?tipo=1" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('administradores','','images/images/menusec2_02_administradores.png',1)"><img src="images/images/menusec_02_administradores.png" name="administradores" width="152" height="23" border="0" id="administradores" /></a></td>
              </tr>
              <tr>
                <td><img src="images/images/menusec2_03.png" width="152" height="17" /></td>
              </tr>
            </table>
                      </form>          </td>
          <td valign="top"><?php if ($totalRows_usuarios_get > 0) { // Show if recordset not empty ?>
              <table width="825" border="3" align="center" cellpadding="0" cellspacing="0">
                <tr class="header2">
                  <td><div align="center">Clave</div></td>
                  <td><div align="center">Nombre</div></td>
                  <td><div align="center">Tipo de Usuario</div></td>
                  <td><div align="center">Cargo</div></td>
                  <td><div align="center">Acciones:</div></td>
                </tr>
                <?php do { ?>
                  <tr class="label1">
                    <td><div align="center"><?php echo $row_usuarios_get['Clave']; ?></div></td>
                    <td><div align="center"><?php echo $row_usuarios_get['Nombre']." ".$row_usuarios_get['Apellidos']; ?> </div></td>
                    <td><div align="center"><?php echo $row_usuarios_get['Tipo_Usuario']; ?></div></td>
                    <td><div align="center"><?php echo $row_usuarios_get['Cargo']; ?></div></td>
                    <td><div align="center" class="label2"><a href="modifica_usuario.php?id_usuario=<?php echo $row_usuarios_get['Clave']; ?>">Ver o Editar</a></div></td>
                  </tr>
                  <?php } while ($row_usuarios_get = mysql_fetch_assoc($usuarios_get)); ?>
              </table>
              <div align="center">
                <table border="0" class="label2">
                  <tr>
                    <td><?php if ($pageNum_usuarios_get > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_usuarios_get=%d%s", $currentPage, 0, $queryString_usuarios_get); ?>">Primero</a>
                          <?php } // Show if not first page ?>
                    </td>
                    <td><?php if ($pageNum_usuarios_get > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_usuarios_get=%d%s", $currentPage, max(0, $pageNum_usuarios_get - 1), $queryString_usuarios_get); ?>">Anterior</a>
                          <?php } // Show if not first page ?>
                    </td>
                    <td><?php if ($pageNum_usuarios_get < $totalPages_usuarios_get) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_usuarios_get=%d%s", $currentPage, min($totalPages_usuarios_get, $pageNum_usuarios_get + 1), $queryString_usuarios_get); ?>">Siguiente</a>
                          <?php } // Show if not last page ?>
                    </td>
                    <td><?php if ($pageNum_usuarios_get < $totalPages_usuarios_get) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_usuarios_get=%d%s", $currentPage, $totalPages_usuarios_get, $queryString_usuarios_get); ?>">Ultimo</a>
                          <?php } // Show if not last page ?>
                    </td>
                  </tr>
                </table>
                <span class="label2">Registros <?php echo ($startRow_usuarios_get + 1) ?>- <?php echo min($startRow_usuarios_get + $maxRows_usuarios_get, $totalRows_usuarios_get) ?>de <?php echo $totalRows_usuarios_get ?></span></div>
              <?php } // Show if recordset not empty ?>
            
            <?php if ($totalRows_usuarios_post > 0) { // Show if recordset not empty ?>
              <table width="825" border="3" align="center" cellpadding="0" cellspacing="0">
                <tr class="header2">
                  <td><div align="center">Clave</div></td>
                  <td><div align="center">Nombre</div></td>
                  <td><div align="center">Tipo de Usuario</div></td>
                  <td><div align="center">Cargo</div></td>
                  <td>&nbsp;</td>
                </tr>
                <?php do { ?>
                  <tr class="label1">
                    <td><div align="center"><?php echo $row_usuarios_post['Clave']; ?></div></td>
                    <td><div align="center"><?php echo $row_usuarios_post['Nombre']." ".$row_usuarios_post['Apellidos']; ?> </div></td>
                    <td><div align="center"><?php echo $row_usuarios_post['Tipo_Usuario']; ?></div></td>
                    <td><div align="center"><?php echo $row_usuarios_post['Cargo']; ?></div></td>
                    <td><div align="center" class="label2"><a href="modifica_usuario.php?id_usuario=<?php echo $row_usuarios_post['Clave']; ?>">Ver o Editar</a></div></td>
                  </tr>
                  <?php } while ($row_usuarios_post = mysql_fetch_assoc($usuarios_post)); ?>
              </table>
              <div align="center" class="label2">
                  <table border="0">
                    <tr>
                      <td><?php if ($pageNum_usuarios_post > 0) { // Show if not first page ?>
                        <a href="<?php printf("%s?pageNum_usuarios_post=%d%s", $currentPage, 0, $queryString_usuarios_post); ?>">Primero</a>
                        <?php } // Show if not first page ?>                  </td>
                      <td><?php if ($pageNum_usuarios_post > 0) { // Show if not first page ?>
                        <a href="<?php printf("%s?pageNum_usuarios_post=%d%s", $currentPage, max(0, $pageNum_usuarios_post - 1), $queryString_usuarios_post); ?>">Anterior</a>
                        <?php } // Show if not first page ?>                  </td>
                      <td><?php if ($pageNum_usuarios_post < $totalPages_usuarios_post) { // Show if not last page ?>
                        <a href="<?php printf("%s?pageNum_usuarios_post=%d%s", $currentPage, min($totalPages_usuarios_post, $pageNum_usuarios_post + 1), $queryString_usuarios_post); ?>">Siguiente</a>
                        <?php } // Show if not last page ?>                  </td>
                      <td><?php if ($pageNum_usuarios_post < $totalPages_usuarios_post) { // Show if not last page ?>
                        <a href="<?php printf("%s?pageNum_usuarios_post=%d%s", $currentPage, $totalPages_usuarios_post, $queryString_usuarios_post); ?>">Ultimo</a>
                        <?php } // Show if not last page ?>                  </td>
                    </tr>
                </table>
                Registros <?php echo ($startRow_usuarios_post + 1) ?>- <?php echo min($startRow_usuarios_post + $maxRows_usuarios_post, $totalRows_usuarios_post) ?> de <?php echo $totalRows_usuarios_post ?>
              </div>
<?php } // Show if recordset not empty ?>
              
              <p align="center"><span class="label5"> <?php if($totalRows_usuarios_post == 0 && isset($_POST['txt_buscar'])){echo "La Busqueda no arrojó resultados";} ?> </span></p>
</td>
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
mysql_free_result($welcome);

mysql_free_result($usuarios_get);

mysql_free_result($usuarios_post);
?>

