<?php
//llamamos el codigo de sesion para usuario nivel 2 = encargado de area
include("phpdelegates/rest_access_2.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");
include("phpdelegates/paginacion.php");
// llamamos las clases a utilizar
include("phpclasses/PrimeraPlana.php");
include("phpclasses/Usuario.php");
include("phpclasses/Noticia.php");

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
//declaramos las variables de paginacion

$url = $_SERVER['PHP_SELF']."?" .$_SERVER['QUERY_STRING'];
$url = str_replace("&pagina=".$_GET['pagina'],"",$url);
$limite = paginacion_init($_GET["pagina"],$registros);

    $query = "SELECT * FROM primera_plana where fecha = '".$_GET['txt_buscar']."'";
    $base->execute_query($query);
    $num_row = $base->num_rows();
//-----------------------------------------------------------------------
$query = "SELECT
            PP.id_primera_plana AS id,
            PP.fecha,
            PP.imagen,
            FU.nombre as fuente
          FROM
            primera_plana AS PP,
            fuente AS FU
          WHERE 1=1
            AND PP.id_fuente = FU.id_fuente
            AND PP.fecha = '".$_GET['txt_buscar']."'
            LIMIT ".$limite[0].",".$registros;
$base->execute_query($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Administrar Primeras Planas</title>
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
                          <td width="533" height="25" class="label2">Prensa --&gt; Primeras Planas --&gt;<span class="label4"> Administrar Primeras Planas</span></td>
                          <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                  </tr>
                    </table>
                  <form id="form_buscar" name="form_buscar" method="get" action="admin_primerasplanas.php">         
                              <table width="876" border="0">
<tr>
                                              <td>&nbsp;</td>
                                              <td colspan="2" class="label2">&nbsp;</td>
                                              <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td width="18">&nbsp;</td>
                                              <td colspan="2" class="label2">Ver Primeras Planas por Fecha:</td>
                                              <td width="542">&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td>&nbsp;</td>
                                              <td width="83">&nbsp;</td>
                                              <td width="215"><script>DateInput('txt_buscar', true, 'YYYY-MM-DD',<?php if(isset($_GET['txt_buscar'])){echo "'".$_GET['txt_buscar']."'"; }else{echo "'".date("Y-m-d" ,time())."'";}?>)</script></td>
                                              <td><input name="btnbuscar" type="submit" id="btnbuscar" value="Ver Primeras Planas" /></td>
                                            </tr>
                                          </table>
                  </form>
                  <table width="917" border="0" align="center">
                        <tr>
                            <td width="830"></td>
        </tr>
                        <tr>
                            <td valign="top"><table width="825" border="0">
                                <tr>
                                  <td><div align="center" class="label2"><?php echo $_GET['mensaje']; ?></div></td>
                                </tr>
                              </table>
                              
                              <table width="832" border="3" align="center" cellpadding="0" cellspacing="0">
                          <tr class="header2">
                                        <td width="221"><div align="center">Fecha</div></td>
                            <td width="254"><div align="center">Fuente</div></td>
                            <td width="281"><div align="center">Imagen</div></td>
                            <td width="124">&nbsp;</td>
                                </tr>
                                    <?php
                                    while($row_query = $base->get_row_assoc())
                                    {
                                            echo'<tr class="label1">';
                                            echo'<td><div align="center" class="label3">'.$row_query['fecha'].'</div></td>';
                                            echo'<td><div align="center">'.$row_query['fuente'].'</div></td>';
                                            echo'<td widht="32" align="center" ><img src="data/thumbs/'.$row_query['imagen'].'_pp.jpg"></td>';
                                            echo'<td align="center"><a href="edit_primeraplana.php?id_pp='.$row_query['id'].'">Editar Plana</a></td>';
                                            echo'</tr>';
                                        }
                                    ?>
                              </table> 
                              <?php echo paginacion($url,$limite[1],$num_row,$registros);
                                        $base->free_result();
                                        $base->close();?>
                                <p align="center"><span class="label5">
                                        <?php if( $num_row == 0 && isset($_GET['txt_buscar'])){echo "La Busqueda no arrojó resultados";} ?>
                            </span></p>                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                        </tr>
                  </table>
              </td>
            </tr>
        </table>
        <?php include("includes/init_menu_principal.php");?>
    </body>
</html>