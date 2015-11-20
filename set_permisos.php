<?php
//llamamos el codigo de sesion para usuario nivel 2 = encargado de area
include("phpdelegates/rest_access_2.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/Empresa.php");
include("phpclasses/Permiso.php");
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
//hacemos consulta para la creacion de los objetos empresa
$base->execute_query("SELECT * FROM empresa WHERE id_empresa =".$_GET['id_empresa']);
//creamos el objeto empresa con los datos que nos regrese la consulta
$empresa = new Empresa($base->get_row_assoc());
//hacemos consulta para obtener los permisos de la empresa
$base->execute_query("SELECT * FROM permiso WHERE id_empresa = ".$empresa->get_id());

$permiso = new Permiso($base->get_row_assoc());
$empresa->set_permisos($permiso);

//creamos un arreglo para mostrar en los menus
$base->execute_query("SELECT * FROM acceso");
$arreglo_menu = array();
while($acceso = $base->get_row_assoc())
{
    $arreglo_menu[$acceso['id_acceso']] = $acceso["descripcion"];
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

function MM_preloadImages() { //v3.0
                var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
                    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
                        if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
                }


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
                          <td width="533" height="25" class="label2">Clientes --&gt; Administrar Clientes --&gt; <span class="label4">Establecer Permisos</span></td>
                          <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                  </tr>
                    </table>
<table width="1000" border="0">
                        <tr>
                            <td width="160">&nbsp;</td>
                            <td width="830">&nbsp;</td>
                        </tr>
                        <tr>
                            <td valign="top"><?php include("includes/menu_empresas.php");?></td>
                            <td valign="top"><table width="825" border="0">
                              <tr>
                                <td width="30">&nbsp;</td>
                                <td width="355"><div align="center"><span class="label4"><?php echo $empresa->get_nombre(); ?></span></div></td>
                                <td width="400"><img src="<?php echo "data/empresas/".$empresa->get_logo(); ?>" alt="" name="logo" width="400" height="80" id="logo" /></td>
                                <td width="22">&nbsp;</td>
                              </tr>
                              
                              
                            </table>
                              <form id="form_set_permisos" name="form_set_permisos" method="post" action="action_set_permisos.php">
                                <table width="825" border="0">
                                  <tr>
                                    <td width="35">&nbsp;</td>
                                    <td width="171" class="label2">Establecer Permisos:</td>
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
                                    <td><input name="id_empresa" type="hidden" id="id_empresa" value="<?php echo $empresa->get_id();?>" /></td>
                                    <td class="label3">Primeras Planas:</td>
                                    <td><label>
                                      <select name="primeras_planas" class="combo3" id="primeras_planas">
                                      <?php
                                      foreach ($arreglo_menu as $value => $label)
                                      {
                                          echo '<option value="'.$value.'"'; if($value == $permiso->get_primeras_planas_id()){echo 'selected="selected"';}  echo'>'.utf8_encode($label).'</option>';
                                      }
                                      ?>
                                        </select>
                                    </label></td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td class="label3">Columnas Políticas:</td>
                                    <td><label>
                                      <select name="col_pol" class="combo3" id="col_pol">
                                      <?php
                                      foreach ($arreglo_menu as $value => $label)
                                      {
                                          echo '<option value="'.$value.'"'; if($value == $permiso->get_col_pol_id()){echo 'selected="selected"';}  echo'>'.utf8_encode($label).'</option>';
                                      }
                                      ?>
                                    </select>
                                    </label></td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td class="label3">Columnas Financieras:</td>
                                    <td><label>
                                      <select name="col_fin" class="combo3" id="col_fin">
                                     <?php
                                      foreach ($arreglo_menu as $value => $label)
                                      {
                                          echo '<option value="'.$value.'"'; if($value == $permiso->get_col_fin_id()){echo 'selected="selected"';}  echo'>'.utf8_encode($label).'</option>';
                                      }
                                      ?>
                                    </select>
                                    </label></td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td class="label3">Cartones:</td>
                                    <td><label>
                                      <select name="cartones" class="combo3" id="cartones">
                                     <?php
                                      foreach ($arreglo_menu as $value => $label)
                                      {
                                          echo '<option value="'.$value.'"'; if($value == $permiso->get_cartones_id()){echo 'selected="selected"';}  echo'>'.utf8_encode($label).'</option>';
                                      }
                                      ?>
                                    </select>
                                    </label></td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td class="label3">Portadas de Negocios:</td>
                                    <td><label>
                                      <select name="portadas_fin" class="combo3" id="portadas_fin">
                                     <?php
                                      foreach ($arreglo_menu as $value => $label)
                                      {
                                          echo '<option value="'.$value.'"'; if($value == $permiso->get_portadas_fin_id()){echo 'selected="selected"';}  echo'>'.utf8_encode($label).'</option>';
                                      }
                                      ?>
                                    </select>
                                    </label></td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td><input name="button" type="submit" id="button" value="Establecer Permisos" /></td>
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