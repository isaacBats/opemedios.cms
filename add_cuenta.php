<?php
//llamamos el codigo de sesion para usuario nivel 2 = encargado de area
include("phpdelegates/rest_access_2.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/Empresa.php");
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
//hacemos consulta para la creacion de un objeto de la clase empresa
$base->execute_query("SELECT * FROM empresa WHERE id_empresa =".$_GET['id_empresa']);
//creamos el objeto empresa con los datos que nos regrese la consulta
$empresa = new Empresa($base->get_row_assoc());

//cerramos conexion
$base->close();

if(isset($_GET['error']) && $_GET['error'] == true)
{
	$datos = $_SESSION['datos_nueva_cuenta'];
}
else
{
	$datos = array();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Agregar Cuenta</title>
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
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' debe contener un valor.\n'; }
    } if (errors) alert('Por favor, Atender lo siguiente:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
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
                          <td width="533" height="25" class="label2">Clientes --&gt; Administrar Clientes --&gt;<span class="label4"> Agregar Cuenta</span></td>
                          <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                  </tr>
                    </table>
<table width="1000" border="0">
                        <tr>
                            <td width="160">&nbsp;</td>
                            <td width="830">&nbsp;</td>
                        </tr>
                        <tr>
                            <td valign="top"><?php include("includes/menu_empresas.php");?></td>
                            <td valign="top"><form action="action_add_cuenta.php" method="post" enctype="multipart/form-data" name="form_insert" id="form_insert">
                              <table width="825" border="0">
                                <tr>
                                  <td width="96">&nbsp;</td>
                                  <td width="157" class="label2"><span class="label2">Agrega Nueva Cuenta:</span></td>
                                  <td width="534"><div align="right"><span class="label4"><?php echo $empresa->get_nombre(); ?></span></div></td>
                                  <td width="20">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label2">&nbsp;</td>
                                  <td><div align="right" class="label5"><?php echo $_GET['mensaje']; ?></div></td>
                                  <td>&nbsp;</td>
                                </tr>
                                
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3">Nombre:</td>
                                  <td><label>
                                    <input name="nombre" type="text" class="textbox1" id="nombre" value="<?php echo $datos["nombre"]; ?>" />
                                  </label></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3">Apellidos:</td>
                                  <td><label>
                                    <input name="apellidos" type="text" class="textbox1" id="apellidos" value="<?php echo $datos["apellidos"]; ?>" />
                                  </label></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3">Cargo:</td>
                                  <td><label>
                                    <input name="cargo" type="text" class="textbox1" id="cargo" value="<?php echo $datos["cargo"]; ?>" />
                                  </label></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3"><label>Teléfono1:</label></td>
                                  <td><label>
                                    <input name="telefono1" type="text" class="textbox1" id="telefono1" value="<?php echo $datos["telefono1"]; ?>" />
                                  </label></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td><span class="label2">
                                    <input name="id_empresa" type="hidden" id="id_empresa" value="<?php echo $empresa->get_id(); ?>" />
                                  </span></td>
                                  <td class="label3">Teléfono2:</td>
                                  <td><label>
                                    <input name="telefono2" type="text" class="textbox1" id="telefono2" value="<?php echo $datos["telefono2"]; ?>" />
                                  </label></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3">Email:</td>
                                  <td><label>
                                    <input name="email" type="text" class="textbox1" id="email" value="<?php echo $datos["email"]; ?>" />
                                  </label></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3">Comentario:</td>
                                  <td><label>
                                    <textarea name="comentario" cols="45" rows="5" class="texboxML" id="comentario"><?php echo $datos["comentario"]; ?></textarea>
                                  </label></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label2">-&gt; Datos de Portal</td>
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3">Username:</td>
                                  <td><label>
                                    <input name="username" type="text" class="textbox1" id="username" value="<?php echo $datos["username"]; ?>" />
                                  </label></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3">Password:</td>
                                  <td><label>
                                    <input name="password" type="text" class="textbox1" id="password" value="<?php echo $datos["password"]; ?>" />
                                  </label></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3">Activo:</td>
                                  <td><label>
                                    <input name="activo" type="checkbox" id="activo" value="1" />
                                  </label></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3">&nbsp;</td>
                                  <td><div align="right">
                                    <input name="button" type="submit" id="button" onclick="MM_validateForm('nombre','','R','apellidos','','R','cargo','','R','email','','RisEmail','username','','R','password','','R');return document.MM_returnValue" value="Agregar Cuenta" />
                                  </div></td>
                                  <td>&nbsp;</td>
                                </tr>
                              </table>
                                                          </form>                          </td>
                      </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                    </table>
              </td>
            </tr>
        </table>
        <?php include("includes/init_menu_principal.php");?>
        <?php include("includes/init_menu_empresas.php");?>
    </body>
</html>