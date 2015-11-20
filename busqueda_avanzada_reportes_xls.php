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


if($_POST['type']=='cliente'){

$base->execute_query("SELECT id_tema, nombre FROM tema where id_empresa = ".$_POST['row_id']);
$arreglo_temas = array();
while($tema = $base->get_row_assoc())
{
    $arreglo_temas[$tema['id_tema']] = $tema["nombre"];
}
$query = "SELECT * FROM empresa WHERE id_empresa = ".$_POST['row_id'];
$base->execute_query($query);
$empresa = $base->get_row_assoc();
$type_usr = 'Cliente';
}
if($_POST['type']=='monitorista'){
$query = "SELECT Usr.id_usuario, CONCAT(Usr.nombre,' ',Usr.apellidos) AS nombre,
            Usr.id_usuario
            FROM usuario Usr WHERE id_usuario = ".$_POST['row_id'];
$base->execute_query($query);
$usuario = $base->get_row_assoc();
$type_usr = 'Monitorista';
}

//cerramos conexion
$base->free_result();
$base->close();
//echo 'this'.$_POST['row_id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.suaorg/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Parametros de reporte</title>
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
        
<script type="text/javascript" src="InputCalendar/calendarDateInput.js"></script>

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
                          <td width="533" height="25" class="label2">Reportes --&gt; Noticias por <?php echo $type_usr;?> --&gt; <span class="label4">Parámetros de Reporte</span></td>
                          <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                  </tr>
                    </table>
<table width="1000" border="0">
                        
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3"><hr /></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="3" class="label1">Seleccione los Parámetros del Reporte</td>
        </tr>
                    </table>
              <form id="form2" name="form2" method="GET" action="crear_reporte_xls.php">
                <table width="1000" border="0">
                  <tr>
                    <td width="47">&nbsp;</td>
                    <td width="94">&nbsp;</td>
                    <td width="123" class="label3"><div align="right"><?php echo $type_usr; ?>
                      <input name="busqueda" type="hidden" id="busqueda" value="true" />
                    </div></td>
                    <td width="718"><?php echo $empresa['nombre'];echo $usuario['nombre']; ?></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right">Entre:</div></td>
                    <td><script>DateInput("fecha1", true, "YYYY-MM-DD") </script></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right">Y:</div></td>
                    <td><script>DateInput("fecha2", true, "YYYY-MM-DD") </script>
                    <input name="row_id" type="hidden" value="<?php echo $_POST['row_id']; ?>" />
                    <input name="type" type="hidden" value="<?php echo $_POST['type']; ?>" /></td>
                  </tr>
                  <?php if($type_usr == 'Cliente'){ ?>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right">Tema:</div></td>
                    <td><select name="id_tema" class="combo3" id="id_tema">
                      <option value="0">**Todos los Temas**</option>
                      <?php
						foreach ($arreglo_temas as $value => $label)
						{
							echo '<option value="'.$value.'"';  echo'>'.$label.'</option>';
						}
                      ?>
                    </select></td>
                  </tr><?php } ?>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right">Sector:</div></td>
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
                    <td class="label3"><div align="right">Género:</div></td>
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
                    <td class="label3"><div align="right">Tipo de Autor:</div></td>
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
                    <td class="label3"><div align="right">Tendencia:</div></td>
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
                    <td class="label3"><div align="right"></div></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right">Tipo de Fuente:</div></td>
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
                    <td class="label3"><div align="right">Fuente:</div></td>
                    <td><div id="fuente" class="label2">Selecciona un Tipo de Fuente</div></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right">Sección:</div></td>
                    <td><div id="seccion" class="label2">Selecciona una Fuente</div></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right"></div></td>
                    <td><div align="center">
                      <label>
                      <input type="submit" name="buscar2" id="buscar2" value="Generar Reporte" />
                      </label>
                    </div></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right"></div></td>
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