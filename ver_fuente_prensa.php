<?php
//llamamos el codigo de sesion para usuario nivel 2 = encargado de area
include("phpdelegates/rest_access_2.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/Seccion.php");
include("phpclasses/Fuente.php");
include("phpclasses/FuenteExtra.php");
include("phpclasses/TarifaPrensa.php");
include("phpclasses/Usuario.php");

//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");

//creamos un DAO para obtener los datos de la empresa dada mediante el metodo GET
//recibe como parametro el resultado de la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();
//obtenemos la informacion del usuario y creamos el objeto
$base->execute_query("SELECT * FROM usuario WHERE username = '".$session_usr."'");// session_usr se encuentra en los includes de sesion
$current_user = new Usuario($base->get_row_assoc());


if($_GET['id_tipo_fuente']==3)
{
   //hacemos consulta para la creacion del objeto FuenteExtra de periodico
   $base->execute_query("SELECT f.id_fuente AS id_fuente,
								f.nombre AS nombre,
								f.empresa AS empresa,
								f.comentario AS comentario,
								f.logo AS logo,
								f.activo AS activo,
								f.id_tipo_fuente AS id_tipo_fuente,
								f.id_cobertura AS id_cobertura,
								t.tiraje AS tiraje
					   FROM fuente AS f, fuente_per as t
					   WHERE (f.id_fuente = ".$_GET['id_fuente']." AND f.id_fuente = t.id_fuente);");
					   
					   //creamos el objeto FuenteExtra con los datos que nos regrese la consulta, tambien indicando que es de tipo 3 = periodico
	$fuente = new FuenteExtra($base->get_row_assoc(),3);
}
else
{
	   //hacemos consulta para la creacion del objeto FuenteExtra de revista
   $base->execute_query("SELECT f.id_fuente AS id_fuente,
								f.nombre AS nombre,
								f.empresa AS empresa,
								f.comentario AS comentario,
								f.logo AS logo,
								f.activo AS activo,
								f.id_tipo_fuente AS id_tipo_fuente,
								f.id_cobertura AS id_cobertura,
								t.tiraje AS tiraje
					   FROM fuente AS f, fuente_rev as t
					   WHERE (f.id_fuente = ".$_GET['id_fuente']." AND f.id_fuente = t.id_fuente);");
					   
					   //creamos el objeto FuenteExtra con los datos que nos regrese la consulta, tambien indicando que es de tipo 4 = revista
	$fuente = new FuenteExtra($base->get_row_assoc(),4);
}
					   

//hacemos consulta para obtener las secciones  de la fuente
//por cada seccion que obtengamos generamos un objeto Seccion y lo asignamos al objeto FuenteTV

$base->execute_query("SELECT * FROM seccion WHERE id_fuente =".$fuente->get_id());
while($row_seccion = $base->get_row_assoc())
{
    $seccion = new Seccion($row_seccion);
    $fuente->add_seccion($seccion);
}


//asignamos tarifas a fuente
$base->execute_query("SELECT * FROM cuesta_prensa WHERE id_fuente = ".$fuente->get_id()." ORDER BY id_seccion, id_tipo_pagina");
$flag = 0;
while($row_tarifa = $base->get_row_assoc())
{
    $tarifa = new TarifaPrensa($row_tarifa);
	$base->execute_query2("SELECT * FROM seccion WHERE id_seccion=".$row_tarifa['id_seccion']." LIMIT 1");
	$flag = 1;
	$seccion = new Seccion($base->get_row_assoc2());
    $tarifa->set_seccion($seccion);
    $fuente->add_tarifa($tarifa);
}

//cerramos conexion
$base->free_result();
if($flag == 1){$base->free_result2();}
$base->close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Ver Fuente</title>
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
                          <td width="533" height="25" class="label2">Fuentes --&gt; Administrar Fuentes --&gt; <span class="label4">Ver Datos de Fuente de <?php if($fuente->get_id_tipo_fuente()==3){echo "Periódico";}else{echo "Revista";}?></span></td>
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
                            <td valign="top"><table width="825" border="0" align="center">
                                    <tr>
                                        <td colspan="3"><div align="center" class="label4"><?php echo $fuente->get_nombre();?></div></td>
                                        <td height="80" colspan="2"><div align="center"><img src="data/fuentes/<?php echo $fuente->get_logo();?>" width="127" height="78" /></div></td>
                                    </tr>

                                    <tr>
                                        <td colspan="5">&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td width="32">&nbsp;</td>
                                        <td width="128" class="label2">Empresa:</td>
                                        <td colspan="3" class="label1"><?php echo $fuente->get_empresa();?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td class="label2">Tiraje:</td>
                                      <td colspan="3" class="label1"><?php echo $fuente->get_tiraje();?></td>
                              </tr>
                                    

                                    <tr>
                                        <td>&nbsp;</td>
                                        <td class="label2">Cobertura:</td>
                                        <td width="174" class="label1"><?php echo $fuente->get_cobertura_txt();?></td>
                                        <td width="58" >&nbsp;</td>
                                        <td width="411" class="label1">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td class="label2">Comentarios:</td>
                                        <td class="label1"><?php echo $fuente->get_comentario();?></td>
                                        <td class="label1">&nbsp;</td>
                                        <td class="label1">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td>&nbsp;</td>
                                      <td class="label2">Activo:</td>
                                      <td class="label1"><?php echo $fuente->get_activo_txt();?></td>
                                      <td class="label1">&nbsp;</td>
                                      <td class="label1">&nbsp;</td>
                                    </tr>
                                </table>

<table width="825" border="0">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="label4">SECCIONES:</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                                <table width="825" border="3" align="center" cellpadding="0" cellspacing="0">
                                    <tr class="header2">
                                        <td><div align="center">Nombre</div></td>
                                        <td><div align="center">Descripción</div></td>
                                    </tr>
                                    <?php
                                    foreach ($fuente->get_secciones() as  $seccion_fuente) {
                                            echo'<tr class="label1">';
                                            echo'<td><div align="center">'.$seccion_fuente->get_nombre().'</div></td>';
                                            echo'<td><div align="center">'.$seccion_fuente->get_descripcion().'</div></td>';
                                            echo'</tr>';
                                        }
                                    ?>
                                </table>
                                <table width="825" border="0">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="label4">TARIFAS:</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                                <table width="825" border="3" align="center" cellpadding="0" cellspacing="0">
                                    <tr class="header2">
                                        <td><div align="center">Sección</div></td>
                                        <td><div align="center">Tipo Página</div></td>
                                        <td><div align="center">Precio Página</div></td>
                                    </tr>
                                    <?php
                                    foreach ($fuente->get_tarifas() as  $tarifa_fuente) {
                                            echo'<tr class="label1">';
                                            echo'<td><div align="center">'.$tarifa_fuente->get_seccion()->get_nombre().'</div></td>';
											echo'<td><div align="center">'.$tarifa_fuente->get_tipo_pagina().'</div></td>';
                                            echo'<td><div align="center"> $'.$tarifa_fuente->get_precio().'</div></td>';
                                            echo'</tr>';
                                        }
                                    ?>
                                </table>
                                <table width="825" border="0">
                                    <tr>
                                        <td width="96">&nbsp;</td>
                                        <td width="196">&nbsp;</td>
                                        <td width="519">&nbsp;</td>
                                    </tr>
                                </table>
                          </td>
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