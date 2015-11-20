<?php
/*
 * Action para asignar una noticia al portal de un cliente, a la vez de mandar un correo a todos las cuentas seleccionadas
 *
 *
 *@autor: Josue Morado Manríquez
 *
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Empresa.php");
include("phpclasses/Cuenta.php");
include("phpclasses/Noticia.php");
include("phpclasses/NoticiaElectronico.php");
include("phpclasses/NoticiaExtra.php");
include("phpclasses/Archivo.php");
include("phpclasses/Ubicacion.php");

// iniciamos conexion

$base = new OpmDB(genera_arreglo_BD());
$base->init();

// primero asignaremos la noticia, si ya esta asignada, se ignora la accion
//$base->execute_query("INSERT IGNORE INTO `asigna` (`id_noticia`, `id_empresa`, `id_tema`, `id_tendencia`)
//                                    VALUES (".$_POST['id_noticia'].",".$_POST['id_empresa'].",".$_POST['id_tema'].",".$_POST['id_tendencia'].");");

//Ahora se mandara la noticia por correo a las cuentas seleccionadas
//
//Segun el tipo de fuente  al q pertenezca la noticia sera el formato y los datos q se necesiten
//
// Se verifica que haya cuentas a quien mandar, si no hay, redireccionamos. Si hay, revisamos que tipo de noticia es para armar el mail

if (isset($_POST['envia']))
{
    $arreglo_envia = $_POST['envia']; // contiene las Id de las cuentas a las que se enviara la noticia
    $ncuentas        = count($arreglo_envia);
}

if($ncuentas <= 0) // NO hay cuentas
{
    header("Location: envia_primeraplana.php?id_pp=".$_POST['id_noticia']);
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
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n"; //iso-8859-1
    $headers .= 'From: Noticias OPEMEDIOS <noticias@operamedios.com.mx>' . "\r\n";

    //ahora segun el tipo de fuente, se generará el mensaje en HTML


    if (isset($_POST['id_tipo_fuente']))
    {
        switch(true)
        {
            case ($_GET['action'] == 'enviar'): // si la noticia es de medio electronico

                //hacemos consulta para la creacion del objeto NoticiaElectronico
                $base->execute_query("SELECT
                                        FU.id_fuente,
                                        PP.id_primera_plana AS id,
                                        PP.fecha, PP.imagen,
                                        FU.nombre as fuente
                                        FROM
                                        PRIMERA_PLANA AS PP,
                                        FUENTE AS FU
                                        WHERE 1=1
                                        AND PP.id_fuente = FU.id_fuente
                                        AND pp.id_primera_plana = ".$_POST['id_noticia'].";");

                //creamos el objeto NoticiaElectronico con los datos que nos regrese la consulta
                $result = $base->get_row_assoc();

                $message = '
                            <html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title></head>

<body>
<table width="949" border="0">
  <tr>
    <td colspan="2" align="left"><strong>PRIMERA PLANA</strong></td>
    <td width="445">&nbsp;</td>
  </tr>
  <tr>
    <td width="136" align="right" >FECHA:&nbsp; </td>
    <td width="3059">&nbsp;&nbsp; '.$result['fecha'].'</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right">FUENTE:&nbsp; </td>
    <td>&nbsp;&nbsp; '.$result['fuente'].'</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center"><img img src="data/primera'.$result['imagen'].'" width="3648" height="2736" alt="PRIMERA_PLANA" longdesc="PRIMERA PLANA">&nbsp; &nbsp;</td>
  </tr>
</table>
<div align="center"><img src="data/primera_plana/'.$result['imagen'].'" width="3648" height="2736" alt="PRIMERA_PLANA" longdesc="PRIMERA PLANA">
</div>
</body>
</html>
                            ';



                break;

            default:
                break;
            
        } // end switch

        $subject = 'envio automatico';

        mail($to, $subject, $message, $headers);

    } // end if isset id_tipo_fuente


    $base->free_result();
    $base->close();
    header("Location: enviar_primeraplana.php?id_pp=".$_POST['id_noticia']."&mensaje=Se ha enviado la Plana");
    exit();

} // end else SI hay cuentas



?>
