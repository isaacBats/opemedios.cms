<?php
//llamamos el codigo de sesion para usuario nivel 3 = monitorista
include("phpdelegates/rest_access_3.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/Usuario.php");
include("phpclasses/Cuenta.php");

//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");

// esta funcion quita caracteres no aceptados en un query
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType)
    {
        case "text":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
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


//creamos un DAO para obtener los datos de la fuente dada mediante el metodo GET
//recibe como parametro el resultado de la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();

//obtenemos la informacion del usuario y creamos el objeto
$base->execute_query("SELECT * FROM usuario WHERE username = '".$session_usr."'");// session_usr se encuentra en los inclides de sesion
$current_user = new Usuario($base->get_row_assoc());



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


/// Obtenemos las cuentas del cliente

$base->execute_query(sprintf("SELECT * FROM cuenta  WHERE id_empresa = %s AND activo = 1 ORDER BY nombre,apellidos;", GetSQLValueString($_POST['row_id'], "int")));
            $flag = 1;

            if($base->num_rows() <= 0 ) // Si no hay resultados
            {
                $output = '<span class="label1"><strong>El cliente seleccionado no tiene cuentas activas.</strong><br /><br />Para enviar el resumen, el cliente seleccionado  debe tener al menos una cuenta activa</span>';
            }
            else // si hay cuentas
            {
                //metemos las cuentas  en un arreglo
                $arreglo_cuentas = array();
                while($row_cuenta = $base->get_row_assoc())
                {
                    $cuenta = new Cuenta($row_cuenta);
                    $arreglo_cuentas[$cuenta->get_id()]=$cuenta;
                }

               

                    //generamos la salida
                    $new_back = array();

                  
                    $new_back[] .= '<input name="id_empresa" type="hidden" id="id_empresa" value="'.$_POST['row_id'].'" />
                                    <table width="400" border="0">
                                        <tr class="header2">
                                            <td><div align="center">Cuenta</div></td>
                                            <td><div align="center">e-mail</div></td>
                                            <td><div align="center">Enviar</div></td>
                                        </tr>';

                    foreach ($arreglo_cuentas as  $cuenta)
                    {
                        $new_back[] .= '<tr>';
                        $new_back[] .= '<td class="row1"><span class="label1">'.$cuenta->get_nombre_completo().'</span></td>';
                        $new_back[] .= '<td class="row1"><span class="label1">'.$cuenta->get_email().'</span></td>';
                        $new_back[] .= '<td align="center" class="row1"><input type="checkbox" name="envia[]" id="envia_'.$cuenta->get_id().'" value="'.$cuenta->get_id().'" /></td>';
                        $new_back[] .= '</tr>';

                    }

                    $new_back[] .= '</table>
                            <br />';
                    $output = join("", $new_back);

            }// end  "SI hay cuentas"


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
	/*text-align: center;*/
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
                          <td width="533" height="25" class="label2">Noticias --&gt; Envio de Resumen de Noticias--&gt; <span class="label4">Parámetros de Envío</span></td>
                          <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                  </tr>
                    </table>
<table width="1000" border="0">
                        
                        <tr>
                          <td width="36">&nbsp;</td>
                          <td colspan="3"><hr /></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="2" class="label1">Seleccione los Parámetros de Envío</td>
                          <td width="278" class="label1">&nbsp;</td>
        </tr>
                    </table>
              <form id="form2" name="form2" method="post" action="action_envia_resumen_noticias.php">
                <table width="1000" border="0">
                  <tr>
                    <td width="47">&nbsp;</td>
                    <td width="94">&nbsp;</td>
                    <td width="123" class="label3"><div align="right"><?php echo $type_usr; ?>
                      <input name="busqueda" type="hidden" id="busqueda" value="true" />
                    </div></td>
                    <td width="718" class="label1"><?php echo $empresa['nombre'];echo $usuario['nombre']; ?></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">&nbsp;</td>
                    <td class="label1">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">De:</td>
                    <td class="label1">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right">Fecha:</div></td>
                    <td><script>DateInput("fecha", true, "YYYY-MM-DD") </script>
                      </td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right">Hora:</div></td>
                    <td>
                    <span class="label2">HH:</span>
                      <select name="hora_HH1" class="combo2" id="hora_HH">
                        <?php
                                        $i = 0;
                                        do {
                                            ?>
                        <option value="<?php echo date("H",mktime($i,0,0,1,1,2000));?>"<?php if ($i == date("H")) {echo 'selected="selected"';} ?>><?php echo date("H",mktime($i,0,0,1,1,2000));?></option>
                        <?php
                                        $i ++;
                                    } while ($i<= 23);

                                    ?>
                      </select>
                      <span class="label2">MM:</span>
                      <select name="hora_MM1" class="combo2" id="hora_MM">
                        <?php
                                        $i = 0;
                                        do {
                                            ?>
                        <option value="<?php echo date("i",mktime(1,$i,0,1,1,2000));?>"<?php if ($i == date("i")) {echo 'selected="selected"';} ?>><?php echo date("i",mktime(1,$i,0,1,1,2000));?></option>
                        <?php
                                        $i ++;
                                    } while ($i<= 59);
                                    ?>
                      </select>
                      <span class="label2">SS:</span>
                      <select name="hora_SS1" class="combo2" id="hora_SS">
                        <?php
                                        $i = 0;
                                        do {
                                            ?>
                        <option value="<?php echo date("s",mktime(1,1,$i,1,1,2000));?>"<?php if ($i == date("s")) {echo 'selected="selected"';} ?>><?php echo date("s",mktime(1,0,$i,1,1,2000));?></option>
                        <?php
                                        $i ++;
                                    } while ($i<= 59);
                                    ?>
                    </select>
                    </td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">A:</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right">Fecha:</div></td>
                    <td><script>DateInput("fecha2", true, "YYYY-MM-DD") </script></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3"><div align="right">Hora:</div></td>
                    <td><span class="label2">HH:</span>
                      <select name="hora_HH2" class="combo2" id="hora_HH2">
                        <?php
                                        $i = 0;
                                        do {
                                            ?>
                        <option value="<?php echo date("H",mktime($i,0,0,1,1,2000));?>"<?php if ($i == date("H")) {echo 'selected="selected"';} ?>><?php echo date("H",mktime($i,0,0,1,1,2000));?></option>
                        <?php
                                        $i ++;
                                    } while ($i<= 23);

                                    ?>
                      </select>
                      <span class="label2">MM:</span>
                      <select name="hora_MM2" class="combo2" id="hora_MM2">
                        <?php
                                        $i = 0;
                                        do {
                                            ?>
                        <option value="<?php echo date("i",mktime(1,$i,0,1,1,2000));?>"<?php if ($i == date("i")) {echo 'selected="selected"';} ?>><?php echo date("i",mktime(1,$i,0,1,1,2000));?></option>
                        <?php
                                        $i ++;
                                    } while ($i<= 59);
                                    ?>
                      </select>
                      <span class="label2">SS:</span>
                      <select name="hora_SS2" class="combo2" id="hora_SS2">
                        <?php
                                        $i = 0;
                                        do {
                                            ?>
                        <option value="<?php echo date("s",mktime(1,1,$i,1,1,2000));?>"<?php if ($i == date("s")) {echo 'selected="selected"';} ?>><?php echo date("s",mktime(1,0,$i,1,1,2000));?></option>
                        <?php
                                        $i ++;
                                    } while ($i<= 59);
                                    ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">&nbsp;</td>
                    <td><input name="row_id" type="hidden" value="<?php echo $_POST['row_id']; ?>" />
                    <input name="type" type="hidden" value="<?php echo $_POST['type']; ?>" /></td>
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
                  </tr>
                  <?php if($type_usr == 'Cliente'){ ?>
                  <?php } ?>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="label3">&nbsp;</td>
                    <td> <input name="muestra_impresos" type="checkbox" id="muestra_impresos" value="1" />
                    <span class="label2">Mostrar Noticias de Medios Impresos</span></td>
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
                    <td class="label3">&nbsp;</td>
                    <td><input name="tipo_ordenacion" type="radio" id="tipo_ordenacion" value="1" checked="checked" />
                      <span class="label2">Ordenar por Tipo de Fuente</span><br>
                        <input type="radio" name="tipo_ordenacion" id="tipo_ordenacion" value="2" />
                    <span class="label2">Ordenar por Temas</span></td>
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
                    <td class="label3">&nbsp;</td>
                    <td><input type="button" name="btn_previsualizar" id="btn_previsualizar" value="Previsualizar" /></td>
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
                    <td class="label3"><div align="right">Enviar a:</div></td>
                    <td><?php echo $output; ?></td>
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
                    <td class="label3">&nbsp;</td>
                    <td>&nbsp;</td>
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
                    <td class="label3"><div align="right"></div></td>
                    <td><div align="center">
                      <label>
                      <input type="submit" name="buscar2" id="buscar2" value="Enviar Resumen Noticias" />
                      </label>
                    </div></td>
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
                    <td class="label3">&nbsp;</td>
                    <td class="label2">&nbsp;</td>
                  </tr>
                </table>
                  </form>
              </td>
            </tr>
        </table>
        <?php include("includes/init_menu_principal.php");?>
    </body>
</html>