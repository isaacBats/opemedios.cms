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
                                            <input type="submit" name="seleccionar" id="seleccionar" value="Seleccionar" onclick="SeleccionaCliente('.$empresa->get_id().')"/>
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

            $new_back[] .= '<span class="label2">Cliente Seleccionado:</span><br /><br />';
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
                

                    //generamos la salida
                    $new_back = array();
					
                    $new_back[] .= '<form id="form1" name="form1" method="post" action="action_envia_portada_financiera.php">';
                   
                    $new_back[] .= '<br />
                                    <span class="label2">Seleccionar cuentas a las que se enviarán las Portadas Financieras:</span><br /><br />
                                    <input name="id_empresa" type="hidden" id="id_empresa" value="'.$_POST['id_cliente'].'" />
                                    <input name="fecha1_Day_ID" type="hidden" id="fecha1_Day_ID" value="'.$_POST['fecha1_Day_ID'].'" />
                                    <input name="fecha1_Month_ID" type="hidden" id="fecha1_Month_ID" value="'.$_POST['fecha1_Month_ID'].'" />
                                    <input name="fecha1_Year_ID" type="hidden" id="fecha1_Year_ID" value="'.$_POST['fecha1_Year_ID'].'" />
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
                                  <input type="submit" name="sumbit" id="submit" value="Enviar Primeras Planas" />
                                </div>
                              </form>
                            <br />';
                    $output = join("", $new_back);



            }// end  "SI hay cuentas"

        default:
            break;

    }//end switch
}//end if issetget
if($flag == 1)
{
    $base->free_result();
}
$base->close();

echo $output;

?>