<?php
//llamamos el codigo de sesion para usuario nivel 2 = encargado de area
include("phpdelegates/rest_access_2.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/Usuario.php");

//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");


//recibe como parametro el resultado de la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();
//obtenemos la informacion del usuario y creamos el objeto
$base->execute_query("SELECT * FROM usuario WHERE username = '".$session_usr."'");// session_usr se encuentra en los includes de sesion
$current_user = new Usuario($base->get_row_assoc());

//cerramos conexion
$base->free_result();
$base->close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Asignacion a Clientes</title>
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
        <script type="text/javascript" src="InputCalendar/calendarDateInput.js">

            /***********************************************
             * Jason's Date Input Calendar- By Jason Moon http://calendar.moonscript.com/dateinput.cfm
             * Script featured on and available at http://www.dynamicdrive.com
             * Keep this notice intact for use.
             ***********************************************/

        </script>
        <script type="text/javascript">
            <!--
            function MM_preloadImages() { //v3.0
                var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
                    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
                        if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
                }
                //-->

                //Obtiene el objeto XmlHttpRequest segun sea el navegador
                function getXmlHttpRequestObject() {
                    if (window.XMLHttpRequest) {
                        return new XMLHttpRequest();
                    } else if(window.ActiveXObject) {
                        return new ActiveXObject("Microsoft.XMLHTTP");
                    } else {
                        document.getElementById('div_clientes').innerHTML =
                            'Status: No se pudo crear el objeto XmlHttpRequest.' +
                            'Considere actualizar su explorador.';
                    }
                }

                var div_clientes_Req = getXmlHttpRequestObject();
                var div_noticias_Req = getXmlHttpRequestObject();
                var div_tema_Req = getXmlHttpRequestObject();
                var div_tendencia_Req = getXmlHttpRequestObject();
                var div_boton_Req = getXmlHttpRequestObject();
                var http_Req = getXmlHttpRequestObject();
                var row_div = 0; // el id de noticia
                var ismodifiedstatus = 0; // 0 si no se ha apretado ningun boton de modificar, 1 si se apreto modificar  y 2 si se apreto actualizar


                //envia el texto a buscar al servidor , le enviamos los datos de la noticia para posteriores redireccionamientos al finalizar el envio
                function BuscaCliente() {
                    if (div_clientes_Req.readyState == 4 || div_clientes_Req.readyState == 0)
                    {
                        document.getElementById('div_clientes').innerHTML = '<span class="label2">Cargando...<img src="images/working.gif" /></span>';
                        div_clientes_Req.open("POST", 'ajax_backend_asignacion_noticia_cliente.php?action=busca_cliente', true);
                        div_clientes_Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                        var param = 'text=' + document.getElementById('txt_busca_cliente').value;
                        div_clientes_Req.onreadystatechange = handleContentCliente;
                        div_clientes_Req.send(param);
                        document.getElementById('div_noticias').innerHTML = '<span class="label2">Seleccione un cliente:</span><br />';
                    }
                }

                //Maneja el regreso de los clientes encontrados
                function handleContentCliente() {
                    if (div_clientes_Req.readyState == 4) {
                        var text_div = document.getElementById('div_clientes');
                        var content = div_clientes_Req.responseText;
                        text_div.innerHTML = content;
                    }
                }

                function SeleccionaCliente(id_cliente)
                {
                    if(div_clientes_Req.readyState == 4 || div_clientes_Req.readyState == 0)
                    {
                        document.getElementById('div_clientes').innerHTML = '<span class="label2">Cargando...<img src="images/working.gif" /></span>';
                        div_clientes_Req.open("POST", 'ajax_backend_asignacion_noticia_cliente.php?action=get_info_cliente', true);
                        div_clientes_Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                        var param = 'id_cliente=' + id_cliente;
                        div_clientes_Req.onreadystatechange = handleContentCliente;
                        div_clientes_Req.send(param);
                    }

                    if (div_noticias_Req.readyState == 4 || div_noticias_Req.readyState == 0)
                    {
                        document.getElementById('div_noticias').innerHTML = '<span class="label2">Cargando...<img src="images/working.gif" /></span>';
                        div_noticias_Req.open("POST", 'ajax_backend_asignacion_noticia_cliente.php?action=muestra_noticias', true);
                        div_noticias_Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                        var param = 'id_cliente=' + id_cliente;
                        param += '&fecha1_Day_ID=' + document.getElementById('fecha1_Day_ID').options[document.getElementById('fecha1_Day_ID').selectedIndex].value;
                        param += '&fecha1_Month_ID=' + document.getElementById('fecha1_Month_ID').options[document.getElementById('fecha1_Month_ID').selectedIndex].value;
                        param += '&fecha1_Year_ID=' + document.getElementById('fecha1_Year_ID').value;
                        param += '&fecha2_Day_ID=' + document.getElementById('fecha2_Day_ID').options[document.getElementById('fecha2_Day_ID').selectedIndex].value;
                        param += '&fecha2_Month_ID=' + document.getElementById('fecha2_Month_ID').options[document.getElementById('fecha2_Month_ID').selectedIndex].value;
                        param += '&fecha2_Year_ID=' + document.getElementById('fecha2_Year_ID').value
                        div_noticias_Req.onreadystatechange = handleContentNoticias;
                        div_noticias_Req.send(param);
                    }
                }

                //Maneja el regreso de las noticias segun el cliente seleccionado y el intervalo de tiempo
                function handleContentNoticias() {
                    if (div_noticias_Req.readyState == 4) {
                        var text_div = document.getElementById('div_noticias');
                        var content = div_noticias_Req.responseText;
                        text_div.innerHTML = content;
                    }
                }

                function handleContentTema() {
                    if (div_tema_Req.readyState == 4) {
                        var text_div = document.getElementById('div_tema_'+row_div);
                        var content = div_tema_Req.responseText;
                        text_div.innerHTML = content;
                    }
                }
                function handleContentTendencia() {
                    if (div_tendencia_Req.readyState == 4) {
                        var text_div = document.getElementById('div_tendencia_'+row_div);
                        var content = div_tendencia_Req.responseText;
                        text_div.innerHTML = content;
                    }
                }
                function handleContentBoton() {
                    if (div_boton_Req.readyState == 4) {
                        var text_div = document.getElementById('div_boton_'+row_div);
                        var content = div_boton_Req.responseText;
                        text_div.innerHTML = content;
                    }
                }

                function modificaNoticia(id_not,id_emp,id_tem,id_ten)
                {
                    row_div = id_not;
                    ismodifiedstatus = 1;
                    if(div_tema_Req.readyState == 4 || div_tema_Req.readyState == 0)
                    {
                        document.getElementById('div_tema_'+id_not).innerHTML = '<span class="label2">Cargando...<img src="images/working.gif" /></span>';
                        div_tema_Req.open("POST",'ajax_backend_asignacion_noticia_cliente.php?action=get_temas',true);
                        div_tema_Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                        var param = 'id_empresa=' + id_emp;
                        param+= '&id_tema=' + id_tem;
                        param+= '&id_noticia=' + id_not;
                        param+= '&id_tendencia=' +id_ten;
                        div_tema_Req.onreadystatechange = handleContentTema;
                        div_tema_Req.send(param,id_not);
                    }

                    if(div_tendencia_Req.readyState == 4 || div_tendencia_Req.readyState == 0)
                    {
                        document.getElementById('div_tendencia_'+id_not).innerHTML = '<span class="label2">Cargando...<img src="images/working.gif" /></span>';
                        div_tendencia_Req.open("POST", 'ajax_backend_asignacion_noticia_cliente.php?action=get_tendencias', true);
                        div_tendencia_Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                        var param = 'id_empresa=' + id_emp;
                        param+= '&id_tema=' + id_tem;
                        param+= '&id_noticia=' + id_not;
                        param+= '&id_tendencia=' +id_ten;
                        div_tendencia_Req.onreadystatechange = handleContentTendencia;
                        div_tendencia_Req.send(param);
                    }

                    if(div_boton_Req.readyState == 4 || div_boton_Req.readyState == 0)
                    {
                        document.getElementById('div_boton_'+id_not).innerHTML = '<span class="label2">Cargando...<img src="images/working.gif" /></span>';
                        div_boton_Req.open("POST", 'ajax_backend_asignacion_noticia_cliente.php?action=get_boton', true);
                        div_boton_Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                        var param = 'id_empresa=' + id_emp;
                        param+= '&id_noticia=' + id_not;
                        param+= '&id_tema=' + id_tem;
                        param+= '&id_tendencia=' + id_ten;
                        div_boton_Req.onreadystatechange = handleContentBoton;
                        div_boton_Req.send(param);
                    }

                }

                function ActualizaNoticia(id_not,id_emp,id_tem,id_ten)
                {
                    row_div = id_not;
                    ismodifiedstatus = 2;
                    if(http_Req.readyState == 4 || http_Req.readyState == 0)
                    {
                        http_Req.open("POST" , 'ajax_backend_asignacion_noticia_cliente.php?action=update', true);
                        http_Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                        var param = 'id_empresa=' + id_emp;
                        param+= '&id_noticia=' + id_not;
                        param+= '&id_tema=' + document.getElementById('id_tema_'+id_not).options[document.getElementById('id_tema_'+id_not).selectedIndex].value;
                        param+= '&id_tendencia=' + document.getElementById('id_tendencia_'+id_not).options[document.getElementById('id_tendencia_'+id_not).selectedIndex].value;
                        http_Req.onreadystatechange =handleActualiza;
                        http_Req.send(param);
                        document.getElementById('actualizar_'+id_not).value = 'Espere...';
                        document.getElementById('quitar_'+id_not).disabled = true;
                        document.getElementById('id_tema_'+id_not).disabled = true;
                        document.getElementById('id_tendencia_'+id_not).disabled = true;
                    }
                }
                function handleActualiza()
                {
                    if (http_Req.readyState == 4) {
                        var content = http_Req.responseText;
                        if(content == 'exito')
                        {
                            document.getElementById('actualizar_'+row_div).value = 'Modificado';
                            document.getElementById('actualizar_'+row_div).disabled = true;
                            document.getElementById('id_tendencia_'+row_div).disabled = true;
                            document.getElementById('id_tema_'+row_div).disabled = true;
                            document.getElementById('quitar_'+row_div).disabled = false;
                        }
                        else
                        {
                            document.getElementById('actualizar_'+row_div).value = 'Error';
                            document.getElementById('actualizar_'+row_div).disabled = true;
                            document.getElementById('id_tendencia_'+row_div).disabled = true;
                            document.getElementById('id_tema_'+row_div).disabled = true;
                            document.getElementById('quitar_'+row_div).disabled = false;
                        }

                    }
                }
                function quitaNoticia(id_not,id_emp,id_tem,id_ten)
                {
                    row_div = id_not;
                    if(http_Req.readyState == 4 || http_Req.readyState == 0)
                    {
                        http_Req.open("POST" , 'ajax_backend_asignacion_noticia_cliente.php?action=quit', true);
                        http_Req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                        var param = 'id_empresa=' + id_emp;
                        param+= '&id_noticia=' + id_not;
                        http_Req.onreadystatechange =handleQuitaNoticia;
                        http_Req.send(param);
                        document.getElementById('quitar_'+id_not).value = 'Espere...';
                        document.getElementById('quitar_'+id_not).disabled = true;
                        if(ismodifiedstatus == 0)
                        {
                            document.getElementById('modificar_'+id_not).disabled = true;
                            document.getElementById('id_tema_'+id_not).disabled = true;
                            document.getElementById('id_tendencia_'+id_not).disabled = true;
                        }
                        if(ismodifiedstatus == 1)
                        {
                            document.getElementById('actualizar_'+id_not).disabled = true;
                            document.getElementById('id_tema_'+id_not).disabled = true;
                            document.getElementById('id_tendencia_'+id_not).disabled = true;
                        }
                        if(ismodifiedstatus == 2)
                        {
                            document.getElementById('actualizar_'+id_not).disabled = true;
                            document.getElementById('id_tema_'+id_not).disabled = true;
                            document.getElementById('id_tendencia_'+id_not).disabled = true;
                        }

                    }

                }

                function handleQuitaNoticia()
                {
                    if (http_Req.readyState == 4) {
                        var content = http_Req.responseText;
                        if(content == 'exito')
                        {
                            document.getElementById('quitar_'+row_div).value = 'Eliminado';
                            document.getElementById('quitar_'+row_div).disabled = true;
                            document.getElementById('div_tema_'+row_div).innerHTML = '';
                            document.getElementById('div_tendencia_'+row_div).innerHTML = '';
                            document.getElementById('div_boton_'+row_div).innerHTML = '';

                        }
                        else
                        {
//                            document.getElementById('actualizar_'+row_div).value = 'Error';
//                            document.getElementById('actualizar_'+row_div).disabled = true;
//                            document.getElementById('id_tendencia_'+row_div).disabled = true;
//                            document.getElementById('id_tema_'+row_div).disabled = true;
//                            document.getElementById('quitar_'+row_div).disabled = false;
                        }

                    }
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
                            <td width="533" height="25" class="label2">Asignación --&gt; <span class="label4">Noticias a Clientes</span></td>
                            <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                        </tr>
                    </table>
                    <table width="1000" border="0">
                        <tr>
                            <td width="15">&nbsp;</td>
                            <td width="485" valign="top">
							<table width="480" border="0">
                                    <tr>
                                        <td colspan="2">&nbsp;</td>                                     
                                    </tr>
                                    <tr>
                                        <td class="label2" colspan="2">&nbsp;</td>                                       
                                    </tr>
                                    <tr>
                                        <td class="label2" colspan="2"><b>Seleccione Fecha:</b></td>
                                    </tr>
                                    <tr>
                                        <td valign="top" class="label2">De:<br><script>DateInput('fecha1', true, 'DD-MON-YYYY')</script></td>
										<td valign="top" class="label2">A:<br><script>DateInput('fecha2', true, 'DD-MON-YYYY')</script></td>										
									</tr>
									<tr>
                                        <td colspan="2">&nbsp;</td>                                     
                                    </tr>
									<tr>
										<td class="label2" colspan="2"><b>Buscar Cliente:</b></td>
									</tr>
									<tr>
										<td><input name="txt_busca_cliente" type="text" class="textbox2" id="txt_busca_cliente" />&nbsp;
											<input type="submit" name="buscar" id="buscar" value="Buscar -&gt;" onclick="BuscaCliente()"/>
										</td>
									</tr>
                            </table>
							</td>
                            <td width="486" valign="top">
								<table width="480" border="0">
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><div class="div_asignacion1" id="div_clientes"><span class="label2"></span><br /></div></td>
                                    </tr>
								</table>
							</td>
                        </tr>
                    </table>
                    <div class="div_asignacion2" id="div_noticias"><br />
                </div></td>
            </tr>
        </table>
        <?php include("includes/init_menu_principal.php");?>
    </body>
</html>