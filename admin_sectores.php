<?php
//llamamos el codigo de sesion para usuario nivel 2 = encargado de area
include("phpdelegates/rest_access_2.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/Sector.php");
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
include("phpdelegates/paginacion_sectores.php");

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
                          <td width="533" height="25" class="label2">Sectores --&gt; <span class="label4">Administrar Sectores</span></td>
                          <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                  </tr>
                    </table>
<table width="1000" border="0">
                        <tr>
                            <td width="160">&nbsp;</td>
                            <td width="830">&nbsp;</td>
                        </tr>
                        <tr>
                            <td valign="top"><form id="form_buscar" name="form_buscar" method="get" action="admin_sectores.php">
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
                              </table>
                            </form></td>
                            <td valign="top"><table width="825" border="0">
                                <tr>
                                  <td><div align="center" class="label2"><?php echo $_GET['mensaje']; ?></div></td>
                                </tr>
                              </table>
                              <?php if ($totalRows > 0) { // muestra solo si si hubo registros ?>
                              <table width="825" border="3" align="center" cellpadding="0" cellspacing="0">
                                    <tr class="header2">
                                        <td><div align="center">Nombre</div></td>
                                        <td><div align="center">Descripcion</div></td>
                                        <td><div align="center"></div></td>
                                    </tr>
                                    <?php
                                    foreach ($arreglo_sectores as  $se) {
                                            echo'<tr class="label1">';
                                            echo'<td><div align="center" class="label3">'.$se->get_nombre().'</div></td>';
                                            echo'<td><div align="center">'.$se->get_descripcion().'</div></td>';
                                            echo'<td><div align="center"><a href="edit_sector.php?id_sector='.$se->get_id().'">Editar Sector</a></div></td>';
                                            echo'</tr>';
                                        }
                                    ?>
                                </table> 
                                <?php include("includes/navigator.php")?>   
                                                                <?php } // Fin de muestra solo si si hubo registros ?>
                                <p align="center"><span class="label5">
                                        <?php if($totalRows == 0 && isset($_POST['txt_buscar'])){echo "La Busqueda no arrojó resultados";} ?>
                            </span></p>                      
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
    </body>
</html>