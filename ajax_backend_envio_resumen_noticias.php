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
include("phpclasses/Noticia.php");
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
                    $new_back[] .= '<table width="415" border="0" align="center">
                                        <tr class="header2">
                                            <td width="320"><div align="center">Cliente</div></td>
                                            <td width="85"><div align="center"></div></td>
                                        </tr>';
                    foreach ($arreglo_empresas as  $empresa)
                    {
                        $new_back[] .= '<tr>';
                        $new_back[] .= '<td class="row1"><span class="label1">'.$empresa->get_nombre().'</span></td>';
                        $new_back[] .= '<td class="row1">
                                            <input type="submit" name="seleccionar" id="seleccionar" value="Ver Noticias" onclick="SeleccionaCliente('.$empresa->get_id().')"/>
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

            $new_back[] .= '<span class="label2">Se muestran a continuacion las noticias asignadas al portal de:</span><br /><br />';
            $new_back[] .= '<div align="center"><span class="label3">'.$empresa->get_nombre().'</span></div>';
            $new_back[] .= '<span class="label2">&nbsp;&nbsp;Giro: </span><br /><span class="label1">&nbsp;&nbsp;&nbsp;'.$empresa->get_giro().'</span><br />';
            $output = join("", $new_back);
            break;



        case "muestra_noticias":

            $base->execute_query("SELECT * FROM tema WHERE id_empresa=".$_POST['id_cliente']);
            $flag = 1;

            if($base->num_rows() <= 0 ) // Si no hay temas
            {
                $output = '<span class="label1"><strong>El cliente seleccionado no tiene Temas para mostrar.</strong></span>';
            }
            else // si hay temas
            {
                //metemos los temas en un arreglo
                $arreglo_temas = array();
                while($row_tema = $base->get_row_assoc())
                {
                    $tema = new Tema($row_tema);
                    $arreglo_temas[$tema->get_id()]=$tema;
                }

                //generamos la salida
                $new_back = array();
                $new_back[] .='';

                foreach ($arreglo_temas as  $tema)
                {
                    $new_back[] .= '<hr />';
                    $new_back[] .= '<span class="label3">'.$tema->get_nombre().'</span><br />';
                    $flag2 = 1;
                    $base->execute_query2(sprintf("SELECT
                                              asigna.id_empresa AS id_empresa,
                                              empresa.nombre AS empresa,
                                              noticia.fecha AS fecha,
                                              asigna.id_noticia AS id_noticia,
                                              noticia.id_tipo_fuente AS id_tipo_fuente,
                                              noticia.encabezado AS encabezado,
                                              noticia.id_fuente AS id_fuente,
                                              fuente.nombre AS fuente,
                                              noticia.id_seccion AS id_seccion,
                                              seccion.nombre AS seccion,
                                              asigna.id_tema AS id_tema,
                                              tema.nombre AS tema,
                                              asigna.id_tendencia AS id_tendencia,
                                              tendencia.descripcion AS tendencia

                                          FROM
                                              asigna
                                              INNER JOIN noticia ON (asigna.id_noticia = noticia.id_noticia)
                                              INNER JOIN empresa ON (asigna.id_empresa = empresa.id_empresa)
                                              INNER JOIN tema ON (asigna.id_tema = tema.id_tema)
                                              INNER JOIN fuente ON (noticia.id_fuente = fuente.id_fuente)
                                              INNER JOIN seccion ON (noticia.id_seccion = seccion.id_seccion)
                                              INNER JOIN tendencia ON (asigna.id_tendencia = tendencia.id_tendencia)
                                          WHERE
                                              asigna.id_tema = %s AND
                                              asigna.id_empresa = %s AND
                                              noticia.fecha BETWEEN %s AND %s
                                          ORDER BY
                                              id_noticia DESC;",
                            GetSQLValueString($tema->get_id(), "int"),
                            GetSQLValueString($_POST['id_cliente'], "int"),
                            GetSQLValueString(date("Y-m-d",mktime(0,0,0,$_POST['fecha1_Month_ID'],$_POST['fecha1_Day_ID'],$_POST['fecha1_Year_ID'])), "date"),
                            GetSQLValueString(date("Y-m-d",mktime(0,0,0,$_POST['fecha2_Month_ID'],$_POST['fecha2_Day_ID'],$_POST['fecha2_Year_ID'])), "date")));

                    if($base->num_rows2() <= 0 ) // Si no hay noticias de ese tema
                    {
                        $new_back[] .= '<br /><span class="label2"><strong>No hay noticias de éste tema.</strong></span><br /><br />';
                    }
                    else // si hay noticias
                    {
                        //metemos las noticias  en un arreglo
                        $arreglo_noticias = array();
                        while($row_noticia = $base->get_row_assoc2())
                        {
                            $noticia = new Noticia($row_noticia);
                            $arreglo_noticias[$noticia->getId()]=$noticia;
                        }

                        $new_back[] .= '<table width="950" border="0" align="center">
                                      <tr class="header2">
                                        <td><div align="center">Fecha</div></td>
                                        <td><div align="center">Noticia</div></td>
                                        <td><div align="center">Fuente</div></td>
                                        <td><div align="center">Enviar</div></td>
                                      </tr>';

                        foreach ($arreglo_noticias as  $noticia)
                        {
                            $new_back[] .= '<tr>';
                            $new_back[] .= '<td class="row1"><div align="center">'.$noticia->getFecha().'</div></td>';
                            $new_back[] .= '<td class="row1"><div align="center"><a href="ver_noticia_selector.php?id_tipo_fuente='.$noticia->getId_tipo_fuente().'&id_noticia='.$noticia->getId().'" target="_self" class="label5">'.$noticia->getId().'</a><br />
                    '.$noticia->getEncabezado().'</div></td>';
                            $new_back[] .= '<td class="row1"><div align="center">'.$noticia->getFuente().'<br />
                    Seccion: '.$noticia->getSeccion().'</div></td>';
                            $new_back[] .= '<td class="row1"><div align="center" id="div_tema_'.$noticia->getId().'">'.$noticia->getTema().'</div></td>';
                            $new_back[] .= '<td class="row1"><div align="center" id="div_tendencia_'.$noticia->getId().'">'.$noticia->getTendencia().'</div></td>';
                          
                            $new_back[] .= '</tr>';

                        }

                        $new_back[] .= '</table>';



                    } //  si hay noticias
                } // foreach arreglo temas
                $output = join("", $new_back);

            }//end else   SI hay temas

            break;

        case "get_temas":
            $output="";
            $base->execute_query("SELECT * FROM tema WHERE id_empresa=".$_POST['id_empresa']);
            $flag=1;

            //metemos los temas en un arreglo
            $arreglo_temas = array();
            while($row_tema = $base->get_row_assoc())
            {
                $tema = new Tema($row_tema);
                $arreglo_temas[$tema->get_id()]=$tema;
            }

            //generamos la salida
            $new_back = array();
            $new_back[] .='<select name="id_tema_'.$_POST['id_noticia'].'" class="combo2" id="id_tema_'.$_POST['id_noticia'].'">';
            foreach($arreglo_temas as $tema)
            {
                if($tema->get_id() == $_POST['id_tema'])
                {
                    $new_back[].= '<option value="'.$tema->get_id().'" selected="selected">'.$tema->get_nombre().'</option>';
                }
                else
                {
                    $new_back[].= '<option value="'.$tema->get_id().'">'.$tema->get_nombre().'</option>';
                }
            }
            $new_back[].= '</select>';

            $output = join("", $new_back);

            break;

        case "get_tendencias":
            $output="";
            $base->execute_query("SELECT * FROM tendencia");
            $flag=1;

            //metemos los temas en un arreglo
            $arreglo_tendencias = array();
            while($row_tendencia = $base->get_row_assoc())
            {
                $arreglo_tendencias[$row_tendencia['id_tendencia']]=$row_tendencia['descripcion'];
            }

            //generamos la salida
            $new_back = array();
            $new_back[] .='<select name="id_tendencia_'.$_POST['id_noticia'].'" class="combo2" id="id_tendencia_'.$_POST['id_noticia'].'">';
            foreach($arreglo_tendencias as $value=>$label)
            {
                if($value == $_POST['id_tendencia'])
                {
                    $new_back[].= '<option value="'.$value.'" selected="selected">'.$label.'</option>';
                }
                else
                {
                    $new_back[].= '<option value="'.$value.'">'.$label.'</option>';
                }
            }
            $new_back[].= '</select>';

            $output = join("", $new_back);

            break;

        case "get_boton":
            $output="";
            
            //generamos la salida
            $new_back = array();
            $new_back[] .='<input type="submit" name="actualizar" id="actualizar_'.$_POST['id_noticia'].'" value="Actualizar" onclick="ActualizaNoticia('.$_POST['id_noticia'].','.$_POST['id_empresa'].','.$_POST['id_tema'].','.$_POST['id_tendencia'].')" />';
            $output = join("", $new_back);

            break;

        case "update":
            $output = "";
            $base->execute_query(sprintf("UPDATE asigna SET "));



            break;



        default:
            break;

    } // end switch

}
if($flag == 1)
{
    $base->free_result();
}
if($flag2 == 1)
{
    $base->free_result2();
}
$base->close();

echo $output;

?>