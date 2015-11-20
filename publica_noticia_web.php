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
$base->execute_query("SELECT id_fuente, nombre FROM fuente WHERE id_tipo_fuente = 1 AND activo = 1 ORDER BY nombre");
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
        <title>Agregar Noticia Sitio Web - OPM</title>
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
		<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
		<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
		<link href="CSS/opemedios.css" rel="stylesheet" type="text/css" />
		
        <script type="text/javascript" language="javascript" src="ajax_fuentes_secciones_1.js"></script>
        <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
        <script type="text/javascript" language="javascript">
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
	
	function disable(){
		document.form1.enviar.value = 'Cargando Noticia.. Espere';
		document.form1.enviar.disabled = true;
	}


function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
	//x=valida_combo();
	var combo1 = document.getElementById("id_tendencia_monitorista");
	if(combo1.value == 0)
		errors+='- Tendencia debe seleccionar una opción.\n';
    for (i=0; i<(args.length-2); i+=3) { 
	  test=args[i+2]; 
	  val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' solo debe contener números (sin comas ni puntos).\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' debe contener un valor.\n'; }
    } if (errors) alert('Favor de atender lo siguiente:\n'+errors);
    document.MM_returnValue = (errors == '');
} }

<!--
function MM_preloadImages() { //v3.0
                var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
                    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
                        if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
                }
//-->
</script>
    </head>

    <body>
		<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
		  <!--DWLayoutTable-->
		  <tr>
			<td width="1000" height="500" valign="top" background="images/images/BackGround_02bg.jpg">
			<table width="1000" border="0" cellpadding="0" cellspacing="0" background="images/images/BackGround_02.jpg">
			  <tr valign="top">
				<td height="25" colspan="3"><?php include("includes/mainmenu.php");?></td>
			  </tr>
			  <tr valign="middle">
				<td width="15" height="25">&nbsp;</td>
				<td width="533" height="25" class="label2">Noticias --&gt; <span class="label4">Publicacion de Noticias en Sitio Web</span></td>
				<td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
			  </tr>
			</table>
			<br>
			
			<table width="900" border="1">
                        <tr>
                            <td align="center">&nbsp;</td>                           
                        </tr>
                        <tr>                            
                            <td valign="top">
							<form action="crear_nota_sitioweb.php" method="post" enctype="multipart/form-data" name="form_insert" id="form_insert">
                              <table width="500" border="0">
                                <tr>
                                  <td width="50">&nbsp;</td>
                                  <td width="400" class="label2" colspan="2"><span class="label2">Por favor introduce el número de Nota que deseas publicar al Sitio Web operamedios.com.mx</span></td>                                  
                                  <td width="50">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label2">&nbsp;</td>
                                  <td><div align="right" class="label5"><?php echo $_GET['mensaje']; ?></div></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3">Num Nota:</td>
                                  <td><label><input name="id_noticia" type="text" class="textbox" id="nota" /></label></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3">Tipo:</td>
                                  <td>
								  <label>
										<select name="id_tipo_fuente" class="textbox" id="tipo">
										   <option value="1">Televisión</option>
										   <option value="2">Radio</option>
										   <option value="3">Periódico</option>
										   <option value="4">Revista</option>
										   <option value="5">Internet</option>
										</select> 
								  </label>
								  </td>
                                  <td>&nbsp;</td>
                                </tr>                                
                                <tr>
                                  <td>&nbsp;</td>
                                  <td class="label3">&nbsp;</td>
                                  <td><div align="right">
                                    <input name="button" type="submit" id="button" onclick="MM_validateForm('id_noticia','','R');return document.MM_returnValue" value="Publicar Nota" />
                                  </div></td>
                                  <td>&nbsp;</td>
                                </tr>
                              </table>
                              </form>                          
							</td>
                      </tr>                                                
                    </table>
              </td>
            </tr>
        </table>
        <?php include("includes/init_menu_principal.php");?>
    </body>
</html>			