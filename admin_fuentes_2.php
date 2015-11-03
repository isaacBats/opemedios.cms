<?php
//llamamos el codigo de sesion para usuario nivel 3
include("phpdelegates/rest_access_3.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/Fuente.php");
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

// mandamos a llamar el codigo donde se obtienen los clientes  ya con paginacion
include("phpdelegates/paginacion_fuentes_get.php");

//cerramos conexion
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
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function confirmar_baja(formObj) { 
	if(!confirm("Desea eliminar la Fuente?")) {
		return false;
	} 
	else {	   
	   return true;
	}   
}
//-->
</script>
        <link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />
    </head>

    <body onload="MM_preloadImages('images/images/menusec_02_television2.png','images/images/menusec_02_radio2.png','images/images/menusec2_periodico.png','images/images/menusec2_02_revista.png','images/images/menusec2_02_internet.png')">
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
                <td width="1000" height="500" valign="top" background="images/images/BackGround_02bg.jpg"><table width="1000" border="0" cellpadding="0" cellspacing="0" background="images/images/BackGround_02.jpg">
          <tr valign="top">
                            <td height="25" colspan="3"><?php include("includes/mainmenu.php");?></td>
                  </tr>
                        <tr valign="middle">
                            <td width="15" height="25">&nbsp;</td>
                          <td width="533" height="25" class="label2">Fuentes --&gt; <span class="label4">Administrar Fuentes</span></td>
                          <td width="452" height="25"><span class="label2">Bienvenido: </span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                  </tr>
                    </table>
<table width="1000" border="0">
                        <tr>
                            <td width="160">&nbsp;</td>
                            <td width="830">&nbsp;</td>
                        </tr>
                        <tr>
                            <td valign="top"><form id="form_buscar" name="form_buscar" method="get" action="admin_fuentes.php">
      <table width="152" border="0" align="center" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td height="28" background="images/images/menusec_01_busqueda.png">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td height="55" align="center" valign="middle" background="images/images/menusec_02_empty.png"><label>
                                                    <input name="txt_buscar" type="text" class="textboxsec" id="txt_buscar" />
                                                    <br />
                                                    <input name="btnbuscar" type="submit" class="boton1" id="btnbuscar" value="Buscar" />
                                                    <br />
                                            </label></td>
                                        </tr>

                                        <tr>
                                            <td height="17"><img src="images/images/menusec2_03.png" width="152" height="17" /></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td><img src="images/images/menusec2_01.png" width="152" height="28" /></td>
                                        </tr>
                                        <tr>
                                          <td><a href="admin_fuentes_2.php?id_tipo_fuente=1" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('television','','images/images/menusec_02_television2.png',1)"><img src="images/images/menusec_02_television_1.png" name="television" width="152" height="23" border="0" id="television" /></a></td>
                                        </tr>
                                        <tr>
                                          <td><a href="admin_fuentes_2.php?id_tipo_fuente=2" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('radio','','images/images/menusec_02_radio2.png',1)"><img src="images/images/menusec_02_radio1.png" name="radio" width="152" height="23" border="0" id="radio" /></a></td>
                                        </tr>
                                        <tr>
                                          <td><a href="admin_fuentes_2.php?id_tipo_fuente=3" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('periodico','','images/images/menusec2_periodico.png',1)"><img src="images/images/menusec_02_periodico1.png" name="periodico" width="152" height="23" border="0" id="periodico" /></a></td>
                                        </tr>
                                        <tr>
                                          <td><a href="admin_fuentes_2.php?id_tipo_fuente=4" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('revista','','images/images/menusec2_02_revista.png',1)"><img src="images/images/menusec_02_revista1.png" name="revista" width="152" height="23" border="0" id="revista" /></a></td>
                                        </tr>
                                        <tr>
                                          <td><a href="admin_fuentes_2.php?id_tipo_fuente=5" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('internet','','images/images/menusec2_02_internet.png',1)"><img src="images/images/menusec_02_internet1.png" name="internet" width="152" height="23" border="0" id="internet" /></a></td>
                                        </tr>
                                        <tr>
                                          <td><img src="images/images/menusec2_03.png" width="152" height="17" /></td>
                                        </tr>
                                    </table>
                            </form>          </td>
                            <td valign="top">
                                <?php if ($totalRows > 0) { // muestra solo si si hubo registros ?>
                                <table width="825" border="3" align="center" cellpadding="0" cellspacing="0">
                                    <tr class="header2">
                                        <td width="32"><div align="center">&nbsp;</div></td>
                                      <td><div align="center"><a href="admin_fuentes_2.php?id_tipo_fuente=<?php echo $parametro ?>&orden=0">Nombre</a></div></td>
                                        <td><div align="center"><a href="admin_fuentes_2.php?id_tipo_fuente=<?php echo $parametro ?>&orden=1">Empresa</a></div></td>
                                        <td width="127"><div align="center">Logo</div></td>
                                      <td width="100"><div align="center">&nbsp;</div></td>
                                  </tr>
                                    <?php
                                    foreach ($arreglo_fuentes as  $fe) {
                                        echo'<tr class="label1">';
                                        echo'<td width="32"><img src="images/icons/'.$fe->get_id_tipo_fuente().'.png" width = "32" height="32"></td>';
                                        echo'<td><div align="center">'.$fe->get_nombre().'</div></td>';
                                        echo'<td><div align="center">'.$fe->get_empresa().'</div></td>';
                                        echo'<td width="127"><img src="data/fuentes/'.$fe->get_logo().'"width ="127" height="80"></td>';
                                        echo'<td><div align="center" class="label2"><a href="ver_fuente_selector.php?id_fuente='.$fe->get_id().'&id_tipo_fuente='.$fe->get_id_tipo_fuente().'">Ver o Editar</a><br>
											<a href="borra_fuentes.php?id_fuente='.$fe->get_id().'" onclick="return confirmar_baja(this);">Dar de Baja</a></div></td>';
                                        echo'</tr>';
                                    }
                                    ?>
                                </table>
                                <?php include("includes/navigator.php")?>
                                <?php } // Fin de muestra solo si si hubo registros ?>
                                <p align="center"><span class="label5">
                                        <?php if($totalRows == 0 && isset($_POST['txt_buscar'])){echo "La Busqueda no arrojó resultados";} ?>
                            </span></p></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                <p>&nbsp;</p></td>
            </tr>
        </table>
        <?php include("includes/init_menu_principal.php");?>
    </body>
</html>