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

        switch ($theType)
        {
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

    $colname_welcome = "-1";
    if (isset($_SESSION['MM_Username'])) {
        $colname_welcome = $_SESSION['MM_Username'];
    }
    mysql_select_db($database_bitacora, $bitacora);
    $query_welcome = sprintf("SELECT id_usuario, nombre, apellidos FROM usuario WHERE username = %s", GetSQLValueString($colname_welcome, "text"));
    $welcome = mysql_query($query_welcome, $bitacora) or die(mysql_error());
    $row_welcome = mysql_fetch_assoc($welcome);
    $totalRows_welcome = mysql_num_rows($welcome);
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
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' debe contener una direccion de correo válida.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' debe contener un valor numérico.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' debe contener algun valor.\n'; }
    } if (errors) alert('Por Favor atender los siguientes puntos:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
//-->
</script>


        <script type="text/javascript" language="javascript" src="colorpicker/js/colorPicker.js"></script>
        <link rel="stylesheet" href="colorpicker/css/colorPicker.css" type="text/css"></link>

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
                          <td width="533" height="25" class="label2">Clientes --&gt; <span class="label4">Agregar Cliente</span></td>
                          <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $row_welcome['nombre']." ".$row_welcome['apellidos']; ?></span></td>
                  </tr>
                    </table>
<form action="action_agrega_cliente.php" method="POST" name="form_inserta" id="form_inserta">
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
                                            <td colspan="2" class="label2"><div align="center">Información General:</div></td>
                                        </tr>
                                        <tr>
                                            <td width="95" class="label2"><input name="insertar" type="hidden" id="insertar" value="true" /></td>
                                            <td width="355">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td height="28" class="label3">Nombre:</td>
                                          <td><input name="nombre" type="text" class="textbox1" id="nombre" /></td>
                                        </tr>
                                        <tr>
                                            <td height="50" class="label3">Dirección:</td>
                                            <td class="label3"><textarea name="direccion" cols="45" rows="5" class="texboxML" id="direccion"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td height="28" class="label3">Teléfono:</td>
                                          <td height="25" class="label3"><input name="telefono" type="text" class="textbox1" id="telefono" /></td>
                                        </tr>
                                        <tr>
                                            <td height="28" class="label3">Contacto:</td>
                                          <td height="25" class="label3"><input name="contacto" type="text" class="textbox1" id="contacto" /></td>
                                        </tr>
                                        <tr>
                                            <td height="28" class="label3">E-mail:</td>
                                          <td height="25" class="label3"><input name="email" type="text" class="textbox1" id="email" /></td>
                                        </tr>
                                        <tr>
                                            <td height="28" class="label3">Giro:</td>
                                          <td height="25" class="label3"><input name="giro" type="text" class="textbox1" id="giro" /></td>
                                        </tr>
                                </table></td>
                                <td valign="top">&nbsp;</td>
                                <td valign="top"><table width="450" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td colspan="2" class="label2"><div align="center">Datos de Portal:</div></td>
                                        </tr>
                                        <tr>
                                            <td width="115">&nbsp;</td>
                                            <td width="335">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td height="28" class="label3">Color de Fondo:</td>
                                          <td><input name="color_fondo" type="text" class="textbox2" id="color_fondo" onclick="startColorPicker(this)"/></td>
                                        </tr>
                                        <tr>
                                            <td height="28" class="label3">Color de Letra:</td>
                              <td><input name="color_letra" type="text" class="textbox2" id="color_letra" onclick="startColorPicker(this)" />
                                                <div align="right"></div>
                                            </td>
                                        </tr>
                                </table></td>
                                <td valign="top">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><input name="fecha_registro" type="hidden" id="fecha_registro" value="<?php echo date("Y-m-d") ?>" /></td>
                                <td>&nbsp;</td>
                                <td><div align="right">
                                        <input name="button" type="submit" id="button" onclick="MM_validateForm('nombre','','R','telefono','','R','contacto','','R','email','','RisEmail','giro','','R','color_fondo','','R','color_letra','','R','direccion','','R');return document.MM_returnValue" value="Agregar Cliente" />
                                </div></td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                    </form>
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
?>

