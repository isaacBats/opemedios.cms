<?php
//llamamos el codigo de sesion para usuario nivel 2 = encargado de area
include("phpdelegates/rest_access_2.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");
include("phpdelegates/paginacion.php");
// llamamos las clases a utilizar
include("phpclasses/Usuario.php");

//llamamos la clase  Data Accecss Object
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
//declaramos las variables de paginacion

$url = $_SERVER['PHP_SELF']."?" .$_SERVER['QUERY_STRING'];
$url = str_replace("&pagina=".$_GET['pagina'],"",$url);
$limite = paginacion_init($_GET["pagina"],$registros);

$consulta = '1';
if(isset($_GET['txt_buscar']) || $_GET['txt_buscar'] != null)
{
    $consulta = "'%".$_GET['txt_buscar']."%'";
}
    $query = "select * from empresa  where nombre like ".$consulta;
    $base->execute_query($query);
    $num_row = $base->num_rows();
//-----------------------------------------------------------------------
$query = "select * from empresa  where nombre like ".$consulta."  LIMIT ".$limite[0].",".$registros;

$base->execute_query($query);
$array_display = array();
/*while($row_query = $base->get_row_assoc())
{
    $array_display = array("id" => $row_query['id'],
                           "fecha" => $row_query['fecha'],
                           "fuente" => $row_query['fuente'],
                           "imagen" => $row_query['imagen']);
}

$base->close();*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Grafica Clientes Analisis</title>
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
        <script type="text/javascript" src="InputCalendar/calendarDateInput.js"></script>
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
                          <td width="533" height="25" class="label2"> Gráficas  --&gt;Clientes --&gt;<span class="label4"> Análisis</span></td>
                          <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                  </tr>
                    </table>
<table width="1000" border="0">
                        <tr>
                            <td width="160">&nbsp;</td>
                            <td width="830"></td>
        </tr>
                        <tr>
                            <td valign="top"><form id="form_buscar" name="form_buscar" method="get" action="grafica_clientes_analisis.php">
                              <table width="152" border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td height="28" background="images/images/menusec_01_busqueda.png">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td height="55" align="center" valign="middle" background="images/images/menusec_02_empty.png"><label> <span class="label2">Cliente</span><br />
									<input name="txt_buscar" type="text" id="txt_buscar" class="textboxsec"/>
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
                              
                              <table width="832" border="3" align="center" cellpadding="0" cellspacing="0">
                          <tr class="header2">
                            <td width="254"><div align="center">Nombre</div></td>
                            <td width="281"><div align="center">Contacto</div></td>
                            <td width="281"><div align="center">giro</div></td>
                            <td width="62">&nbsp;</td>
                        </tr>
                                    <?php
                                    while($row_query = $base->get_row_assoc())
                                    {
                                            echo'<tr class="label1"><form name="reporte" action="busqueda_avanzada_graficas_analisis.php" method="POST">';
                                            
                                            echo'<td><div align="center">'.$row_query['nombre'].'</div></td>';
                                            echo'<td widht="32" align="center" ><img src="data/thumbs/'.$row_query['contacto'].'_pp.jpeg"></td>';
					    echo'<td><div align="center">'.$row_query['giro'].'</div></td>';
                                           // echo'<td><div align="center"><script>DateInput("fecha_ini", true, "YYYY-MM-DD") </script></div></td>';
                                           // echo'<td><div align="center"><script>DateInput("fecha_fin", true, "YYYY-MM-DD") </script></div></td>';
                                            echo'<td align="center"><input type="submit" value="Graficar" name="Reporte" /></td>';
					    echo'<input name="row_id" type="hidden" value="'.$row_query['id_empresa'].'" />';
                                            echo'<input name="type" type="hidden" value="cliente" />';
                                            echo'</form></tr>';
                                        }
                                    ?>
                                </table> 
                              <?php echo paginacion($url,$limite[1],$num_row,$registros);
                                        $base->free_result();
                                        $base->close();?>
                                <p align="center"><span class="label5">
                                        <?php if( $num_row == 0 && isset($_GET['txt_buscar'])){echo "La Busqueda no arrojó resultados";} ?>
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