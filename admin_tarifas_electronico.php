<?php
//llamamos el codigo de sesion para usuario nivel 3
include("phpdelegates/rest_access_3.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/Fuente.php");
include("phpclasses/Usuario.php");
include("phpclasses/TarifaElectronico.php");
include("phpclasses/Horario.php");



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



//metemos los horarios en un arreglo
$base->execute_query("SELECT * FROM horario");
$arreglo_horarios = array();
while($row_horarios = $base->get_row_assoc())
{
    $horario = new Horario($row_horarios);
    $arreglo_horarios[$horario->get_id()]=$horario;
}
//metemos las tarifas en un arreglo
$arreglo_tarifas = array();
$base->execute_query("SELECT * FROM cuesta_electronico WHERE id_fuente = ".$fuente->get_id()." ORDER BY id_mes, id_horario");
while($row_tarifa = $base->get_row_assoc())
{
    $tarifa = new TarifaElectronico($row_tarifa);
    $tarifa->set_horario($arreglo_horarios[$row_tarifa['id_horario']]);
    $arreglo_tarifas[$tarifa->get_id_fuente()."_".$tarifa->get_horario()->get_id()."_".$tarifa->get_id_mes()]=$tarifa;
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
                          <td width="533" height="25" class="label2">Fuentes --&gt; Administrar Fuentes --&gt; <span class="label4">Administrar Tarifas</span></td>
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
                              <table width="825" border="0">
                                <tr>
                                  <td><div align="center" class="label4">Tarifas</div></td>
                                </tr>
                              </table>
                              <table width="825" border="3" align="center" cellpadding="0" cellspacing="0">
                                    <tr class="header2">
                                        <td><div align="center">Horario</div></td>
                                        <td><div align="center">Mes</div></td>
                                        <td><div align="center">Tiempo</div></td>
                                        <td><div align="center">Precio</div></td>
                                        <td><div align="center">&nbsp;</div></td>
                                    </tr>
                                    <?php
                                    foreach ($arreglo_tarifas as  $tarifa_fuente) {
                                            echo'<tr class="label1">';
                                            echo'<td><div align="center">'.$tarifa_fuente->get_horario()->get_hora_inicio().' a '.$tarifa_fuente->get_horario()->get_hora_final().'</div></td>';
											echo'<td><div align="center">'.$tarifa_fuente->get_mes().'</div></td>';
											echo'<td><div align="center">'.$tarifa_fuente->get_tiempo().'</div></td>';
                                            echo'<td><div align="center">$ '.$tarifa_fuente->get_precio().'</div></td>';
											echo'<td><div align="center"><a href="action_admin_tarifas_electronico.php?id_fuente='.$fuente->get_id().'&id_mes='.$tarifa_fuente->get_id_mes().'&id_horario='.$tarifa_fuente->get_horario()->get_id().'" onclick="if(!confirm(\'Está seguro de borrar la tarifa?\'))return false">Borrar Tarifa</a></div></td>';
                                            echo'</tr>';
                                        }
                                    ?>
                                </table>
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