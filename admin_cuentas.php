<?php
//llamamos el codigo de sesion para usuario nivel 2 = encargado de area
include("phpdelegates/rest_access_2.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/Empresa.php");
include("phpclasses/Cuenta.php");
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
//hacemos consulta para obtener los temas de la empresa
//por cada tema que obtengamos generamos un objeto tema y lo asignamos al objeto empresa
$base->execute_query("SELECT * FROM cuenta WHERE id_empresa = ".$empresa->get_id());
while($row_cuenta = $base->get_row_assoc())
{
    $cuenta = new Cuenta($row_cuenta);
    $empresa->add_cuenta($cuenta);
}
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
                          <td width="533" height="25" class="label2">Clientes --&gt; Administrar Clientes --&gt; <span class="label4">Administrar Cuentas</span></td>
                          <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                  </tr>
                    </table>
<table width="1000" border="0">
                        <tr>
                            <td width="160">&nbsp;</td>
                            <td width="830"><div align="right" class="label2"><?php echo $_GET['mensaje']; ?></div></td>
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
                              <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              
                              
                            </table>
                              <table width="825" border="0">
                                <tr>
                                  <td><div align="center" class="label4">Cuentas Actuales</div></td>
                                </tr>
                              </table>
                              <table width="825" border="3" align="center" cellpadding="0" cellspacing="0">
                                    <tr class="header2">
                                        <td><div align="center">Nombre</div></td>
                                        <td><div align="center">Cargo</div></td>
                                        <td><div align="center">Correo</div></td>
                                        <td><div align="center"></div></td>
                                        <td><div align="center"></div></td>
                                    </tr>
                                    <?php
                                    foreach ($empresa->get_cuentas() as  $cuenta_empresa) {
                                            echo'<tr class="label1">';
                                                echo'<td><div align="center" class="label3">'.$cuenta_empresa->get_nombre_completo().'</div></td>';
                                                echo'<td><div align="center">'.$cuenta_empresa->get_cargo().'</div></td>';
                                                echo'<td><div align="center">'.$cuenta_empresa->get_email().'</div></td>';
                                                echo'<td><div align="center"><a href="edit_cuenta.php?id_cuenta='.$cuenta_empresa->get_id().'&id_empresa='.$empresa->get_id().'">Ver o Editar</a></div></td>';
												echo'<td><div align="center"><a onclick="if(!confirm(\'¿Está seguro de eliminar la cuenta permanentemente?\'))return false"  href="action_delete_cuenta.php?id_cuenta='.$cuenta_empresa->get_id().'&id_empresa='.$empresa->get_id().'">Eliminar</a></div></td>';
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