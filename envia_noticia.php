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

$arreglo_carpetas = array(1=>"television",
						  2=>"radio",
						  3=>"periodico",
						  4=>"revista",
						  5=>"internet");

//recibe como parametro el resultado de la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();
//obtenemos la informacion del usuario y creamos el objeto
$base->execute_query("SELECT * FROM usuario WHERE username = '".$session_usr."'");// session_usr se encuentra en los includes de sesion
$current_user = new Usuario($base->get_row_assoc());


//hacemos consulta para la creacion del objeto NoticiaExtra
$base->execute_query("SELECT
                          noticia.id_noticia AS id_noticia,
                          noticia.encabezado AS encabezado,
                          noticia.sintesis AS sintesis,
                          noticia.autor AS autor,
                          noticia.fecha AS fecha,
                          noticia.comentario AS comentario,
                          noticia.id_tipo_fuente AS id_tipo_fuente,
                          noticia.id_fuente AS id_fuente,
                          noticia.id_seccion AS id_seccion,
                          noticia.id_sector AS id_sector,
                          noticia.id_tipo_autor AS id_tipo_autor,
                          noticia.id_genero AS id_genero,
                          fuente.nombre AS fuente,
                          seccion.nombre AS seccion,
                          sector.nombre AS sector,
                          tipo_fuente.descripcion AS tipo_fuente,
                          tipo_autor.descripcion AS tipo_autor,
                          genero.descripcion AS genero,
                          tendencia.descripcion AS tendencia,
                          noticia.id_tendencia_monitorista AS id_tendencia
                         
                    FROM
                         noticia
                         INNER JOIN tipo_fuente ON (noticia.id_tipo_fuente=tipo_fuente.id_tipo_fuente)
                         INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                         INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                         INNER JOIN sector ON (noticia.id_sector=sector.id_sector)
                         INNER JOIN tipo_autor ON (noticia.id_tipo_autor=tipo_autor.id_tipo_autor)
                         INNER JOIN genero ON (noticia.id_genero=genero.id_genero)
                         INNER JOIN tendencia ON (noticia.id_tendencia_monitorista=tendencia.id_tendencia)
                    WHERE noticia.id_noticia = ".$_GET['id_noticia']." LIMIT 1;");

//creamos el objeto Noticia con los datos que nos regrese la consulta
$noticia = new SuperNoticia($base->get_row_assoc());


//hacemos consulta para obtener los datos del archivo principal y creamos objeto Archivo para asignarlo a la noticia
                        $base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 1 LIMIT 1;");

                        if($base->num_rows() == 0) {
                            $isprincipal = 0;
                        }
                        else {
                            $isprincipal = 1;
                            $principal = new Archivo($base->get_row_assoc());
                        }


                        //hacemos consulta para obtener los archivos secundarios  de la noticia
                        //por cada archivo que obtengamos generamos un objeto Archivo y lo metemos a un arreglo
                        $arreglo_secundarios = array();
                        $base->execute_query("SELECT * FROM adjunto WHERE id_noticia = ".$noticia->getId()." AND principal = 0;");

                        if($base->num_rows() == 0) {
                            $issecundarios = 0;
                        }
                        else {
                            
                            $issecundarios = $base->num_rows();
                            while($row_archivo = $base->get_row_assoc()) {
                                $archivo = new Archivo($row_archivo);
                                $arreglo_secundarios[$archivo->getId()]=$archivo;
                            }
                        }
						
						

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
function BuscaCliente(id_noticia,id_tipo_fuente) {
	if (clienteReq.readyState == 4 || clienteReq.readyState == 0)
	 {
	 	document.getElementById('resultados_busqueda').innerHTML = '<span class="label2">Cargando...<img src="images/working.gif" /></span>';
		clienteReq.open("POST", 'ajax_backend_envia_noticia.php?action=busca_cliente', true);
		clienteReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		var param = 'text=' + document.getElementById('txt_busca_cliente').value;
		param += '&id_noticia='+id_noticia;
		param += '&id_tipo_fuente='+id_tipo_fuente;
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

function SeleccionaCliente(id_cliente,id_noticia,id_tipo_fuente)
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
		document.getElementById('correos_cliente').innerHTML = '<span class="label2">Cargando...<img src="images/working.gif" /></span>';
		cuentaReq.open("POST", 'ajax_backend_envia_noticia.php?action=muestra_cuentas', true);
		cuentaReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		var param = 'id_cliente=' + id_cliente;
		param += '&id_noticia='+id_noticia;
		param += '&id_tipo_fuente='+id_tipo_fuente;
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
                    <table width="1000" border="0">
                  <tr>
                            <td width="15">&nbsp;</td>
                            <td width="490">&nbsp;</td>
                    <td width="481">&nbsp;</td>
                      </tr>
                        <tr>
                            <td valign="top">&nbsp;</td>
                          <td valign="top"><span class="label2">Noticia #:</span> <span class="label5"><?php echo $noticia->getid(); ?></span></td>
                          <td valign="top"><div align="right" class="label4"><?php echo $_GET['mensaje'] ?></div></td>
                      </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td colspan="2" class="label3"><?php echo $noticia->getEncabezado(); ?></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="2"><span class="label1"><?php echo WordLimiter($noticia->getSintesis(), 100); ?></span></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="2" class="label2"><?php echo $noticia->getFuente()." (".$noticia->getSeccion().") "; ?></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <?php if ($isprincipal == 1){ ?>
                          <td colspan="2" class="label2">Archivo Principal: <?php echo '<a target="_blank" href="http://sistema.opemedios.mx/data/noticias/'.$arreglo_carpetas[$noticia->getId_tipo_fuente()].'/'.$principal->getNombre_archivo().'">http://sistema.opemedios.mx/data/noticias/'.$arreglo_carpetas[$noticia->getId_tipo_fuente()].'/'.$principal->getNombre_archivo(); ?></td>
                          <?php  }  // End If is principal?> 
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td colspan="2"><span class="label2">Secundarios:<br /> 
                            <?php                     
							foreach($arreglo_secundarios as $sec) {
								echo '<a target="_blank" href="http://sistema.opemedios.mx/data/noticias/'.$arreglo_carpetas[$noticia->getId_tipo_fuente()].'/'.$sec->getNombre_archivo().'">http://sistema.opemedios.mx/data/noticias/'.$arreglo_carpetas[$noticia->getId_tipo_fuente()].'/'.$sec->getNombre_archivo().'</a><br>';
								
							}

							?>
                            </span><br /></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td><span class="label2">Buscar Cliente:</span><br />
                            <label>
                            <input name="txt_busca_cliente" type="text" class="textbox2" id="txt_busca_cliente" />
                            </label>                            <label>
                            <input type="submit" name="enviar" id="enviar" value="Buscar-&gt;" onclick="BuscaCliente(<?php echo $noticia->getId(); ?>,<?php echo $noticia->getId_tipo_fuente(); ?>)" />
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