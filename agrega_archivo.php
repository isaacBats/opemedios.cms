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

//creamos un DAO para obtener los datos de la empresa dada mediante el metodo GET
//recibe como parametro el resultado de la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();
//obtenemos la informacion del usuario y creamos el objeto
$base->execute_query("SELECT * FROM usuario WHERE username = '".$session_usr."'");// session_usr se encuentra en los inclides de sesion
$current_user = new Usuario($base->get_row_assoc());
//hacemos consulta para la creacion de un objeto de la clase noticia
$base->execute_query("SELECT * FROM noticia WHERE id_noticia =".$_GET['id_noticia']);
//creamos el objeto empresa con los datos que nos regrese la consulta
$noticia = new Noticia($base->get_row_assoc());

//cerramos conexion
$base->close();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Agrega Archivo</title>
        <style type="text/css">
            <!--
            body {
                margin-left: 0px;
                margin-top: 0px;
                margin-right: 0px;
                margin-bottom: 0px;
                background-image: url(images/noticia_bg.jpg);
            }
            -->
        </style>
        <link href="CSS/opemedios.css" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <table width="505" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
                <td width="505" height="560" valign="top" ><table width="505" height="37" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td><img src="images/titulo_agregaarchivo.png" width="500" height="30" /></td>
                        </tr>
                    </table>
                    <form action="action_agrega_archivo.php" method="post" enctype="multipart/form-data" name="form_add_archivo" target="_self" id="form_add_archivo">
                        <table width="500" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="20">&nbsp;</td>
                                <td width="100">&nbsp;</td>
                                <td width="38">&nbsp;</td>
                                <td width="40">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                        <table width="500" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="20">&nbsp;</td>
                                <td width="99">&nbsp;</td>
                                <td width="152">&nbsp;</td>
                                <td width="10">&nbsp;</td>
                                <td width="59">&nbsp;</td>
                                <td width="148">&nbsp;</td>
                                <td width="12">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td class="label1">Noticia id:</td>
                                <td colspan="4" class="label3"><?php echo $noticia->getId(); ?></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td class="label1">Encabezado:</td>
                                <td colspan="4" class="label2"><?php echo $noticia->getEncabezado(); ?></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan="5" class="label5">
                                    <?php 
                                        if($_GET['mensaje'] != "exito"){ 
                                            if($_GET['archivo'] == ""){
                                                echo "No se selecciono ningun archivo. Puede subir uno ahora o cerrar la ventana";
                                            }else {
                                                echo "Hubo un error al subir el archivo: ".$_GET['archivo']." Revise que el tamaño no exceda 50 Mb";
                                            }
                                        } 
                                    ?>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan="5" class="label4">
                                    <?php 
                                        if($_GET['mensaje'] == "exito"){ 
                                            echo "El Archivo: ".$_GET['archivo']." se ha Subido correctamente, Puede añadir otro archivo a la noticia, o bien, cierre la ventana";
                                        } 
                                    ?>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td colspan="4">&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td colspan="4">&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>Archivo :</td>
                                <td colspan="4"><input name="archivo" type="file" class="textbox1" id="archivo" size="50" /></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <input name="principal" type="hidden" id="principal" value="<?php echo $_GET['principal']; ?>" />
                                    <input name="id_noticia" type="hidden" id="id_noticia" value="<?php echo $_GET['id_noticia']; ?>" />
                                    <input name="folder" type="hidden" id="id_noticia" value="<?php echo $_GET['folder']; ?>" />
                                </td>
                                <td colspan="3">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td colspan="3">&nbsp;</td>
                                <td><input type="submit" name="button" id="button" value="Agregar Archivo" /></td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                </form>    </td>
            </tr>
        </table>
    </body>
</html>