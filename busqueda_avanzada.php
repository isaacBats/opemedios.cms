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

//creamos un DAO para obtener los datos de la fuente dada mediante el metodo GET
//recibe como parametro el resultado de la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();

//obtenemos la informacion del usuario y creamos el objeto
$base->execute_query("SELECT * FROM usuario WHERE username = '".$session_usr."'");// session_usr se encuentra en los inclides de sesion
$current_user = new Usuario($base->get_row_assoc());

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

//creamos un arreglo para mostrar el menu tipo_fuente
$base->execute_query("SELECT * FROM tipo_fuente");
$arreglo_tipos_fuente = array();
while($tipo_fuente = $base->get_row_assoc())
{
    $arreglo_tipos_fuente[$tipo_fuente['id_tipo_fuente']] = $tipo_fuente["descripcion"];
}



//cerramos conexion
$base->free_result();
$base->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Búsqueda Avanzada</title>
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
//-->
</script>
        <link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />
        
<script type="text/javascript" src="InputCalendar/calendarDateInput.js">

/***********************************************
* Jason's Date Input Calendar- By Jason Moon http://calendar.moonscript.com/dateinput.cfm
* Script featured on and available at http://www.dynamicdrive.com
* Keep this notice intact for use.
***********************************************/

</script>

<script type="text/javascript" language="javascript" src="ajax_tipos_fuente_fuentes.js"></script>
<script type="text/javascript" language="javascript" src="ajax_fuentes_secciones_busq.js"></script>

