<?php
//llamamos el codigo de sesion para usuario nivel 3 = monitorista
include("phpdelegates/rest_access_3.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/Noticia.php");
include("phpclasses/NoticiaElectronico.php");
include("phpclasses/Usuario.php");

//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");

//creamos DAO
//recibe como parametro el resultado de la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();
//obtenemos la informacion del usuario y creamos el objeto
$base->execute_query("SELECT * FROM usuario WHERE username = '".$session_usr."'");// session_usr se encuentra en los includes de sesion
$current_user = new Usuario($base->get_row_assoc());

//hacemos consulta para la creacion del objeto NoticiaElectronico usando como atributo la variable GET id_noticia

$tabla_tipo = "";
if($_GET['id_tipo_fuente'] == 1)
{
    $tabla_tipo = "noticia_tel";
}
if($_GET['id_tipo_fuente'] == 2)
{
    $tabla_tipo = "noticia_rad";
}


//hacemos consulta para la creacion del objeto NoticiaElectronico
$base->execute_query("SELECT 
                          noticia.id_noticia AS id_noticia,
                          noticia.encabezado AS encabezado,
                          noticia.sintesis AS sintesis,
                          noticia.autor AS autor,
                          noticia.fecha AS fecha,
                          noticia.comentario AS comentario,
                          noticia.alcanse AS alcanse,
                          noticia.id_tipo_fuente AS id_tipo_fuente,
                          noticia.id_fuente AS id_fuente,
                          noticia.id_seccion AS id_seccion,
                          noticia.id_sector AS id_sector,
                          noticia.id_tipo_autor AS id_tipo_autor,
                          noticia.id_genero AS id_genero,
                          noticia.id_tendencia_monitorista AS id_tendencia_monitorista,
                          noticia.id_usuario AS id_usuario,
                          fuente.nombre AS fuente,
                          seccion.nombre AS seccion,
                          sector.nombre AS sector,
                          tipo_fuente.descripcion AS tipo_fuente,
                          tipo_autor.descripcion AS tipo_autor,
                          genero.descripcion AS genero,
                          tendencia.descripcion AS tendencia_monitorista,
                          ".$tabla_tipo.".hora AS hora,
                          ".$tabla_tipo.".duracion AS duracion,
						  ".$tabla_tipo.".costo AS costo
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                         INNER JOIN ".$tabla_tipo." ON (noticia.id_noticia=".$tabla_tipo.".id_noticia)
                    WHERE
                         noticia.id_noticia =".$_GET['id_noticia'].";");

//creamos el objeto NoticiaElectronico con los datos que nos regrese la consulta
$noticia = new NoticiaElectronico($base->get_row_assoc());

//creamos un arreglo para mostrar las fuentes de tv
$base->execute_query("SELECT id_fuente, nombre FROM fuente WHERE id_tipo_fuente = ".$noticia->getId_tipo_fuente()." AND activo = 1 ORDER BY nombre");
$arreglo_fuentes = array();
while($fuente = $base->get_row_assoc())
{
    $arreglo_fuentes[$fuente['id_fuente']] = $fuente["nombre"];
}

//creamos un arreglo para mostrar las secciones de la fuente seleccionada inicialmente
$base->execute_query("SELECT id_seccion, nombre FROM seccion WHERE id_fuente = ".$noticia->getId_fuente()." AND activo = 1 ORDER BY descripcion");
$arreglo_secciones = array();
while($seccion = $base->get_row_assoc())
{
    $arreglo_secciones[$seccion['id_seccion']] = $seccion["nombre"];
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


//vamos a crear variables que contengan los valores de fecha hora y duracion con el fin de seleccionar los que correspondan a la noticia

$hora = intval(date("G",mktime(substr($noticia->getHora(),0,2),0,0,1,1,2000)));
$minuto  = intval(date("i",mktime(0,substr($noticia->getHora(),3,2),0,1,1,2000)));
$segundo = intval(date("s",mktime(0,0,substr($noticia->getHora(),6,2),1,1,2000)));
$dia = intval(date("j",mktime(0,0,0,1,substr($noticia->getFecha(),8,2),2000)));
$mes = intval(date("n",mktime(0,0,0,substr($noticia->getFecha(),5,2),1,2000)));
$año = intval(date("Y",mktime(0,0,0,1,1,substr($noticia->getFecha(),0,4))));
$dura_hora = intval(date("G",mktime(substr($noticia->getDuracion(),0,2),0,0,1,1,2000)));
$dura_minuto = intval(date("i",mktime(0,substr($noticia->getDuracion(),3,2),0,1,1,2000)));
$dura_segundo = intval(date("s",mktime(0,0,substr($noticia->getDuracion(),6,2),1,1,2000)));

//echo "Hora: ".$noticia->getHora();
//echo "<br>Fecha: ".$noticia->getFecha();
//echo "<br> Duracion: ".$noticia->getDuracion();
//echo "<br> variables:
//      <br> hora: ".$hora.
//      "<br> minuto: ".$minuto.
//      "<br> segundo: ".$segundo.
//      "<br> dia: ".$dia.
//      "<br> mes: ".$mes.
//      "<br> año: ".$año.
//      "<br> dura_hora: ".$dura_hora.
//      "<br> dura_minuto: ".$dura_minuto.
//      "<br> dura_segundo: ".$dura_segundo;
//
//exit();


//cerramos conexion
$base->free_result();
$base->close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Editar Noticia Electronica</title>
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
        <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
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
        <script type="text/javascript" language="javascript" src="colorpicker/js/colorPicker.js"></script>
        <link rel="stylesheet" href="colorpicker/css/colorPicker.css" type="text/css"></link>
        <script type="text/javascript" language="javascript" src="ajax_fuentes_secciones_edit_noticia.js"></script>
        <script type="text/javascript">
		function seleccion_fuente()
            {
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
                            <td width="533" height="25" class="label2">Noticias --&gt; <span class="label4">Editar Noticia de <?php if($noticia->getId_tipo_fuente()==1){echo "Televisión";}else{echo "Radio";}?></span></td>
                            <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                        </tr>
                    </table>
                    <table width="1000" border="0">
                        <tr>
                            <td width="160">&nbsp;</td>
                            <td width="830">&nbsp;</td>
                        </tr>
                        <tr>
                            <td valign="top"><?php include("includes/menu_noticias.php");?></td>
                            <td valign="top"><form id="form1" name="form1" method="post" action="action_edit_noticia_electronico.php">
      <table width="825" border="0">
                                        <tr>
                                            <td width="112"><input name="id_noticia" type="hidden" id="id_noticia" value="<?php echo $noticia->getId();?>" />
                                            <input type="hidden" name="id_tipo_fuente" id="id_tipo_fuente" value="<?php echo $noticia->getId_tipo_fuente();?>"/></td>
                                            <td width="148" height="28" class="label3">Encabezado:</td>
                                            <td width="441"><label></label>
                                                <label>
                                                    <input name="encabezado" type="text" class="textbox1" id="encabezado" value="<?php echo $noticia->getEncabezado();?>" />
                                            </label></td>
                                            <td width="106">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td height="180" class="label3">Síntesis:</td>
                                      <td><label>
                                                    <textarea name="sintesis" cols="45" rows="5" class="texboxML2" id="sintesis"><?php echo $noticia->getSintesis();?></textarea>
                                            </label></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td height="28" class="label3">Fuente:</td>
                                            <td><label>
                                                    <select name="id_fuente" class="combo3" id="id_fuente" onchange="seleccion_fuente()">
                                                        <?php
                                                        foreach ($arreglo_fuentes as $value => $label)
                                                        {
                                                            echo '<option value="'.$value.'"'; if($value == $noticia->getId_fuente()){echo 'selected="selected"';}  echo'>'.$label.'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                            </label></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td height="28" class="label3">Sección:</td>
                                            <td>
                                            <div id="seccion"><label>
                                                    <select name="id_seccion" class="combo3" id="id_seccion">
                                                        <?php
                                                        foreach ($arreglo_secciones as $value => $label)
                                                        {
                                                            echo '<option value="'.$value.'"'; if($value == $noticia->getId_seccion()){echo 'selected="selected"';}  echo'>'.$label.'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                          </label></div></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td height="28" class="label3">Nombre Autor:</td>
                                            <td><label>
                                                    <input name="autor" type="text" class="textbox1" id="autor" value="<?php echo $noticia->getAutor();?>"/>
                                            </label></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td height="28" class="label3">Tipo de Autor:</td>
                                            <td><label>
                                                    <select name="id_tipo_autor" class="combo3" id="id_tipo_autor">
                                                        <?php
                                                        foreach ($arreglo_tipo_autor as $value => $label)
                                                        {
                                                            echo '<option value="'.$value.'"'; if($value == $noticia->getId_tipo_autor()){echo 'selected="selected"';}  echo'>'.utf8_encode($label).'</option>';
                                                        }
                                                        ?>
                                            </select>
                                            </label></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td height="28" class="label3">Género:</td>
                                            <td><label>
                                                    <select name="id_genero" class="combo3" id="id_genero">
                                                        <?php
                                                        foreach ($arreglo_generos as $value => $label)
                                                        {
                                                            echo '<option value="'.$value.'"'; if($value == $noticia->getId_genero()){echo 'selected="selected"';}  echo'>'.$label.'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                            </label></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td class="label3">Sector:</td>
                                            <td><label>
                                                    <select name="id_sector" class="combo3" id="id_sector">
                                                        <?php
                                                        foreach ($arreglo_sectores as $value => $label)
                                                        {
                                                            echo '<option value="'.$value.'"'; if($value == $noticia->getId_sector()){echo 'selected="selected"';}  echo'>'.$label.'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                            </label></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td height="28" class="label3">Fecha:</td>
                                            <td><label><span class="label2">Día:</span>
                                                    <select name="fecha_dd" class="combo2" id="fecha_dd">
                                                        <?php
                                                        $i = 1;
                                                        do {
                                                            ?>
                                                        <option value="<?php echo $i;?>"<?php if ($i == $dia) {echo 'selected="selected"';} ?>><?php echo $i;?></option>
                                                        <?php
                                                        $i ++;
                                                    } while ($i<=31);
                                                    ?>
                                                    </select>
                                                    <span class="label2">Mes:</span>
                                                    <select name="fecha_mm" class="combo2" id="fecha_mm">
                                                        <?php
                                                        $i = 1;
                                                        do {
                                                            ?>
                                                        <option value="<?php echo $i;?>"<?php if ($i == $mes) {echo 'selected="selected"';} ?>><?php echo date("M",mktime(0,0,0,$i,1,2008));?></option>
                                                        <?php
                                                        $i ++;
                                                    } while ($i<=12);
                                                    ?>
                                                    </select>
                                                    <span class="label2">Año:</span>
                                                    <select name="fecha_yy" class="combo2" id="fecha_yy">
                                                        <?php
                                                        $i = 2000;
                                                        do {
                                                            ?>
                                                        <option value="<?php echo $i;?>"<?php if ($i == $año) {echo 'selected="selected"';} ?>><?php echo $i;?></option>
                                                        <?php
                                                        $i ++;
                                                    } while ($i<= date("Y")+1);
                                                    ?>
                                                    </select>
                                            </label></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td height="28" class="label3">Hora:</td>
                                            <td><span class="label2">HH:</span>
                                                <label>
                                                    <select name="hora_hh" class="combo2" id="hora_hh">
                                                        <?php
                                                        $i = 0;
                                                        do {
                                                            ?>
                                                        <option value="<?php echo date("H",mktime($i,0,0,1,1,2000));?>"<?php if ($i == $hora) {echo 'selected="selected"';} ?>><?php echo date("H",mktime($i,0,0,1,1,2000));?></option>
                                                        <?php
                                                        $i ++;
                                                    } while ($i<= 23);

                                                    ?>
                                                    </select>
                                                    <span class="label2">MM:</span>
                                                    <select name="hora_mm" class="combo2" id="hora_mm">
                                                        <?php
                                                        $i = 0;
                                                        do {
                                                            ?>
                                                        <option value="<?php echo date("i",mktime(1,$i,0,1,1,2000));?>"<?php if ($i == $minuto) {echo 'selected="selected"';} ?>><?php echo date("i",mktime(1,$i,0,1,1,2000));?></option>
                                                        <?php
                                                        $i ++;
                                                    } while ($i<= 59);
                                                    ?>
                                                    </select>
                                                    <span class="label2">SS:</span>
                                                    <select name="hora_ss" class="combo2" id="hora_ss">
                                                        <?php
                                                        $i = 0;
                                                        do {
                                                            ?>
                                                        <option value="<?php echo date("s",mktime(1,1,$i,1,1,2000));?>"<?php if ($i == $segundo) {echo 'selected="selected"';} ?>><?php echo date("s",mktime(1,0,$i,1,1,2000));?></option>
                                                        <?php
                                                        $i ++;
                                                    } while ($i<= 59);
                                                    ?>
                                                    </select>
                                                </label>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td height="28" class="label3">Duración:</td>
                                            <td><span class="label2">HH:</span>
                                                <label>
                                                    <select name="duracion_hh" class="combo2" id="duracion_hh">
                                                    <?php
                                                        $i = 0;
                                                        do {
                                                            ?>
                                                        <option value="<?php echo date("H",mktime($i,0,0,1,1,2000));?>"<?php if ($i == $dura_hora) {echo 'selected="selected"';} ?>><?php echo date("H",mktime($i,0,0,1,1,2000));?></option>
                                                        <?php
                                                        $i ++;
                                                    } while ($i<= 23);

                                                    ?>
                                                    </select>
                                                    <span class="label2">MM:</span>
                                                    <select name="duracion_mm" class="combo2" id="duracion_mm">
                                                        <?php
                                                        $i = 0;
                                                        do {
                                                            ?>
                                                        <option value="<?php echo date("i",mktime(1,$i,0,1,1,2000));?>"<?php if ($i == $dura_minuto) {echo 'selected="selected"';} ?>><?php echo date("i",mktime(1,$i,0,1,1,2000));?></option>
                                                        <?php
                                                        $i ++;
                                                    } while ($i<= 59);
                                                    ?>
                                                    </select>
                                                    <span class="label2">SS:</span>
                                                    <select name="duracion_ss" class="combo2" id="duracion_ss">
                                                    <?php
                                                        $i = 0;
                                                        do {
                                                            ?>
                                                        <option value="<?php echo date("s",mktime(1,1,$i,1,1,2000));?>"<?php if ($i == $dura_segundo) {echo 'selected="selected"';} ?>><?php echo date("s",mktime(1,0,$i,1,1,2000));?></option>
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
                                          <td height="28" class="label3">Costo Beneficio:($)</td>
                                          <td><label for="costo"></label>
                                          <input name="costo" type="text" class="textbox1" id="costo" value="<?php echo $noticia->getCosto() ?>" /></td>
                                          <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td>&nbsp;</td>
                                          <td height="28" class="label3">Alcance:</td>
                                          <td><label for="alcanse"></label>
                                          <input name="alcanse" type="text" class="textbox1" id="alcanse" value="<?php echo $noticia->getAlcanse() ?>" /></td>
                                          <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td height="28" class="label3">Tendencia:</td>
                                            <td><label>
                                                    <select name="id_tendencia_monitorista" class="combo3" id="id_tendencia_monitorista">
                                                        <?php
                                                        foreach ($arreglo_tendencia as $value => $label)
                                                        {
                                                            echo '<option value="'.$value.'"'; if($value == $noticia->getId_tendencia_monitorista()){echo 'selected="selected"';}  echo'>'.$label.'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                            </label></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td height="28" class="label3">Comentarios:</td>
                                            <td><label>
                                                    <textarea name="comentario" cols="45" rows="5" class="texboxML2" id="comentario"><?php echo $noticia->getComentario();?></textarea>
                                            </label></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td height="28" class="label3">&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td class="label3">&nbsp;</td>
                                            <td><div align="right">
                                                    <label>
                                                        <input type="submit" name="enviar" id="enviar" value="Modificar Informacion" />
                                                    </label>
                                            </div></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </table>
                          </form>                            </td>
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