<?php
//llamamos el codigo de sesion para usuario nivel 3
include("phpdelegates/rest_access_3.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/Fuente.php");
include("phpclasses/Seccion.php");
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
//hacemos consulta para la creacion de los objetos fuente
$base->execute_query("SELECT * FROM fuente WHERE id_fuente =".$_GET['id_fuente']);
//creamos el objeto fuente con los datos que nos regrese la consulta
$fuente = new Fuente($base->get_row_assoc());
//hacemos consulta para obtener las secciones de la fuente
//por cada seccion que obtengamos generamos un objeto seccion y lo asignamos al objeto fuente
$base->execute_query("SELECT * FROM seccion WHERE id_fuente = ".$fuente->get_id());
while($row_seccion = $base->get_row_assoc())
{
    $seccion = new Seccion($row_seccion);
    $fuente->add_seccion($seccion);
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
                          <td width="533" height="25" class="label2">Fuentes --&gt; Administrar Fuentes --&gt; <span class="label4">Administrar Secciones</span></td>
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
                              <form id="form_add_seccion" name="form_add_seccion" method="post" action="action_admin_secciones.php">
                                <table width="825" border="0">
                                  <tr>
                                    <td width="35">&nbsp;</td>
                                    <td width="171" class="label2">Agregar Nueva Seccion:</td>
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
                                    <td class="label3">Nombre:</td>
                                    <td><label>
                                      <input name="nombre" type="text" class="textbox1" id="nombre" />
                                    </label></td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td class="label3">Descripcion:</td>
                                    <td><label>
                                      <textarea name="descripcion" cols="45" rows="5" class="texboxML" id="descripcion"></textarea>
                                    </label></td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td class="label3">Activo:</td>
                                    <td><label>
                                      <input name="activo" type="checkbox" id="activo" value="1" />
                                    </label></td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td><input name="button" type="submit" id="button" onclick="MM_validateForm('nombre','','R');return document.MM_returnValue" value="Agregar Sección" /></td>
                                  </tr>
                                </table>
                              </form>
                              <table width="825" border="0">
                                <tr>
                                  <td><div align="center" class="label4">Secciones</div></td>
                                </tr>
                              </table>
                              <table width="825" border="3" align="center" cellpadding="0" cellspacing="0">
                                    <tr class="header2">
                                        <td><div align="center">Nombre</div></td>
                                        <td><div align="center">Descripcion</div></td>
                                        <td><div align="center">Activo</div></td>
                                        <td><div align="center"></div></td>
                                    </tr>
                                    <?php
                                    foreach ($fuente->get_secciones() as  $seccion_fuente) {
                                            echo'<tr class="label1">';
                                            echo'<td><div align="center" class="label3">'.$seccion_fuente->get_nombre().'</div></td>';
                                            echo'<td><div align="center">'.$seccion_fuente->get_descripcion().'</div></td>';
											 echo'<td><div align="center">'.$seccion_fuente->get_activo_txt().'</div></td>';
                                            echo'<td><div align="center"><a href="edit_seccion.php?id_seccion='.$seccion_fuente->get_id().'&id_fuente='.$fuente->get_id().'">Editar Sección</a></div></td>';
                                            echo'</tr>';
                                        }
                                    ?>
                                </table>                          </td>
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