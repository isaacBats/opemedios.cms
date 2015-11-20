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
?>
<?php
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form_update")) {
  $updateSQL = sprintf("UPDATE usuario SET nombre=%s, apellidos=%s, direccion=%s, telefono1=%s, telefono2=%s, email=%s, cargo=%s, comentario=%s, username=%s, password=%s, activo=%s, id_tipo_usuario=%s WHERE id_usuario=%s",
                       GetSQLValueString($_POST['nombre'], "text"),
                       GetSQLValueString($_POST['apellidos'], "text"),
                       GetSQLValueString($_POST['direccion'], "text"),
                       GetSQLValueString($_POST['telefono1'], "text"),
                       GetSQLValueString($_POST['telefono2'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['cargo'], "text"),
                       GetSQLValueString($_POST['comentario'], "text"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString(isset($_POST['activo']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['id_tipo_usuario'], "int"),
                       GetSQLValueString($_POST['id_usuario'], "int"));

  mysql_select_db($database_bitacora, $bitacora);
  $Result1 = mysql_query($updateSQL, $bitacora) or die(mysql_error());

  $updateGoTo = "admin_usuarios.php?updated=true";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_welcome = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_welcome = $_SESSION['MM_Username'];
}
mysql_select_db($database_bitacora, $bitacora);
$query_welcome = sprintf("SELECT id_usuario, nombre, apellidos FROM usuario WHERE username = %s", GetSQLValueString($colname_welcome, "text"));
$welcome = mysql_query($query_welcome, $bitacora) or die(mysql_error());
$row_welcome = mysql_fetch_assoc($welcome);
$totalRows_welcome = mysql_num_rows($welcome);

mysql_select_db($database_bitacora, $bitacora);
$query_tipos_usuarios = "SELECT * FROM tipo_usuario";
$tipos_usuarios = mysql_query($query_tipos_usuarios, $bitacora) or die(mysql_error());
$row_tipos_usuarios = mysql_fetch_assoc($tipos_usuarios);
$totalRows_tipos_usuarios = mysql_num_rows($tipos_usuarios);

$colname_usuario = "-1";
if (isset($_GET['id_usuario'])) {
  $colname_usuario = $_GET['id_usuario'];
}
mysql_select_db($database_bitacora, $bitacora);
$query_usuario = sprintf("SELECT * FROM usuario WHERE id_usuario = %s", GetSQLValueString($colname_usuario, "int"));
$usuario = mysql_query($query_usuario, $bitacora) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>agregar usuario</title>
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
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
//-->
</script>
</head>

<body>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr>
    <td width="1000" height="500" valign="top" background="images/images/BackGround_02bg.jpg"><table width="1000" border="0" cellpadding="0" cellspacing="0" background="images/images/BackGround_02.jpg">
      <tr valign="top">
        <td height="25" colspan="3"><div align="center">
          <ul id="MenuPrincipal" class="MenuBarHorizontal">
            <li><a href="#" class="MenuBarItemSubmenu">Noticias</a>
              <ul>
                <li><a href="#" class="MenuBarItemSubmenu">Agregar Noticia</a>
                    <ul>
                      <li><a href="#">Televisi&oacute;n</a></li>
                      <li><a href="#">Radio</a></li>
                      <li><a href="#">Peri&oacute;dico</a></li>
                      <li><a href="#">Revista</a></li>
                      <li><a href="#">Internet</a></li>
                    </ul>
                </li>
                <li><a href="noticiashoy.php">Noticias de Hoy</a></li>
                <li><a href="busqueda_avanzada.php">B&uacute;squeda Avanzada</a></li>
                <li><a href="envia_bloque_noticias.php">Enviar Bloque de Noticias por Correo</a></li>
              </ul>
               </li>
            <li><a href="#" class="MenuBarItemSubmenu">Fuentes</a>
              <ul>
                <li><a href="#" class="MenuBarItemSubmenu">Agregar Fuentes</a>
                    <ul>
                      <li><a href="agrega_fuente_television.php">Televisi&oacute;n</a></li>
                      <li><a href="agrega_fuente_radio.php">Radio</a></li>
                      <li><a href="agrega_fuente_periodico.php">Peri&oacute;dico</a></li>
                      <li><a href="agrega_fuente_revista.php">Revista</a></li>
                      <li><a href="agrega_fuente_internet.php">Internet</a></li>
                    </ul>
                </li>
                <li><a href="admin_fuente.php">Administrar Fuentes</a></li>
              </ul>
              </li>
            <li><a href="#" class="MenuBarItemSubmenu">Sectores</a>
              <ul>
                <li><a href="agrega_sector.php">Agregar Sector</a></li>
                <li><a href="admin_sectores.php">Administrar Sectores</a></li>
              </ul>
              </li>
            <li><a href="#" class="MenuBarItemSubmenu">Prensa</a>
              <ul>
                <li><a href="#" class="MenuBarItemSubmenu">Primeras Planas</a>
                  <ul>
                    <li><a href="agrega_primeraplana.php">Agregar</a></li>
                    <li><a href="admin_primerasplanas.php">Administrar</a></li>
                    <li><a href="envio_primerasplanas.php">Enviar a Clientes</a></li>
                  </ul>
                  </li>
                <li><a href="#" class="MenuBarItemSubmenu">Portadas Financieras</a>
                  <ul>
                    <li><a href="agrega_portadafinanciera.php">Agregar</a></li>
                    <li><a href="admin_portadasfinancieras.php">Administrar</a></li>
                    <li><a href="envio_portadasfinancieras.php">Enviar a Clientes</a></li>
                  </ul>
                  </li>
                <li><a href="#" class="MenuBarItemSubmenu">Columnas Pol&iacute;ticas</a>
                  <ul>
                    <li><a href="agrega_colpol.php">Agregar</a></li>
                    <li><a href="admin_colpol.php">Administrar</a></li>
                    <li><a href="envio_colpol.php">Enviar a Clientes</a></li>
                  </ul>
                  </li>
                <li><a href="#" class="MenuBarItemSubmenu">Columnas Financieras</a>
                  <ul>
                    <li><a href="agrega_colfin.php">Agregar</a></li>
                    <li><a href="admin_colfin.php">Administrar</a></li>
                    <li><a href="envio_colfin.php">Enviar a Clientes</a></li>
                  </ul>
                  </li>
                <li><a href="#" class="MenuBarItemSubmenu">Cartones</a>
                  <ul>
                    <li><a href="agrega_carton.php">Agregar</a></li>
                    <li><a href="admin_cartones.php">Administrar</a></li>
                    <li><a href="envio_cartones.php">Enviar a Clientes</a></li>
                  </ul>
                  </li>
              </ul>
              </li>
            <li><a href="#" class="MenuBarItemSubmenu">Asignaci&oacute;n</a>
                <ul>
                  <li><a href="asignacion_noticia_cliente.php">Noticias por Cliente</a> </li>
                  </ul>
            </li>
            <li><a href="#" class="MenuBarItemSubmenu">Reportes</a>
              <ul>
                <li><a href="reporte_noticia_cliente.php">Noticias por Cliente</a></li>
                <li><a href="reporte_noticia_monitorista.php">Noticias por Monitorista</a></li>
              </ul>
              </li>
            <li><a href="#" class="MenuBarItemSubmenu">Gr&aacute;ficas</a>
              <ul>
                <li><a href="#" class="MenuBarItemSubmenu">Noticias por Cliente</a>
                    <ul>
                      <li><a href="grafica_clientes_general.php">General</a></li>
                      <li><a href="grafica_cliente_tema.php">Por Tema</a></li>
                      <li><a href="grafica_cliente_tipofuente.php">Por Tipo de Fuente</a></li>
                      <li><a href="grafica_cliente_fuente.php">Por Fuente</a></li>
                      <li><a href="grafica_cliente_otras.php">Otros</a></li>
                    </ul>
                </li>
                <li><a href="#" class="MenuBarItemSubmenu">Noticias por Fuente</a>
                  <ul>
                    <li><a href="grafica_fuentes_general.php">General</a></li>
                    <li><a href="grafica_fuente_seccion.php">Por Seccion</a></li>
                    <li><a href="grafica_fuente_sector.php">Por Sector</a></li>
                    <li><a href="grafica_fuente_tendencia.php">Por Tendencia</a></li>
                  </ul>
                  </li>
                <li><a href="grafica_otras.php">Otras gr&aacute;ficas</a></li>
              </ul>
              </li>
            <li><a href="#" class="MenuBarItemSubmenu">Clientes</a>
              <ul>
                <li><a href="agrega_cliente.php">Agregar Cliente</a></li>
                <li><a href="admin_clientes.php">Administrar Clientes</a></li>
              </ul>
              </li>
            <li><a href="#" class="MenuBarItemSubmenu">Usuarios</a>
              <ul>
                <li><a href="agrega_usuario.php">Agregar Usuario</a></li>
                <li><a href="admin_usuarios.php">Administrar Usuarios</a></li>
              </ul>
              </li>
            <li><a href="<?php echo $logoutAction ?>" target="_parent">Cerrar Sesi&oacute;n</a></li>
          </ul>
          </div></td>
      </tr>
      <tr valign="middle">
        <td width="15" height="25">&nbsp;</td>
        <td width="533" height="25" class="label2">Usuarios --&gt; Administrar Usuarios --&gt; <span class="label4">Modificar Usuario</span></td>
        <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $row_welcome['nombre']." ".$row_welcome['apellidos']; ?></span></td>
      </tr>
    </table>
      <form action="<?php echo $editFormAction; ?>" id="form_update" name="form_update" method="POST">
        <table width="1000" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="40">&nbsp;</td>
            <td width="450">&nbsp;</td>
            <td width="20">&nbsp;</td>
            <td width="450">&nbsp;</td>
            <td width="40">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td valign="top"><table width="450" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td colspan="2" class="label2"><div align="center">Datos Personales:</div></td>
                </tr>
                <tr>
                  <td width="95" class="label2"><input name="id_usuario" type="hidden" id="id_usuario" value="<?php echo $row_usuario['id_usuario']; ?>" /></td>
                  <td width="355">&nbsp;</td>
                </tr>
                <tr>
                  <td height="25" class="label3">Nombre</td>
                  <td><label>
                    <input name="nombre" type="text" class="textbox1" id="nombre" value="<?php echo $row_usuario['nombre']; ?>" />
                  </label></td>
                </tr>
                <tr>
                  <td height="25" class="label3">Apellidos</td>
                  <td><label>
                    <input name="apellidos" type="text" class="textbox1" id="apellidos" value="<?php echo $row_usuario['apellidos']; ?>" />
                  </label></td>
                </tr>
                <tr>
                  <td height="50" class="label3">Dirección</td>
                  <td><label>
                    <textarea name="direccion" cols="45" rows="5" class="texboxML" id="direccion"><?php echo $row_usuario['direccion']; ?></textarea>
                  </label></td>
                </tr>
                <tr>
                  <td height="25" class="label3">Teléfono 1</td>
                  <td><label>
                    <input name="telefono1" type="text" class="textbox1" id="telefono1" value="<?php echo $row_usuario['telefono1']; ?>" />
                  </label></td>
                </tr>
                <tr>
                  <td height="25" class="label3">Teléfono 2</td>
                  <td height="25"><label>
                    <input name="telefono2" type="text" class="textbox1" id="telefono2" value="<?php echo $row_usuario['telefono2']; ?>" />
                  </label></td>
                </tr>
                <tr>
                  <td height="25" class="label3">E-mail</td>
                  <td><label>
                    <input name="email" type="text" class="textbox1" id="email" value="<?php echo $row_usuario['email']; ?>" />
                  </label></td>
                </tr>
                <tr>
                  <td height="25" class="label3">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td height="25" class="label3">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td height="25" class="label3">&nbsp;</td>
                  <td><span class="label5">
                    <?php if(isset($_GET['error'])&& $_GET['error']== "true"){echo "El nombre de usuario ya existe, favor de seleccionar otro diferente";} ?>
                  </span></td>
                </tr>
                <tr>
                  <td height="25" colspan="2" class="label5">&nbsp;</td>
                </tr>
            </table></td>
            <td valign="top">&nbsp;</td>
            <td valign="top"><table width="450" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2" class="label2"><div align="center">Datos Laborales:</div></td>
                </tr>
              <tr>
                <td width="100">&nbsp;</td>
                <td width="350">&nbsp;</td>
              </tr>
              <tr>
                <td height="25" class="label3">Tipo</td>
                <td><label>
                  <select name="id_tipo_usuario" class="combo3" id="id_tipo_usuario">
                    <?php
do {  
?>
                    <option value="<?php echo $row_tipos_usuarios['id_tipo_usuario']?>"<?php if (!(strcmp($row_tipos_usuarios['id_tipo_usuario'], $row_usuario['id_tipo_usuario']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tipos_usuarios['descripcion']?></option>
                    <?php
} while ($row_tipos_usuarios = mysql_fetch_assoc($tipos_usuarios));
  $rows = mysql_num_rows($tipos_usuarios);
  if($rows > 0) {
      mysql_data_seek($tipos_usuarios, 0);
	  $row_tipos_usuarios = mysql_fetch_assoc($tipos_usuarios);
  }
?>
                   
                  </select>
                </label></td>
              </tr>
              <tr>
                <td height="25" class="label3">Cargo</td>
                <td><label>
                  <input name="cargo" type="text" class="textbox1" id="cargo" value="<?php echo $row_usuario['cargo']; ?>" />
                </label></td>
              </tr>
              <tr>
                <td height="50" class="label3">Comentarios</td>
                <td><label>
                  <textarea name="comentario" cols="45" rows="5" class="texboxML" id="comentario"><?php echo $row_usuario['comentario']; ?></textarea>
                </label></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2" class="label2"><div align="center">Datos de Sistema:</div></td>
                </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td height="25" class="label3">Username</td>
                <td><label>
                  <input name="username" type="text" class="textbox1" id="username" value="<?php echo $row_usuario['username']; ?>" />
                </label></td>
              </tr>
              <tr>
                <td height="25" class="label3">Password</td>
                <td><label>
                  <input name="password" type="text" class="textbox1" id="password" value="<?php echo $row_usuario['password']; ?>" />
                </label></td>
              </tr>
              <tr>
                <td height="25" class="label3">Activo</td>
                <td><label>
                  <input <?php if (!(strcmp($row_usuario['activo'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="activo" id="activo" />
                </label></td>
              </tr>
              <tr>
                <td class="label3">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="label3">&nbsp;</td>
                <td><label>
                  <div align="right">
                    <input name="button" type="submit" id="button" onclick="MM_validateForm('nombre','','R','apellidos','','R','telefono1','','R','email','','RisEmail','cargo','','R','username','','R','password','','R','direccion','','R');return document.MM_returnValue" value="Modificar Usuario" />
                  </div>
                  </label></td>
              </tr>
              <tr>
                <td class="label3">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="label3">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="label3">&nbsp;</td>
                <td><hr /></td>
              </tr>
              <tr>
                <td class="label3">&nbsp;</td>
                <td><div align="right"><a onclick="if(!confirm('¿Está seguro de Eliminar el Usuario <?php echo $row_usuario['nombre'] ?> permanentemente?'))return false" href="action_elimina_usuario.php?id_usuario=<?php echo $row_usuario['id_usuario']?>"><img src="images/boton_elimina_usuario.gif" alt="Elimina Usuario" width="113" height="21" border="0" /></a></div></td>
              </tr>
            </table></td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
            
        <input type="hidden" name="MM_update" value="form_update" />
    </form>      <p>&nbsp;</p></td>
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

mysql_free_result($tipos_usuarios);

mysql_free_result($usuario);
?>

