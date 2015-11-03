<?php 
//Sestos headers son para evitar que haya cache en el sistema y siemrpe se mantenga la informacion actual
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
header("Cache-Control: no-cache, must-revalidate" ); 
header("Pragma: no-cache" );
header("Content-Type: text/xml; charset=utf-8");

// esta funcion quita caracteres no aceptados en un query
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType)
    {
        case "text":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "int":
            $theValue = ($theValue != "") ? intval($theValue) : "NULL";
            break;
        case "double":
            $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
            break;
        case "date":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "defined":
            $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
            break;
    }
    return $theValue;
}

// llamamos archivos de la base de datos
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");

// clases utilizadas
include("phpclasses/Empresa.php");
include("phpclasses/Cuenta.php");
include("phpclasses/Tema.php");

$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();

if (isset($_GET['action']))
{
    switch($_GET['action'])
    {
        case "busca_cliente":
            $output = "";
            if($_POST['text'] != "")
            {
                $base->execute_query(sprintf("SELECT * FROM empresa  WHERE nombre LIKE %s ORDER BY nombre LIMIT 10;", GetSQLValueString($_POST['text']."%" , "text")));
                $flag = 1;

                if($base->num_rows() <= 0 ) // Si no hay resultados
                {
                    $output = '<span class="label1"><strong>No se encontraron clientes con el criterio solicitado</strong></span>';
                }
                else
                {
                    //metemos las empresas cliente  en un arreglo
                    $arreglo_empresas = array();
                    while($row_empresa = $base->get_row_assoc())
                    {
                        $empresa = new Empresa($row_empresa);
                        $arreglo_empresas[$empresa->get_id()]=$empresa;
                    }

                    //generamos la salida

                    $new_back = array();

                    $new_back[] .= '<span class="label2">Seleccionar Cliente:</span><br /><br />';
                    $new_back[] .= '<table width="397" border="0" align="center">
                                        <tr class="header2">
                                            <td width="297"><div align="center">Cliente</div></td>
                                            <td width="90"><div align="center"></div></td>
                                        </tr>';
                    foreach ($arreglo_empresas as  $empresa)
                    {
                        $new_back[] .= '<tr>';
                        $new_back[] .= '<td class="row1"><span class="label1">'.$empresa->get_nombre().'</span></td>';
                        $new_back[] .= '<td class="row1">
                                            <input type="submit" name="seleccionar" id="seleccionar" value="Seleccionar" onclick="SeleccionaCliente('.$empresa->get_id().','.$_POST['id_noticia'].','.$_POST['id_tipo_fuente'].')"/>
                                        </td></tr>';
                    }
                    $new_back[].= '</table><br />';
                    $output = join("", $new_back);
                }
            }
            else
            {
                $flag = false;
                $output = '<span class="label5">Debe de introducir un nombre en el campo de búsqueda</span>';
            }
            break;

        case "get_info_cliente":
            $output = "";
            $base->execute_query(sprintf("SELECT * FROM empresa  WHERE id_empresa = %s ;", GetSQLValueString($_POST['id_cliente'],"int")));
            $flag = 1;

            //creamos el objeto empresa(cliente)
            $empresa = new Empresa($base->get_row_assoc());

            //generamos salida
            $new_back = array();

            $new_back[] .= '<span class="label2">La noticia aparecerá en el portal del siguiente cliente:</span><br /><br />';
            $new_back[] .= '<div align="center"><span class="label3">'.$empresa->get_nombre().'</span></div><br /><br />';
            $new_back[] .= '<span class="label2">&nbsp;&nbsp;Giro: </span><br /><span class="label1">&nbsp;&nbsp;&nbsp;'.$empresa->get_giro().'</span><br /><br />';
            $new_back[] .= '<span class="label2">&nbsp;&nbsp;Contacto: </span><br /><span class="label1">&nbsp;&nbsp;&nbsp;'.$empresa->get_contacto().'</span><br /><span class="label2">&nbsp;&nbsp;&nbsp;('.$empresa->get_email().')</span>';
            $output = join("", $new_back);
            break;

        case "muestra_cuentas":

            $base->execute_query(sprintf("SELECT * FROM cuenta  WHERE id_empresa = %s AND activo = 1 ORDER BY nombre,apellidos;", GetSQLValueString($_POST['id_cliente'], "int")));
            $flag = 1;

            if($base->num_rows() <= 0 ) // Si no hay resultados
            {
                $output = '<span class="label1"><strong>El cliente seleccionado no tiene cuentas activas.</strong><br /><br />Para enviar una noticia, el cliente seleccionado  debe tener al menos una cuenta activa</span>';
            }
            else // si hay cuentas
            {
                //metemos las cuentas  en un arreglo
                $arreglo_cuentas = array();
                while($row_cuenta = $base->get_row_assoc())
                {
                    $cuenta = new Cuenta($row_cuenta);
                    $arreglo_cuentas[$cuenta->get_id()]=$cuenta;
                }
                // Generamos un arreglo con las tendencias
                $base->execute_query("SELECT * FROM tendencia");
                $arreglo_tendencia = array();
                while($tendencia = $base->get_row_assoc())
                {
                    $arreglo_tendencia[$tendencia['id_tendencia']] = $tendencia["descripcion"];
                }

                // hacemos consulta de los temas del cliente
                $base->execute_query("SELECT * FROM tema WHERE id_empresa=".$_POST['id_cliente']." ORDER BY nombre;");

                if($base->num_rows() <= 0) // si no hay temas
                {
                    $output = '<span class="label1"><strong>El cliente seleccionado no tiene Temas dados de alta.</strong><br />Para asignar una noticia a un cliente debe tener al menos una tema</span>';
                }
                else // SI hay temas
                {
                    // metemos los temas en un arreglo

                    $arreglo_temas = array();
                    while($row_tema = $base->get_row_assoc())
                    {
                        $tema = new Tema($row_tema);
                        $arreglo_temas[$tema->get_id()]=$tema;
                    }

                    //generamos la salida
                    $new_back = array();

                    $new_back[] .= '<span class="label2">Seleccionar Parametros de Noticia para el cliente:</span><br /><br />';
                    $new_back[] .= '<form id="form1" name="form1" method="post" action="action_envia_noticia.php">';
                    $new_back[] .= '<table width="300" border="0" align="default">
                                    <tr>
                                        <td>
                                            <span class="label2">Tendencia:</span>
                                        </td>
                                        <td>
                                            <select name="id_tendencia" class="combo1" id="id_tendencia">';
                    foreach ($arreglo_tendencia as $value => $label)
                    {
                        $new_back[] .=             '<option value="'.$value.'">'.utf8_encode($label).'</option>';
                    }

                    $new_back[] .=             '</select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="label2">Tema:</span>
                                        </td>
                                        <td>
                                            <select name="id_tema" class="combo1" id="id_tema">';
                    foreach ($arreglo_temas as $tema)
                    {
                        $new_back[] .=             '<option value="'.$tema->get_id().'">'.$tema->get_nombre().'</option>';
                    }
                    $new_back[] .=         '</select>
                                        </td>
                                    </tr>
                                    </table>
                                    <br />
                                    <span class="label2">Seleccionar cuentas a las que se enviará la nota:</span><br /><br />
                                    <input name="id_noticia" type="hidden" id="id_noticia" value="'.$_POST['id_noticia'].'" />
                                    <input name="id_tipo_fuente" type="hidden" id="id_tipo_fuente" value="'.$_POST['id_tipo_fuente'].'" />
                                    <input name="id_empresa" type="hidden" id="id_empresa" value="'.$_POST['id_cliente'].'" />
                                    <div align="center" class="label2">Selecciona Todos<input type="checkbox" name="select_all" id="select_all" onclick="CheckAll()" /></div>
                                    <table width="400" border="0" align="center">
                                        <tr class="header2">
                                            <td><div align="center">Cuenta</div></td>
                                            <td><div align="center">e-mail</div></td>
                                            <td><div align="center">Enviar</div></td>
                                        </tr>';

                    foreach ($arreglo_cuentas as  $cuenta)
                    {
                        $new_back[] .= '<tr>';
                        $new_back[] .= '<td class="row1"><span class="label1">'.$cuenta->get_nombre_completo().'</span></td>';
                        $new_back[] .= '<td class="row1"><span class="label1">'.$cuenta->get_email().'</span></td>';
                        $new_back[] .= '<td align="center" class="row1"><input type="checkbox" name="envia[]" id="envia_'.$cuenta->get_id().'" value="'.$cuenta->get_id().'" /></td>';
                        $new_back[] .= '</tr>';

                    }

                    $new_back[] .= '</table>
                                <br />
                                <div align="center">
                                  <input type="submit" name="sumbit" id="submit" value="Enviar Noticia" />
                                </div>
                              </form>
                            <br />';
                    $output = join("", $new_back);

                }// end  "SI hay temas"

            }// end  "SI hay cuentas"

/*************************************************************************************************************
 *
 *              ACA COMENAMOS PRIMERAS PLANAS
 *
 *************************************************************************************************************/
        case "muestra_cuentas_primeraplana":

            $base->execute_query(sprintf("SELECT * FROM cuenta  WHERE id_empresa = %s AND activo = 1 ORDER BY nombre,apellidos;", GetSQLValueString($_POST['id_cliente'], "int")));
            $flag = 1;

            if($base->num_rows() <= 0 ) // Si no hay resultados
            {
                $output = '<span class="label1"><strong>El cliente seleccionado no tiene cuentas activas.</strong><br /><br />Para enviar una noticia, el cliente seleccionado  debe tener al menos una cuenta activa</span>';
            }
            else // si hay cuentas
            {
                //metemos las cuentas  en un arreglo
                $arreglo_cuentas = array();
                while($row_cuenta = $base->get_row_assoc())
                {
                    $cuenta = new Cuenta($row_cuenta);
                    $arreglo_cuentas[$cuenta->get_id()]=$cuenta;
                }
                // Generamos un arreglo con las tendencias
                $base->execute_query("SELECT * FROM tendencia");
                $arreglo_tendencia = array();
                while($tendencia = $base->get_row_assoc())
                {
                    $arreglo_tendencia[$tendencia['id_tendencia']] = $tendencia["descripcion"];
                }

                // hacemos consulta de los temas del cliente
                $base->execute_query("SELECT * FROM tema WHERE id_empresa=".$_POST['id_cliente']." ORDER BY nombre;");

                if($base->num_rows() <= 0) // si no hay temas
                {
                    $output = '<span class="label1"><strong>El cliente seleccionado no tiene Temas dados de alta.</strong><br />Para asignar una noticia a un cliente debe tener al menos una tema</span>';
                }
                else // SI hay temas
                {
                    // metemos los temas en un arreglo

                    $arreglo_temas = array();
                    while($row_tema = $base->get_row_assoc())
                    {
                        $tema = new Tema($row_tema);
                        $arreglo_temas[$tema->get_id()]=$tema;
                    }

                    //generamos la salida
                    $new_back = array();

                    $new_back[] .= '<span class="label2">Seleccionar Parametros de Primera Plana para el cliente:</span><br /><br />';
                    $new_back[] .= '<form id="form1" name="form1" method="post" action="action_envia_primeraplana.php?action=enviar">';
                    $new_back[] .= '<table width="300" border="0" align="default">
                                    <tr>
                                        <td>
                                            <span class="label2">Tendencia:</span>
                                        </td>
                                        <td>
                                            <select name="id_tendencia" class="combo1" id="id_tendencia">';
                    foreach ($arreglo_tendencia as $value => $label)
                    {
                        $new_back[] .=             '<option value="'.$value.'">'.utf8_encode($label).'</option>';
                    }

                    $new_back[] .=             '</select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="label2">Tema:</span>
                                        </td>
                                        <td>
                                            <select name="id_tema" class="combo1" id="id_tema">';
                    foreach ($arreglo_temas as $tema)
                    {
                        $new_back[] .=             '<option value="'.$tema->get_id().'">'.$tema->get_nombre().'</option>';
                    }
                    $new_back[] .=         '</select>
                                        </td>
                                    </tr>
                                    </table>
                                    <br />
                                    <span class="label2">Seleccionar cuentas a las que se enviará la nota:</span><br /><br />
                                    <input name="id_noticia" type="hidden" id="id_noticia" value="'.$_POST['id_noticia'].'" />
                                    <input name="id_tipo_fuente" type="hidden" id="id_tipo_fuente" value="'.$_POST['id_tipo_fuente'].'" />
                                    <input name="id_empresa" type="hidden" id="id_empresa" value="'.$_POST['id_cliente'].'" />
                                    <div align="center" class="label2">Selecciona Todos<input type="checkbox" name="select_all" id="select_all" onclick="CheckAll()" /></div>
                                    <table width="400" border="0" align="center">
                                        <tr class="header2">
                                            <td><div align="center">Cuenta</div></td>
                                            <td><div align="center">e-mail</div></td>
                                            <td><div align="center">Enviar</div></td>
                                        </tr>';

                    foreach ($arreglo_cuentas as  $cuenta)
                    {
                        $new_back[] .= '<tr>';
                        $new_back[] .= '<td class="row1"><span class="label1">'.$cuenta->get_nombre_completo().'</span></td>';
                        $new_back[] .= '<td class="row1"><span class="label1">'.$cuenta->get_email().'</span></td>';
                        $new_back[] .= '<td align="center" class="row1"><input type="checkbox" name="envia[]" id="envia_'.$cuenta->get_id().'" value="'.$cuenta->get_id().'" /></td>';
                        $new_back[] .= '</tr>';

                    }

                    $new_back[] .= '</table>
                                <br />
                                <div align="center">
                                  <input type="submit" name="sumbit" id="submit" value="Enviar a Cliente" />
                                </div>
                              </form>
                            <br />';
                    $output = join("", $new_back);

                }// end  "SI hay temas"

            }// end  "SI hay cuentas"
            break;
        default:
            break;

    }

}
if($flag == 1)
{
    $base->free_result();
}
$base->close();

echo $output;

?>