<?php
//llamamos el codigo de sesion para usuario nivel 3 = monitorista
include("phpdelegates/rest_access_3.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/Noticia.php");
include("phpclasses/Usuario.php");

//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");

//creamos DAO
//recibe como parametro el resultado de la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();
//obtenemos la informacion del usuario y creamos el objeto
$base->execute_query("SELECT * FROM usuario WHERE username = '".$session_usr."'");// session_usr se encuentra en los includes de sesion
$current_user = new Usuario($base->get_row_assoc());

//obtenemos datos de la noticia
$base->execute_query("SELECT * FROM noticia WHERE id_noticia = ".$_GET['id_noticia'].";");

$noticia = new Noticia($base->get_row_assoc());


//cerramos conexion
$base->free_result();
$base->close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Editar Noticia Electronica</title>
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
				
	 function disable()
	{
	document.form1.enviar.value = 'Cargando Archivo.. Espere'
	document.form1.enviar.disabled = true
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
                            <td width="533" height="25" class="label2">Noticias --&gt; <span class="label4">Establecer Archivo Principal</span></td>
                            <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                        </tr>
                    </table>
                    <table width="1000" border="0">
                        <tr>
                            <td width="160">&nbsp;</td>
                            <td width="830">&nbsp;</td>
                        </tr>
                        <tr>
                            <td valign="top"><?php include("includes/menu_noticias.php");?></td>
                            <td valign="top"><form action="action_set_archivo_principal.php" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="disable()">
                                    <table width="825" border="0">
                                        <tr>
                                            <td width="112">&nbsp;</td>
                                            <td width="148" height="28" class="label3">Id: <span class="label5"><?php echo $noticia->getId(); ?></span></td>
                                            <td width="441">&nbsp;</td>
                                            <td width="106">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td height="28" class="label3">Encabezado:</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td height="28" colspan="2" class="label1"><div align="justify" class="label1"><?php echo $noticia->getEncabezado(); ?></div></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td height="28" class="label3">&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td height="28" class="label5"><?php echo $_GET['mensaje'];  ?></td>
                                          <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td height="28" colspan="2" class="label2">Seleccione un archivo para establecerlo como principal a esta noticia, el archivo debe pesar menos de 50Mb</td>
                                            <td>&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td><input name="id_noticia" type="hidden" id="id_noticia" value="<?php echo $noticia->getId(); ?>" />
                                            <input name="id_tipo_fuente" type="hidden" id="id_tipo_fuente" value="<?php echo $noticia->getId_tipo_fuente(); ?>" /></td>
                                            <td height="28" colspan="2" class="label3"><label>

                                                    <input name="archivo" type="file" class="textbox1" id="archivo" size="60" />
                                            </label></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td class="label3">&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>


                                        <tr>
                                            <td>&nbsp;</td>
                                            <td class="label3">&nbsp;</td>
                                            <td><div align="right">
                                                    <label></label>
                                                    <label>
                                                        <input type="submit" name="enviar" id="enviar" value="Establecer Archivo" />
                                                    </label>
                                            </div></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </table>
                            </form>                            </td>
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