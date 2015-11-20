<?php
/* 
 * Envia las portadas financieras por correo electronico
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Empresa.php");
include("phpclasses/Cuenta.php");
include("phpclasses/Cartones.php");
include("phpdelegates/thumbnailer.php");



//funcion de Fecha
//
$fecha = date("Y-m-d",mktime(0,0,0,$_POST['fecha1_Month_ID'],$_POST['fecha1_Day_ID'],$_POST['fecha1_Year_ID']));
function getFecha_larga($f)
    {
        $arreglo_meses = array(
            1=>"Enero",
            2=>"Febrero",
            3=>"Marzo",
            4=>"Abril",
            5=>"Mayo",
            6=>"Junio",
            7=>"Julio",
            8=>"Agosto",
            9=>"Septiembre",
            10=>"Octubre",
            11=>"Noviembre",
            12=>"Diciembre",);

        $dia = substr($f,8,2);
        $mes = date("n",mktime(00,00,00,substr($f,5,2),01,2000));
        $año = substr($f,0,4);

        return $dia." de ".$arreglo_meses[$mes].", ".$año;
    }

// iniciamos conexion

$base = new OpmDB(genera_arreglo_BD());
$base->init();

if (isset($_POST['envia']))
{
    $arreglo_envia = $_POST['envia']; // contiene las Id de las cuentas a las que se enviara la noticia
    $ncuentas        = count($arreglo_envia);
}

if($ncuentas <= 0) // NO hay cuentas
{
    $base->close();
    header("Location: envio_cartones.php");
    exit();
}
else // SI hay cuentas
{
    //metemos los datos de las cuentas en un arreglo de objetos Cuenta
    $arreglo_cuentas = array();
    foreach($arreglo_envia as $id_cuenta)
    {
        $base->execute_query("SELECT * FROM cuenta WHERE id_cuenta = ".$id_cuenta." LIMIT 1;");
        $cuenta = new Cuenta($base->get_row_assoc());
        $arreglo_cuentas[$cuenta->get_id()]=$cuenta;
    }

    //leemos portadas financieras y creamos los thumbs
    $query = "SELECT a.fecha,a.imagen,a.id_carton id,a.titulo,b.nombre fuente
                FROM carton a, fuente b
                WHERE a.id_fuente = b.id_fuente and a.fecha = '".$fecha."'";
    $base->execute_query($query);
    $columna = 1;
    $url="data/cartones/";

    //$strw="";
    while($row_query = $base->get_row_assoc())
    {
        $thumbnail = new thumbnail($url.$row_query['imagen'],"data/thumbs",120,200,90,"_mailtn.");
        //$strw.=$row_query['imagen']."<br>";
    }
    //die($fecha."<br>".$strw);

    //armamos los elementos del correo
    //
    //Se itera el arreglo de las cuentas para ver a quien se le manda el correo

    $array_to = array();
    foreach($arreglo_cuentas as $cuenta)
    {
        $array_to[] .=$cuenta->get_nombre_completo().' <'.$cuenta->get_email().'>';
    }
    $to = join(",", $array_to);

    //headers
    $headers  = "MIME-Version: 1.0\n";
    $headers .= "Return-Path: <prensa@opemedios.com.mx>\n";
    $headers .= "Content-type: text/html; charset=utf-8\n"; //iso-8859-1
    $headers .= "From: Prensa OPEMEDIOS <prensa@opemedios.com.mx>";

    $subject = "Cartones del ".getFecha_larga($fecha);

    //empezamos con el mensaje

    $message = '<html>
                    <head>
                            <title>Operadora de Medios Informativos S.A. de C.V.</title>
                    </head>

                    <body background="http://www.opemedios.com.mx/html/images/fondo.gif" bgcolor="#1D6C9F" text="#2b4f71" link="#933838" vlink="#933838" alink="#2b4f71" leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0">

                    <div align="center"><font face="Tahoma" size="1"><br>
                    </font>


                    <!-- tabla top -->
                    <table width="600" border="0" cellspacing="0" cellpadding="0">
                    <tr bgcolor="#FFFFFF">
                            <td width="194" valign="top">
                            <img src="http://www.opemedios.com.mx/html/images/logo.gif" alt=""><br></td>
                            <td align="right" valign="top">
                            <img src="http://www.opemedios.com.mx/html/images/top.gif" alt=""><br>
                            <b><font face="Tahoma" size="2">Cartones del d&iacute;a '.getFecha_larga($fecha).'</font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

                    </tr>
                    </table>

                    <!-- tabla desarrollo -->
                    <table width="600" border="0" cellspacing="0" cellpadding="0">
                    <tr bgcolor="#FFFFFF">
                            <td colspan="3">
                            <div align="center"><br>

                    <table width="94%" border="0" cellspacing="4" cellpadding="4">

                    ';
    $query = "SELECT a.fecha,a.imagen,a.id_carton id,a.titulo,b.nombre fuente
                FROM carton a, fuente b
                WHERE a.id_fuente = b.id_fuente and a.fecha = '".$fecha."'";
    $base->execute_query($query);
        while($row_query = $base->get_row_assoc()) {
            if($columna == 1) {
                $message.= "<tr>";
            }
            $message.='<td bgcolor="#FFEDE1"><font face="Tahoma" size="2">
                       <div align="center"><br>
                       <a href="http://sistema.opemedios.com.mx/data/cartones/'.$row_query['imagen'].'"><img src="http://sistema.opemedios.com.mx/data/thumbs/'.$row_query['imagen'].'_mailtn.jpg" alt="" border="1"></a><br><br>
                       <b>'.$row_query['titulo'].'</b><br>'.$row_query['fuente'].'</div>
                    </td>
                   ';
            $columna ++;
            if($columna == 5) {
                $message.= '</tr>

                            <tr>
                                 <td colspan="6">
                                 <img src="http://www.opemedios.com.mx/intranet/images/trans.gif" width="1" height="15" alt=""><br>
                                 <img src="http://www.opemedios.com.mx/intranet/images/pix-azul.gif" width="100%" height="1" alt=""><br>
                                 <img src="http://www.opemedios.com.mx/intranet/images/trans.gif" width="1" height="15" alt=""><br>
                                 </td>
                            </tr> ';
                $columna = 1;
            }
        }
        if($columna != 5 && $columna != 1) {
            $message.= "</tr>";
        }

        $message.= '</table><br><br>

                    </td>
                    </tr>
                    </table>

                    <!-- tabla pie -->

                    <table width="600" border="0" cellspacing="0" cellpadding="0">
                    <tr bgcolor="#FFFFFF">
                            <td width="10">&nbsp;</td>
                            <td valign="top"><br><font face="Tahoma" size="1">
                            <b>Operadora de Medios Informativos, S.A. de C.V.</b><br>
                    contacto@opemedios.com.mx </font><br>

                    </td>
                    </tr>
                    <tr bgcolor="#FFFFFF">
                            <td colspan="2"><img src="http://www.opemedios.com.mx/html/images/pie.gif" alt=""><br></td>
                    </tr>
                    </table><br>

                    </div>

                    </body>
                    </html><br>
                    ';
 mail($to, $subject, $message, $headers);
 
$base->free_result();
$base->close();
header("Location: envio_cartones.php?&mensaje=El envío se realizo exitosamente");
exit();

}//else SI hay cuentas

?>
