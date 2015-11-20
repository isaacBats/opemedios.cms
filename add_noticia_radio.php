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

//creamos un arreglo para mostrar las fuentes de tv
$base->execute_query("SELECT id_fuente, nombre FROM fuente WHERE id_tipo_fuente = 2 AND activo = 1 ORDER BY nombre");
$arreglo_fuentes = array();
while($fuente = $base->get_row_assoc())
{
    $arreglo_fuentes[$fuente['id_fuente']] = $fuente["nombre"];
}

//creamos un arreglo para mostrar el menu tipo de autor
$base->execute_query("SELECT * FROM tipo_autor ORDER BY descripcion");
$arreglo_tipo_autor = array();
while($tipo_autor = $base->get_row_assoc())
{
    $arreglo_tipo_autor[$tipo_autor['id_tipo_autor']] = $tipo_autor["descripcion"];
}

//creamos un arreglo para mostrar el menu sector
$base->execute_query("SELECT id_sector, nombre FROM sector WHERE activo = 1 ORDER BY nombre");
$arreglo_sectores = array();
while($sector = $base->get_row_assoc())
{
    $arreglo_sectores[$sector['id_sector']] = $sector["nombre"];
}

//creamos un arreglo para mostrar el menu genero
$base->execute_query("SELECT * FROM genero ORDER BY descripcion");
$arreglo_generos = array();
while($genero = $base->get_row_assoc())
{
    $arreglo_generos[$genero['id_genero']] = $genero["descripcion"];
}

//creamos un arreglo para mostrar el menu tendencia monitorista
$base->execute_query("SELECT * FROM tendencia");
$arreglo_tendencia = array();
while($tendencia = $base->get_row_assoc())
{
    $arreglo_tendencia[$tendencia['id_tendencia']] = $tendencia["descripcion"];
}