<script type="text/javascript" language="javascript">
function seleccion_tipo_fuente()
	{
		var i = document.getElementById('id_tipo_fuente').selectedIndex;
		var valor = document.getElementById('id_tipo_fuente').options[i].value;
		sndReqCat(valor);
		limpia_secciones();
	}
	function seleccion_fuente()
	{
		var i = document.getElementById('id_fuente').selectedIndex;
		var valor = document.getElementById('id_fuente').options[i].value;
		sndReqCat2(valor);
	}
	function limpia_secciones() {
		// Detect Browser

		id = parseInt('seccion');
		var IE = (document.all) ? 1 : 0;
		var DOM = 0;
		if (parseInt(navigator.appVersion) >=5) {DOM=1};

		// Grab the content from the requested "div" and show it in the "container"
		if (DOM) {
			var viewer = document.getElementById('seccion');
			//alert(viewer.innerHTML );
			viewer.innerHTML = 'Selecciona una Fuente';
		}  else if(IE) {
			document.all['seccion'].innerHTML = 'Selecciona una Fuente';
		}
	}
	
	function muestra_evitar() {
		// Detect Browser

		var IE = (document.all) ? 1 : 0;
		var DOM = 0;
		if (parseInt(navigator.appVersion) >=5) {DOM=1};

		// Grab the content from the requested "div" and show it in the "container"
		if (DOM) {
			var viewer1 = document.getElementById('evitar_label');
			var viewer2 = document.getElementById('evitar_input');
			//alert(viewer.innerHTML );
			viewer1.innerHTML = 'Evitar:';
			viewer2.innerHTML = '<input name="txt_evitar" type="text" class="textbox1" id="txt_evitar" /><span class="label2">**Las noticias que contengan estas palabras no se mostraran</span>';
		}  else if(IE) {
			document.all['evitar_label'].innerHTML = 'Evitar:';
			document.all['evitar_input'].innerHTML = '<input name="txt_evitar" type="text" class="textbox1" id="txt_evitar" /><span class="label2">**Las noticias que contengan estas palabras no se mostraran</span>';
		}
	}
	
	function limpia_evitar() {
		// Detect Browser

		var IE = (document.all) ? 1 : 0;
		var DOM = 0;
		if (parseInt(navigator.appVersion) >=5) {DOM=1};

		// Grab the content from the requested "div" and show it in the "container"
		if (DOM) {
			var viewer1 = document.getElementById('evitar_label');
			var viewer2 = document.getElementById('evitar_input');
			//alert(viewer.innerHTML );
			viewer1.innerHTML = '';
			viewer2.innerHTML = '';
		}  else if(IE) {
			document.all['evitar_label'].innerHTML = '';
			document.all['evitar_input'].innerHTML = '';
		}
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
</script>


        
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
                          <td width="533" height="25" class="label2">Noticias --&gt; <span class="label4">Búsqueda Avanzada</span></td>
                          <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                  </tr>
                    </table>
<table width="1000" border="0">
                        <tr>
                            <td width="47">&nbsp;</td>
                            <td width="94">&nbsp;</td>
                            <td width="845" colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td valign="top">&nbsp;</td>
                            <td colspan="3" valign="top" class="label4">Búsqueda por Número de Noticia</td>
        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td colspan="2"><form id="form1" name="form1" method="post" action="action_busqueda_avanzada_num.php">
                                                          <span class="label2"># Noticia:</span>
  <label>
                                                          <input name="id_noticia" type="text" class="label5" id="id_noticia" />
                                                          </label>
                                                          <label>
                                                          <input name="buscar1" type="submit" id="buscar1" onclick="MM_validateForm('id_noticia','','RisNum');return document.MM_returnValue" value="Buscar &gt;&gt;" />
                              </label>
                                                          <input name="busqueda" type="hidden" id="busqueda" value="true" />
                                                          <span class="label5"><?php echo $_GET['mensaje']; ?>                                                          </span>
                            </form>                            </td>
        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3"><hr /></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label4">Búsqueda Avanzada</td>
                        </tr>
                    </table>
              <form id="form2" name="form2" method="get" action="resultados_busqueda.php">
                <table width="1000" border="0">
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><input name="busqueda" type="hidden" id="busqueda" value="true" /></td>
                    <td><p>
                      <label>
                        <input name="tipo_busqueda" type="radio" id="buscar_0" value="1" checked="checked" onclick="limpia_evitar()" />
                        <span class="label1">Frase Completa</span></label> 
                      <label>
                        <input name="tipo_busqueda" type="radio" id="buscar_1" value="2" onclick="muestra_evitar()" />
                        <span class="label1">Palabras Clave</span></label>
                      <br />
                    </p></td>
                  </tr>
                  <tr>
                    <td width="47">&nbsp;</td>
                    <td width="94">&nbsp;</td>
                    <td width="123" class="label3">Buscar:</td>
                  <td width="718"><label>
                      <input name="txt_buscar" type="text" class="textbox1" id="txt_buscar" />
                    </label></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div id="evitar_label" ></div></td>
                    <td><label>
                      <div id="evitar_input">
                      </div>
                    </label></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">Entre:</td>
                    <td><script>DateInput('fecha1', true, 'DD-MON-YYYY')</script></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">Y:</td>
                    <td><script>DateInput('fecha2', true, 'DD-MON-YYYY')</script></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">Sector:</td>
                    <td><label>
                      <select name="id_sector" class="combo3" id="id_sector">
                      <option value="0">**Todos los Sectores**</option>
                      <?php
						foreach ($arreglo_sectores as $value => $label)
						{
							echo '<option value="'.$value.'"';  echo'>'.$label.'</option>';
						}
						?>
                      </select>
                    </label></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">Género:</td>
                    <td><label>
                      <select name="id_genero" class="combo3" id="id_genero">
                      <option value="0">**Todos los Géneros**</option>
                      <?php
						foreach ($arreglo_generos as $value => $label)
						{
							echo '<option value="'.$value.'"';  echo'>'.utf8_encode($label).'</option>';
						}
						?>
                    </select>
                    </label></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">Tipo de Autor:</td>
                    <td><label>
                      <select name="id_tipo_autor" class="combo3" id="id_tipo_autor">
                      <option value="0">**Todos los Tipos de Autor**</option>
                      <?php
							foreach ($arreglo_tipo_autor as $value => $label)
							{
								echo '<option value="'.$value.'"';  echo'>'.utf8_encode($label).'</option>';
							}
							?>
                    </select>
                    </label></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">Tendencia:</td>
                    <td><label>
                      <select name="id_tendencia_monitorista" class="combo3" id="id_tendencia_monitorista">
                      <option value="0">**Todas las Tendencias**</option>
                      <?php
							foreach ($arreglo_tendencia as $value => $label)
							{
								echo '<option value="'.$value.'"';  echo'>'.utf8_encode($label).'</option>';
							}
							?>
                    </select>
                    </label></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">Tipo de Fuente:</td>
                    <td><label>
                      <select name="id_tipo_fuente" class="combo3" id="id_tipo_fuente" onchange="seleccion_tipo_fuente()">
                      <option value="0">**Todos los Tipos de Fuente**</option>
                      <?php
							foreach ($arreglo_tipos_fuente as $value => $label)
							{
								echo '<option value="'.$value.'"';  echo'>'.utf8_encode($label).'</option>';
							}
							?>
                    </select>
                    </label></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">Fuente:</td>
                    <td><div id="fuente" class="label2">Selecciona un Tipo de Fuente</div></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">Sección:</td>
                    <td><div id="seccion" class="label2">Selecciona una Fuente</div></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">&nbsp;</td>
                    <td><div align="center">
                      <label>
                      <input type="submit" name="buscar2" id="buscar2" value="Buscar &gt;&gt;" />
                      </label>
                    </div></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
                  </form>
              </td>
            </tr>
        </table>
        <?php include("includes/init_menu_principal.php");?>
    </body>
</html>