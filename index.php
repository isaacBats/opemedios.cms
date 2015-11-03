<?php require_once('Connections/bitacora.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "id_tipo_usuario";
  $MM_redirectLoginSuccess = "opemedios.html";
  $MM_redirectLoginFailed = "index.php?err=true";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_bitacora, $bitacora);
  	
  $LoginRS__query=sprintf("SELECT username, password, id_tipo_usuario, activo FROM usuario WHERE username=%s AND password=%s",  // Se le añadio el campo activo 
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $bitacora) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'id_tipo_usuario');
	$activo  = mysql_result($LoginRS,0,'activo');
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;
	$_SESSION['MM_Activo'] = $activo;
	
	if($activo == 1)
	{
		if (isset($_SESSION['PrevUrl']) && false) {
		  $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
		}
		header("Location: " . $MM_redirectLoginSuccess );
		}
		 else {
		header("Location: ". $MM_redirectLoginFailed );
		}
	}
	else
	{
		header("Location: ". $MM_redirectLoginFailed );
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema Integral de Administracion de Empresa de Medios -- OPM 2009</title>
<style type="text/css">
<!--
@import url("CSS/opemedios.css");
#apDiv1 {
	position:absolute;
	left:106px;
	top:67px;
	width:332px;
	height:215px;
	z-index:1;
}
body {
	background-color: #000000;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
#apDiv2 {
	position:absolute;
	left:840px;
	top:167px;
	width:130px;
	height:25px;
	z-index:1;
	overflow: auto;
}
#apDiv3 {
	position:absolute;
	left:275px;
	top:297px;
	width:354px;
	height:122px;
	z-index:2;
}
-->
</style>
<script type="text/javascript">
<!--
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
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' debe contener un valor.\n'; }
    } if (errors) alert('Por favor, verifica lo siguiente:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
//-->
</script>
</head>

<body>
<table width="995" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr>
    <td height="77" colspan="2" valign="top" background="images/images/BackGround_I1.jpg"><!--DWLayoutEmptyCell-->&nbsp;</td>
  </tr>
  <tr>
    <td width="162" height="495" valign="top" background="images/images/BackGround_I2.jpg"><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td width="833" valign="top" background="images/images/BackGround2_I3.jpg"><form ACTION="<?php echo $loginFormAction; ?>" id="loginform" name="loginform" method="POST">
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <table width="820" height="160" border="0" align="center">
        <tr>
          <td width="82" height="44">&nbsp;</td>
            <td width="162" class="label4">Iniciar Sesion</td>
            <td width="307">&nbsp;</td>
            <td width="251">&nbsp;</td>
          </tr>
        <tr>
          <td height="26">&nbsp;</td>
            <td height="26"><div align="right" class="label3">Usuario:</div></td>
            <td height="26"><input name="username" type="text" class="textbox2" id="username" tabindex="1" /></td>
            <td height="26">&nbsp;</td>
          </tr>
        <tr>
          <td height="40">&nbsp;</td>
            <td height="40"><div align="right" class="label3">Contraseña:</div></td>
            <td height="40"><input name="password" type="password" class="textbox2" id="password" tabindex="2" /></td>
            <td height="40"><input name="button" type="submit" id="button" tabindex="3" onclick="MM_validateForm('username','','R','password','','R');return document.MM_returnValue" value="Iniciar Sesion" /></td>
          </tr>
        <tr>
          <td height="40" colspan="3" class="label5"><?php if($_GET['err2'] == true){ echo "Lo Sentimos. Usted No tiene Acceso a la Pagina. Por favor contactar al Administrador del Sistema" ;} ?>
            <?php if($_GET['err'] == true){ echo "Nombre de Usuario y/o Contraseña Incorrectos" ;} ?></td>
            <td height="40">&nbsp;</td>
        </tr>
        </table>
    </form></td>
  </tr>
  <tr>
    <td height="3"></td>
    <td></td>
  </tr>
</table>
</body>
</html>
