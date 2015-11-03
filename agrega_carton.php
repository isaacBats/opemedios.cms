<?php
//llamamos el codigo de sesion para usuario nivel 2 = encargado de area
include("phpdelegates/rest_access_2.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/Cartones.php");
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

//creamos un arreglo para mostrar las fuentes
$base->execute_query("SELECT id_fuente, nombre FROM fuente WHERE activo = 1 ORDER BY nombre");
$arreglo_sectores = array();
while($fuente = $base->get_row_assoc())
{
    $arreglo_fuentes[$fuente['id_fuente']] = $fuente["nombre"];
}

//cerramos conexion
$base->close();


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
        <script type="text/javascript" src="InputCalendar/calendarDateInput.js"></script>
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
                          <td width="533" height="25" class="label2">Prensa --&gt; Cartones --&gt;<span class="label4"> Agregar</span></td>
                          <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                  </tr>
                    </table>
<table width="1000" border="0">
                        <tr>
                            <td width="160">&nbsp;</td>
                            <td width="830">&nbsp;</td>
                        </tr>
                        <tr>
                            <td valign="top"></td>
                            <td valign="top"><form action="action_add_carton.php" method="post" enctype="multipart/form-data" name="form_insert" id="form_insert">
                              <table width="825" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td width="96">&nbsp;</td>
                                  <td width="157" class="label2"><span class="label2">Agregar Cartones</span></td>
                                  <td width="534"><div align="right"></div></td>
                                  <td width="20">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label2">&nbsp;</td>
                                  <td><div align="center" class="label5"><?php echo $_GET['mensaje']; ?></div></td>
                                  <td>&nbsp;</td>
                                </tr>
                                
                                
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3">Fecha:</td>
                                  <td><label>
                                    <script>DateInput('fecha', true, 'YYYY-MM-DD')</script>
                                  </label></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3">Titulo:</td>
                                  <td><label>
                                  <input type="text" name="titulo"class="combo3" id="titulo" />
                                  </label></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3">Autor:</td>
                                  <td><label>
                                    <input type="text" name="autor"class="combo3" id="autor" />
                                  </label></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3">Fuente:</td>
                                  <td><label>
                                  <select name="fuente_id" class="combo3" id="fuente_id">
                                  <option value="0">-------------------------------------------------------------------------------------------------------------------------------</option>
                                  <?php
                                    foreach ($arreglo_fuentes as $value => $label)
                                    {
                                        echo '<option value="'.$value.'"';  echo'>'.$label.'</option>';
                                    }
                                    ?>
                                  </select>
                                  </label></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td height="22">&nbsp;</td>
                                  <td class="label3">Imagen: <br></td>
                                  <td valign="middle">
                                  <input colspan="4"  name="imagen" type="file" id="imagen" size = "45" />                                  </td>
                                  <td> &nbsp; &nbsp; <br></td>
                                </tr>
                                <tr>
                                   <tr>
                                  <td>&nbsp; <br></td>
                                </tr>
                                  <td>&nbsp; <br></td>
                                  <td >&nbsp;</td>
                                  <td><div align="center">
                                    <input name="action" type="hidden" id="action" value="new" />
                                    <input name="button" type="submit" id="button" onclick="MM_validateForm('fuente_id','','R');return document.MM_returnValue" value="Agregar" />
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
    </body>
</html>