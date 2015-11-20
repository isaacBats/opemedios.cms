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
        <title>Enviar Portadas Financieras</title>
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
		document.getElementById('resultados_busqueda').innerHTML = 
		'Status: No se pudo crear el objeto XmlHttpRequest.' +
		'Considere actualizar su explorador.';
	}
}

var clienteReq = getXmlHttpRequestObject();
var cuentaReq = getXmlHttpRequestObject();

//envia el texto a buscar al servidor , le enviamos los datos de la noticia para posteriores redireciconamientos al finalizar el envio
function BuscaCliente() {
	if (clienteReq.readyState == 4 || clienteReq.readyState == 0)
	 {
	 	document.getElementById('resultados_busqueda').innerHTML = '<span class="label2">Cargando...<img src="images/working.gif" /></span>';
		clienteReq.open("POST", 'ajax_backend_envia_portada_financiera.php?action=busca_cliente', true);
		clienteReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		var param = 'text=' + document.getElementById('txt_busca_cliente').value;
		clienteReq.onreadystatechange = handleContentCliente;
		clienteReq.send(param);
		document.getElementById('correos_cliente').innerHTML = '<span class="label2">Seleccionar un cliente en el cuadro de la izquierda:</span><br />';
	}							
}

//Maneja el regreso de los clientes encontrados
function handleContentCliente() {
	if (clienteReq.readyState == 4) {
		var text_div = document.getElementById('resultados_busqueda');
		var content = clienteReq.responseText;
		text_div.innerHTML = content;
	}
}

function SeleccionaCliente(id_cliente)
{
	if(clienteReq.readyState == 4 || clienteReq.readyState == 0)
	{
		document.getElementById('resultados_busqueda').innerHTML = '<span class="label2">Cargando...<img src="images/working.gif" /></span>';
		clienteReq.open("POST", 'ajax_backend_envia_portada_financiera.php?action=get_info_cliente', true);
		clienteReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		var param = 'id_cliente=' + id_cliente;
		clienteReq.onreadystatechange = handleContentCliente;
		clienteReq.send(param);
	}
	
	if (cuentaReq.readyState == 4 || cuentaReq.readyState == 0)
	{
		document.getElementById('correos_cliente').innerHTML = '<span class="label2">Cargando...<img src="images/working.gif" /></span>';
		cuentaReq.open("POST", 'ajax_backend_envia_portada_financiera.php?action=muestra_cuentas', true);
		cuentaReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		var param = 'id_cliente=' + id_cliente;
		param += '&fecha1_Day_ID=' + document.getElementById('fecha1_Day_ID').options[document.getElementById('fecha1_Day_ID').selectedIndex].value;
        param += '&fecha1_Month_ID=' + document.getElementById('fecha1_Month_ID').options[document.getElementById('fecha1_Month_ID').selectedIndex].value;
        param += '&fecha1_Year_ID=' + document.getElementById('fecha1_Year_ID').value;
		cuentaReq.onreadystatechange = handleContentCuentas;
		cuentaReq.send(param);
	}
}

//Maneja el regreso de las cuentas segun el cliente seleccionado
function handleContentCuentas() {
	if (cuentaReq.readyState == 4) {
		var text_div = document.getElementById('correos_cliente');
		var content = cuentaReq.responseText;
		text_div.innerHTML = content;
	}
}


function CheckAll()
{
	for (var i=0; i<document.form1.elements.length;i++)
	{
 		var e = document.form1.elements[i];
 		if (e.name != 'select_all')
 		e.checked = document.form1.select_all.checked;
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
                            <td width="533" height="25" class="label2">Prensa--&gt; Portadas Financieras --&gt; <span class="label4">Enviar a Clientes</span></td>
                          <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                        </tr>
                    </table>
                    <table width="1000" border="0">
                  <tr>
                            <td width="15">&nbsp;</td>
                            <td width="490">&nbsp;</td>
                    <td width="481">&nbsp;</td>
                      </tr>
                        <tr>
                            <td valign="top">&nbsp;</td>
                            <td valign="top"><span class="label2">Seleccione Fecha de Portadas Financieras:</span> <span class="label5"></span></td>
                          <td valign="top">&nbsp;</td>
                      </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td colspan="2" class="label3"><script>DateInput('fecha1', true, 'DD-MON-YYYY')</script></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><div align="right" class="label4"><?php echo $_GET['mensaje'] ?></div></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td><span class="label2">Buscar Cliente:</span><br />
                            <label>
                            <input name="txt_busca_cliente" type="text" class="textbox2" id="txt_busca_cliente" />
                            </label>                            <label>
                                <input type="submit" name="enviar" id="enviar" value="Buscar-&gt;" onclick="BuscaCliente()" />
                            </label>                          <br />
                            <br /></td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td><div class="resultados_busqueda_cliente" id="resultados_busqueda"><span class="label2">Introducir un nombre a buscar en el campo de texto de arriba:</span><br />
                              <br />
                            <br />
                          </div></td>
                          <td><div class="resultados_busqueda_cliente" id="correos_cliente"><span class="label2">Seleccionar un cliente en el cuadro de la izquierda:</span><br />
                          </div></td>
                        </tr>
                    </table>                </td>
            </tr>
        </table>
        <?php include("includes/init_menu_principal.php");?>
    </body>
</html>