//cerramos conexion
$base->free_result();
$base->close();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Agregar Noticia de Radio - OPM</title>
<style type="text/css">
<!--
@import url("CSS/opemedios.css");
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-image: url(images/noticia_bg.jpg);
}
-->
</style>
	<script type="text/javascript" language="javascript" src="ajax_fuentes_secciones_1.js"></script>
    <!-- <script type="text/javascript" src="ckeditor/ckeditor.js"></script> -->
    <script type="text/javascript" language="javascript">
	function ve_fuente(){
                var i = document.getElementById('id_fuente').selectedIndex;
                var nombre_fuente = document.getElementById('id_fuente').options[i].value;
				if (i == 0) {
					alert("Por favor seleccione una fuente para ver la Tabla de Costos");
					return false;
				}
				//alert(nombre_fuente);
				window.open('http://sistema.operamedios.com.mx/consulta_tarifario_radio.php?fuente=' + nombre_fuente, 'tarifas','width=400, height=600');
				return true;
    }
    function seleccion_fuente(){
                var i = document.getElementById('id_fuente').selectedIndex;
                var valor = document.getElementById('id_fuente').options[i].value;
                sndReqCat(valor);
				document.form1.enviar.disabled=true;
    }
	function activa()
	{
		var i = document.getElementById('id_seccion').selectedIndex;
		var valor = document.getElementById('id_seccion').options[i].value;
		//alert(valor);
		if(valor == 0)
		{
			document.form1.enviar.disabled=true;
			//alert(document.form1.enviar.disabled);
		}
		else
		{
			document.form1.enviar.disabled=false;
			//alert(document.form1.enviar.disabled);
		}
	}
		function disable()
	{
	document.form1.enviar.value = 'Cargando Noticia.. Espere'
	document.form1.enviar.disabled = true
	}
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
	var combo1 = document.getElementById("id_tendencia_monitorista");
	if(combo1.value == 0)
		errors+='- Tendencia debe seleccionar una opción.\n';
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' debe contener un valor numérico.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' debe contener un valor.\n'; }
    } if (errors) alert('Favor de atender lo siguiente:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
</script>
</head>

<body>
<table width="505" border="0" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr>
    <td valign="top">
		<table width="500" height="40" border="0" cellpadding="0" cellspacing="0">
		  <tr>
			<td align="center"><img src="images/titulo_addnoticia_radio.png"/></td>
		  </tr>
		</table>
       <form action="action_add_noticia.php" method="post" enctype="multipart/form-data" name="form1" target="_self" id="form1" onsubmit="disable()">
        <table width="500" border="0" cellpadding="1" cellspacing="1">
		  <tr>
            <td width="10">&nbsp;</td>
            <td width="150" class="label1">Fuente:</td>
            <td><select name="id_fuente" class="combo3" id="id_fuente" onchange="seleccion_fuente()">
                                        <option value="0">Selecciona una Fuente</option>
                                        <?php
                                        foreach ($arreglo_fuentes as $value => $label){
                                            echo '<option value="'.$value.'"';  echo'>'.$label.'</option>';
                                        }
                                        ?>
                                </select></td>
            <td width="10">&nbsp;</td>
          </tr>
          <tr>
            <td width="10">&nbsp;</td>
            <td width="150" class="label1">Encabezado:</td>
            <td><input name="encabezado" type="text" class="textbox1" id="encabezado" /></td>
            <td width="10">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td class="label1">Síntesis:</td>
            <td><textarea name="sintesis" rows="2" class="texboxML" id="sintesis"></textarea></td>
            <td>&nbsp;</td>
          </tr>          
		  <tr>
			<td width="10">&nbsp;</td>
			<td width="150" class="label1">Nombre Autor:</td>
			<td><input name="autor" type="text" class="textbox1" id="autor"/></td>
			<td width="10">&nbsp;</td>
		  </tr>		  
		</table>
	<table width="550" border="0" cellpadding="1" cellspacing="1">
              <tr>
                <td width="10">&nbsp;</td>
                <td width="100" class="label1">Tipo de Autor:</td>
                <td width="80">
					<select name="id_tipo_autor" class="combo1" id="id_tipo_autor">
						<?php
						foreach ($arreglo_tipo_autor as $value => $label)
						{
							echo '<option value="'.$value.'"';  echo'>'.$label.'</option>';
						}
						?>
                    </select>
				</td>
                <td width="2">&nbsp;</td>
                <td width="80" class="label1">Sector:</td>
                <td width="80"><select name="id_sector" class="combo1" id="id_sector">
                                        <?php
                                        foreach ($arreglo_sectores as $value => $label)
                                        {
                                            echo '<option value="'.$value.'"';  echo'>'.$label.'</option>';
                                        }
                                        ?>
                                </select> </td>
                <td width="10">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td class="label1">Género:</td>
                <td><select name="id_genero" class="combo1" id="id_genero">
                                        <?php
                                        foreach ($arreglo_generos as $value => $label)
                                        {
                                            echo '<option value="'.$value.'"';  echo'>'.utf8_encode($label).'</option>';
                                        }
                                        ?>
                                </select>               </td>
                <td>&nbsp;</td>
                <td class="label1">Sección:</td>
                <td><div align="left" class="combo1" id="fuente">Selecciona una Fuente</div></td>
                <td>&nbsp;</td>
              </tr>              
              <tr>
                <td>&nbsp;</td>
                <td class="label1">Fecha:</td>
                <td colspan="4"><span class="label2">Dia:</span>
                  <select name="fecha_d" class="combo2" id="fecha_d">
                  <?php
$i = 1;				  
do {  
?>
  <option value="<?php echo $i;?>"<?php if ($i == date("j")) {echo 'selected="selected"';} ?>><?php echo $i;?></option>
  <?php
  $i ++;
} while ($i<=31);
  $rows = mysql_num_rows($autores);
?>
                  </select>
                  <span class="label2">Mes:</span>
                <select name="fecha_m" class="combo2" id="fecha_m">
                <?php
$i = 1;				  
do {  
?>
  <option value="<?php echo $i;?>"<?php if ($i == date("n")) {echo 'selected="selected"';} ?>><?php echo date("M",mktime(0,0,0,$i,1,2008));?></option>
  <?php
  $i ++;
} while ($i<=12);
  $rows = mysql_num_rows($autores);
?>
                </select>
                <span class="label2">Año:</span>                <select name="fecha_y" class="combo2" id="fecha_y">
                <?php
$i = 2000;				  
do {  
?>
  <option value="<?php echo $i;?>"<?php if ($i == date("Y")) {echo 'selected="selected"';} ?>><?php echo $i;?></option>
  <?php
  $i ++;
} while ($i<= date("Y")+1);
  $rows = mysql_num_rows($autores);
?>
                </select></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td class="label1">Hora:</td>
                <td colspan="4"><span class="label2">HH: </span>
    <select name="hora_HH" class="combo2" id="hora_HH">
                                  <?php
$i = 0;				  
do {  
?>
  <option value="<?php echo date("H",mktime($i,0,0,1,1,2000));?>"<?php if ($i == date("H")) {echo 'selected="selected"';} ?>><?php echo date("H",mktime($i,0,0,1,1,2000));?></option>
  <?php
  $i ++;
} while ($i<= 23);
  $rows = mysql_num_rows($autores);
?>
                  </select> 
                  <span class="label2">MM:</span>
                <select name="hora_MM" class="combo2" id="hora_MM">
                                                  <?php
$i = 0;				  
do {  
?>
  <option value="<?php echo date("i",mktime(1,$i,0,1,1,2000));?>"<?php if ($i == date("i")) {echo 'selected="selected"';} ?>><?php echo date("i",mktime(1,$i,0,1,1,2000));?></option>
  <?php
  $i ++;
} while ($i<= 59);
  $rows = mysql_num_rows($autores);
?>
                </select>
                <span class="label2">SS :</span>                <select name="hora_SS" class="combo2" id="hora_SS">
<?php
$i = 0;				  
do {  
?>
  <option value="<?php echo date("s",mktime(1,1,$i,1,1,2000));?>"<?php if ($i == date("s")) {echo 'selected="selected"';} ?>><?php echo date("s",mktime(1,0,$i,1,1,2000));?></option>
  <?php
  $i ++;
} while ($i<= 59);
  $rows = mysql_num_rows($autores);
?>
                </select>                </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td class="label1">Duración:</td>
                <td colspan="4"><span class="label2">HH</span>:
                  <select name="duracion_HH" class="combo2" id="duracion_HH">
                  <?php
$i = 0;				  
do {  
?>
  <option value="<?php echo date("H",mktime($i,0,0,1,1,2000));?>"<?php if ($i == 0) {echo 'selected="selected"';} ?>><?php echo date("H",mktime($i,0,0,1,1,2000));?></option>
  <?php
  $i ++;
} while ($i<= 23);
  $rows = mysql_num_rows($autores);
?>
                  </select> 
                  <span class="label2">MM:</span>
                <select name="duracion_MM" class="combo2" id="duracion_MM">
                <?php
$i = 00;				  
do {  
?>
  <option value="<?php echo date("i",mktime(1,$i,0,1,1,2000));?>"<?php if ($i == 0) {echo 'selected="selected"';} ?>><?php echo date("i",mktime(1,$i,0,1,1,2000));?></option>
  <?php
  $i ++;
} while ($i<= 59);
  $rows = mysql_num_rows($autores);
?>
                </select> 
                <span class="label2">SS :</span>                <select name="duracion_SS" class="combo2" id="duracion_SS">
                <?php
$i = 00;				  
do {  
?>
  <option value="<?php echo date("s",mktime(1,1,$i,1,1,2000));?>"<?php if ($i == 0) {echo 'selected="selected"';} ?>><?php echo date("s",mktime(1,1,$i,1,1,2000));?></option>
  <?php
  $i ++;
} while ($i<= 59);
  $rows = mysql_num_rows($autores);
?>
                </select></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td class="label1">Costo Beneficio:($)</td>
                <td colspan="2"><label for="costo"></label><input name="costo" type="text" class="textbox2" id="costo" /></td>
                <td colspan="3" align="center">&nbsp;<!--<a href="#" onClick="ve_fuente()">Tabla de Costos</a>--></td>
              </tr>              
              <tr>
                <td>&nbsp;</td>
                <td class="label1">Tendencia:</td>
                <td colspan="4">
                  <select name="id_tendencia_monitorista" class="combo1" id="id_tendencia_monitorista">
				  <option value="0">Selecciona una opción</option>
					<?php
					foreach ($arreglo_tendencia as $value => $label){
						echo '<option value="'.$value.'"';  echo'>'.$label.'</option>';
					}
					?>
				  </select>
                </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td class="label1">Comentarios:</td>
                <td colspan="4"><textarea name="comentario" cols="45" rows="4" class="texboxML2" id="comentario"></textarea></td>
                <td>&nbsp;</td>
              </tr>             
              <tr>
                <td>&nbsp;</td>
                <td class="label1">Archivo de Audio:</td>
                <td colspan="4"><input name="archivo" type="file" class="textbox1" id="archivo" size="50" /></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td colspan="4"><input name="secundario" type="checkbox" id="secundario" value="yes" />
                <span class="label2">No poner archivo principal (el archivo indicado anteriormente será archivo secundario)</span></td>
                <td>&nbsp;</td>
              </tr>              
              <tr>                
                <td><input name="insertar" type="hidden" id="insertar" value="true" />
                  <input name="id_tipo_fuente" type="hidden" id="id_tipo_fuente" value="2" />
                  <input name="id_usuario" type="hidden" id="id_usuario" value="<?php echo $current_user->get_id()?>" /></td>
                <td colspan="5">
				  <div align="center">
                  <input name="enviar" type="submit" disabled="disabled" id="enviar" onclick="MM_validateForm('encabezado','','R','autor','','R','costo','','NisNum','sintesis','','R','costo','','R');return document.MM_returnValue" value="Agregar Noticia"/>
                  </div>
				</td>                
                <td>&nbsp;</td>
              </tr>              
            </table>
    </form>    </td>
  </tr>
</table>
</body>
</html>