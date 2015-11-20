<?php
//llamamos el codigo de sesion para usuario nivel 2 = encargado de area
include("phpdelegates/rest_access_2.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/PrimeraPlana.php");
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
$base->execute_query("SELECT id_fuente, nombre FROM fuente WHERE activo = 1 AND id_tipo_fuente = 3 ORDER BY nombre");
$arreglo_fuentes = array();
while($fuente = $base->get_row_assoc())
{
    $arreglo_fuentes[$fuente['id_fuente']] = $fuente["nombre"];
}
$query = "SELECT
FU.id_fuente,
PP.id_primera_plana AS id,
PP.fecha, PP.imagen,
FU.nombre as fuente
FROM
primera_plana AS PP,
fuente AS FU
WHERE 1=1 AND PP.id_fuente = FU.id_fuente
AND PP.id_primera_plana = ".$_GET['id_pp'];
$base->execute_query($query);
$row_query = $base->get_row_assoc();
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
                          <td width="533" height="25" class="label2">Prensa --&gt; Primeras Planas --&gt;<span class="label4"> Editar Primera Plana</span></td>
                          <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                  </tr>
                    </table>
<table width="1000" border="0">
                        <tr>
                            <td width="160">&nbsp;</td>
                            <td width="830">&nbsp;</td>
                        </tr>
                        <tr>
                          
                            <td valign="top"><form action="action_add_primera_plana.php" method="post" enctype="multipart/form-data" name="form_insert" id="form_insert">
                              <table width="825" border="0">
                                <tr>
                                  <td width="93">&nbsp;</td>
                                  <td width="172" class="label2">Editar Primera Plana</td>
                                  <td width="440"><div align="right"></div></td>
                                  <?php   echo   '<td width="92" rowspan="5"><img height="200" width="200" src="data/thumbs/'.$row_query['imagen'].'_pp.jpg"></td>'  ?>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label2">&nbsp;</td>
                                  <td><div align="center" class="label5"><?php echo $_GET['mensaje']; ?></div></td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3"><div align="right">Fecha: </div></td>
                                  <td><label>
                                    <script>DateInput('fecha', false, 'YYYY-MM-DD', <?php echo "'".$row_query['fecha']."'"; ?>)</script>
                                  </label></td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3"><div align="right">Fuente:</div></td>
                                  <td><label>
                                    <select name="fuente_id" class="combo3" id="fuente_id">
                                      <option value="0">--------------------------------------------------------------------------------------------------------------</option>
                                      <?php

                                    foreach ($arreglo_fuentes as $value => $label)
                                    {
										echo '<option value="'.$value.'"'; if($row_query['id_fuente']==$value){echo 'selected="selected"';}  echo'>'.$label.'</option>';
                                    }
                                    ?>
                                    </select>
                                  </label></td>
                                </tr>
                                <tr>
                                  <td height="22">&nbsp;</td>
                                  <td class="label3"><div align="right">Imagen: <br />
                                  </div></td>
                                  <td valign="middle"><input colspan="4"  name="imagen" type="file" id="imagen" size = "45" />                                  </td>
                                </tr>
                                <tr>
                                  <td height="22">&nbsp;</td>
                                  <td class="label3">&nbsp;</td>
                                  <td valign="middle">&nbsp;</td>
                                  <td width="96" height="22" class="label3">imagen actual</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                  <td ><input name="button" type="submit" id="button" onclick="MM_validateForm('fuente_id','','R');return document.MM_returnValue" value="Editar" />
                                      <input name="action" type="hidden" id="action" value="edit" />
                                      <input type="hidden"name="row_id"  value="<?php echo $row_query['id'] ?>" id="hiddenField" /></td>
                                  <td><div align="center"></div></td>
                                  <td width="2">&nbsp;</td>
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
        <?php   						$base->free_result();
                                        $base->close();
										include("includes/init_menu_principal.php");?>
    </body>
</html>