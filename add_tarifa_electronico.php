<?php
//llamamos el codigo de sesion para usuario nivel 3
include("phpdelegates/rest_access_3.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/Fuente.php");
include("phpclasses/Usuario.php");
include("phpclasses/Horario.php");

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
//hacemos consulta para la creacion de los objetos fuente
$base->execute_query("SELECT * FROM fuente WHERE id_fuente =".$_GET['id_fuente']);
//creamos el objeto fuente con los datos que nos regrese la consulta
$fuente = new Fuente($base->get_row_assoc());

// hacemos el arreglo de los horarios a desplegar en el menú
$arreglo_horarios = array();
$base->execute_query("SELECT * FROM horario");
while($hora = $base->get_row_assoc())
{
	$horario = new Horario($hora);
    $arreglo_horarios[$horario->get_id()] = $horario;
}

//hacemos arreglo para mostrar meses
$arreglo_meses = array();
$base->execute_query("SELECT * from mes");
while($mes = $base->get_row_assoc())
{
	$arreglo_meses[$mes['id_mes']]=$mes['descripcion'];
}

//cerramos conexion
$base->free_result();
$base->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Ver Cliente</title>
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
                          <td width="533" height="25" class="label2">Fuentes --&gt; Administrar Fuentes --&gt; <span class="label4">Agrega Tarifa</span></td>
                          <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                  </tr>
                    </table>
<table width="1000" border="0">
                        <tr>
                            <td width="160">&nbsp;</td>
                            <td width="830">&nbsp;</td>
                        </tr>
                        <tr>
                            <td valign="top"><?php include("includes/menu_fuentes.php");?></td>
                            <td valign="top"><table width="825" border="0">
                              <tr>
                                <td width="30">&nbsp;</td>
                                <td width="355"><div align="center"><span class="label4"><?php echo $fuente->get_nombre(); ?></span></div></td>
                                <td width="400"><div align="center"><img src="<?php echo "data/fuentes/".$fuente->get_logo(); ?>" alt="" name="logo" width="127" height="80" id="logo" /></div></td>
                                <td width="22">&nbsp;</td>
                              </tr>
                              
                              
                            </table>
                              <form id="form_add_tarifa" name="form_add_tarifa" method="post" action="action_add_tarifa_electronico.php">
                                <table width="825" border="0">
                                  <tr>
                                    <td width="35">&nbsp;</td>
                                    <td width="171" class="label2">Agregar Nueva Tarifa:</td>
                                    <td width="359">&nbsp;</td>
                                    <td width="242">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td><input name="id_fuente" type="hidden" id="id_fuente" value="<?php echo $fuente->get_id();?>" /></td>
                                    <td class="label3">Horario:</td>
                                    <td><label>
                                      <select name="id_horario" class="combo3" id="id_horario">
							            <?php
                                        foreach ($arreglo_horarios as $value => $hora)
                                        {
                                            echo '<option value="'.$value.'">'.utf8_encode($hora->get_hora_inicio()).' hrs  a  '.utf8_encode($hora->get_hora_final()).' hrs </option>';
                                        }
                                        ?>
                                    </select>
                                    </label></td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td><input name="id_tipo_fuente" type="hidden" id="id_tipo_fuente" value="<?php echo $fuente->get_id_tipo_fuente();?>" /></td>
                                    <td class="label3">Mes:</td>
                                    <td><label>
                                      <select name="id_mes" class="combo3" id="id_mes">
                                      <?php
                                        foreach ($arreglo_meses as $value => $label)
                                        {
                                            echo '<option value="'.$value.'">'.utf8_encode($label).'</option>';
                                        }
                                      ?>
                                    </select>
                                    </label></td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td class="label3">Tiempo:</td>
                                    <td><label>HH:
                                      <select name="tiempo_hh" class="combo2" id="tiempo_hh">
											<?php
                                            $i = 0;				  
                                            do {  
                                            ?>
                                              <option value="<?php echo date("H",mktime($i,0,0,1,1,2000));?>"<?php if ($i == 0) {echo 'selected="selected"';} ?>><?php echo date("H",mktime($i,0,0,1,1,2000));?></option>
                                              <?php
                                              $i ++;
                                            } while ($i<= 10);
                                            ?>
                                      </select>
                                    MM:
                                    <select name="tiempo_mm" class="combo2" id="tiempo_mm">
                                    	   <?php
                                            $i = 0;				  
                                            do {  
                                            ?>
                                              <option value="<?php echo date("i",mktime(0,$i,0,1,1,2000));?>"<?php if ($i == 0) {echo 'selected="selected"';} ?>><?php echo date("i",mktime(0,$i,0,1,1,2000));?></option>
                                              <?php
                                              $i ++;
                                            } while ($i<= 59);
                                            ?>
                                    </select>
                                    SS:
                                    <select name="tiempo_ss" class="combo2" id="tiempo_ss">
                                            <?php
                                            $i = 0;				  
                                            do {  
                                            ?>
                                              <option value="<?php echo date("s",mktime(0,0,$i,1,1,2000));?>"<?php if ($i == 0) {echo 'selected="selected"';} ?>><?php echo date("s",mktime(0,0,$i,1,1,2000));?></option>
                                              <?php
                                              $i ++;
                                            } while ($i<= 59);
                                            ?>
                                    </select>
                                    </label></td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td class="label3">Precio ($) :</td>
                                    <td><label>
                                      <input name="precio" type="text" class="textbox1" id="precio" />
                                    </label></td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td><input name="button" type="submit" id="button" onclick="MM_validateForm('precio','','RisNum');return document.MM_returnValue" value="Agregar Tarifa" /></td>
                                  </tr>
                                </table>
                              </form>
                          </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
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