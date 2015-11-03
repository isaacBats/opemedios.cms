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

//creamos un DAO 
//recibe como parametro el resultado de la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();
//obtenemos la informacion del usuario y creamos el objeto
$base->execute_query("SELECT * FROM usuario WHERE username = '".$session_usr."'");// session_usr se encuentra en los inclides de sesion
$current_user = new Usuario($base->get_row_assoc());

//creamos un arreglo para mostrar el menu señal
$base->execute_query("SELECT * FROM senal");
$arreglo_senal = array();
while($senal = $base->get_row_assoc())
{
    $arreglo_senal[$senal['id_senal']] = $senal["descripcion"];
}

//creamos un arreglo para mostrar el menu cobertura
$base->execute_query("SELECT * FROM cobertura");
$arreglo_cobertura = array();
while($cobertura = $base->get_row_assoc())
{
    $arreglo_cobertura[$cobertura['id_cobertura']] = $cobertura["descripcion"];
}

//cerramos conexion
$base->free_result();
$base->close();	
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
                            <td width="533" height="25" class="label2">Fuentes --&gt; Agregar Fuente --&gt; <span class="label4"> Televisión</span></td>
                            <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo(); ?></span></td>
                        </tr>
                    </table>
                    <form action="action_add_fuente_tv.php" method="POST" name="form_inserta" id="form_inserta">
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
                                            <td colspan="2" class="label2"><div align="center"></div></td>
                                        </tr>
                                        <tr>
                                            <td width="95" class="label2"><input name="insertar" type="hidden" id="insertar" value="true" /></td>
                                            <td width="355">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td height="28" class="label3">Nombre:</td>
                                            <td><label>
                                                    <input name="nombre" type="text" class="textbox1" id="nombre" />
                                            </label></td>
                                        </tr>
                                        <tr>
                                            <td height="28" class="label3">Empresa</td>
                                            <td class="label3"><label>
                                                    <input name="empresa" type="text" class="textbox1" id="empresa" />
                                            </label></td>
                                        </tr>
                                        <tr>
                                            <td height="28" class="label3">Conductor:</td>
                                            <td height="25" class="label3"><label>
                                                    <input name="conductor" type="text" class="textbox1" id="conductor" />
                                            </label></td>
                                        </tr>
                                        <tr>
                                            <td height="28" class="label3">Canal:</td>
                                            <td height="25" class="label3"><label>
                                                    <input name="canal" type="text" class="textbox1" id="canal" />
                                            </label></td>
                                        </tr>
                                        <tr>
                                            <td height="28" class="label3">Horario:</td>
                                            <td height="25" class="label3"><label>
                                                    <input name="horario" type="text" class="textbox1" id="horario" />
                                            </label></td>
                                        </tr>
                                        <tr>
                                            <td height="28" class="label3">Señal:</td>
                                            <td height="25" class="label3"><label>
                                                    <select name="id_senal" class="combo3" id="id_senal">
                                                        <?php
                                                        foreach ($arreglo_senal as $value => $label)
                                                        {
                                                            echo '<option value="'.$value.'">'.utf8_encode($label).'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                            </label></td>
                                        </tr>
                                </table></td>
                                <td valign="top">&nbsp;</td>
                                <td valign="top"><table width="450" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td colspan="2" class="label2"><div align="center"></div></td>
                                        </tr>
                                        <tr>
                                            <td width="115">&nbsp;</td>
                                            <td width="335">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td height="28" class="label3">Cobertura:</td>
                                            <td><label>
                                                    <select name="id_cobertura" class="combo3" id="id_cobertura">
                                                        <?php
                                                        foreach ($arreglo_cobertura as $value => $label)
                                                        {
                                                            echo '<option value="'.$value.'">'.utf8_encode($label).'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                            </label></td>
                                        </tr>
                                        <tr>
                                            <td height="50" class="label3">Comentarios:</td>
                                            <td><label>
                                                    <textarea name="comentario" cols="45" rows="5" class="texboxML" id="comentario"></textarea>
                                            </label></td>
                                        </tr>
                                        <tr>
                                            <td height="28" class="label3">Activo:</td>
                                            <td><label>
                                                    <input name="activo" type="checkbox" id="activo" value="1" />
                                            </label></td>
                                        </tr>
                                </table></td>
                                <td valign="top">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><div align="right">
                                        <input name="button" type="submit" id="button" onclick="MM_validateForm('nombre','','R','empresa','','R','conductor','','R','canal','','R','horario','','R');return document.MM_returnValue" value="Agregar Fuente" />
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

