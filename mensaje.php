<?php
//llamamos el codigo de sesion para usuario nivel 3 monitorista
include("phpdelegates/rest_access_3.php");
include("phpdelegates/logout.php");
//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");
include("phpdelegates/funciones.php");

// llamamos las clases a utilizar
include("phpclasses/SuperNoticia.php");
include("phpclasses/Archivo.php");
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
        <title>Enviar Noticia</title>
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
		clienteReq.open("POST", 'ajax_backend_envia_noticia_multiple.php?action=busca_cliente', true);
		clienteReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		var param = 'text=' + document.getElementById('txt_busca_cliente').value;
		// Borrar
		//var elementos = document.getElementsByName("id_noticia");
		//num = elementos.length;
		//alert("Busca Clientes:, "+num);
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
		clienteReq.open("POST", 'ajax_backend_envia_noticia.php?action=get_info_cliente', true);
		clienteReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		var param = 'id_cliente=' + id_cliente;
		clienteReq.onreadystatechange = handleContentCliente;
		clienteReq.send(param);
	}
	
	if (cuentaReq.readyState == 4 || cuentaReq.readyState == 0)
	{
		var ids_noticias = document.getElementsByName("id_noticia");
		var ids_tipo_fuente = document.getElementsByName("id_tipo_fuente");
		//alert("Hay en Selecciona clientes " + ids_noticias.length);
		document.getElementById('correos_cliente').innerHTML = '<span class="label2">Cargando...<img src="images/working.gif" /></span>';
		cuentaReq.open("POST", 'ajax_backend_envia_noticia_multiple.php?action=muestra_cuentas', true);
		cuentaReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		var param = 'id_cliente=' + id_cliente;
		for (i=0;i<ids_noticias.length;i++) {
			if (ids_noticias[i].value != '')
				param += '&id_noticias[]='+ids_noticias[i].value;
			if (ids_tipo_fuente[i].options.selectedIndex != 0)
				param += '&id_tipo_fuente[]='+ids_tipo_fuente[i].options.selectedIndex;
		}
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
 		if (e.name == 'select_all' || e.name == 'tipo_correo')
		{
			//alert(e.name+' Con este no se hace nada');
		}
		else
		{
			e.checked = document.form1.select_all.checked;
			//alert(e.name+' a este le damos: ' + document.form1.select_all.checked);
		}
		
 		
	}//end for
}// end function checkall
		
				
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
                            <td width="533" height="25" class="label2">Noticias --&gt; <span class="label4">Enviar Noticia a Cliente</span></td>
                          <td width="452" height="25"><span class="label2">Bienvenido:</span> <span class="label1"><?php echo $current_user->get_nombre_completo();?></span></td>
                        </tr>
                    </table>
                    <table width="95%" border="0">
						<tr>
							<td width="15">&nbsp;</td>
							<td>							
								<table width="90%" border="0">
								<tr align="center"><td colspan="4">Envió de Varias Noticias</td></tr>
								<?php
									echo '<tr><td>'.$_GET['mensaje'].'</td></tr>';
									echo '<tr><td>'.$_GET['error'].'</td></tr>';
								?>
								</table>								
							</td>
                        </tr>
					</table>
				</td>
            </tr>
        </table>
        <?php include("includes/init_menu_principal.php");?>
    </body>
</html>
