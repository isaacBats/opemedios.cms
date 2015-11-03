<?php
/* 
 * Action para la actualizacion de informacion de una cuenta en la base de datos
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Cuenta.php");

//usamos esta funcion para meter 1 o 0 en el campo activo
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType)
    {
        case "defined":
            $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
            break;
    }
    return $theValue;
}

//creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();

//obtenemos los datos del formulario y se meten en un arreglo para el objeto empresa
$datos_cuenta = array("id_cuenta"=>$_POST['id_cuenta'],
                      "nombre"=>$_POST['nombre'],
                      "apellidos"=>$_POST['apellidos'],
                      "cargo"=>$_POST['cargo'],
                      "telefono1"=>$_POST['telefono1'],
                      "telefono2"=>$_POST['telefono2'],
                      "email"=>$_POST['email'],
                      "comentario"=>$_POST['comentario'],
                      "username"=>$_POST['username'],
                      "password"=>$_POST['password'],
                      "activo"=>GetSQLValueString(isset($_POST['activo']) ? "true" : "", "defined","1","0"),
                      "id_empresa"=>$_POST['id_empresa']);


//revisamos si el username de la cuenta ha cambiado

$base->execute_query("SELECT username FROM cuenta WHERE id_cuenta =".$_POST['id_cuenta']);
$resultado =$base->get_row_assoc();
$username_actual= $resultado["username"];
$nuevo_username= $_POST['username'];


if($nuevo_username == $username_actual)
{
    //creamos el objeto Cuenta
    $cuenta = new Cuenta($datos_cuenta);

    // actualizamos
    $base->execute_query($cuenta->SQL_update_cuenta());

    $base->close();

    header("Location:admin_cuentas.php?id_empresa=".$_POST['id_empresa']."&mensaje=Se modifico la cuenta!!");

    exit ();
}
else
{
    if($base->exists_username_cuenta($_POST['username']))
    {
        $base->close();

        header("Location:edit_cuenta.php?id_empresa=".$_POST['id_empresa'].
                                    "&mensaje=El nombre de usuario: ".$_POST['username']." ya esta en uso
                                      &error=true
                                        &id_cuenta=".$_POST['id_cuenta']);
        exit ();
    }
    else
    {
        //creamos el objeto Cuenta
        $cuenta = new Cuenta($datos_cuenta);

        // actualizamos
        $base->execute_query($cuenta->SQL_update_cuenta());

        $base->close();

        header("Location:admin_cuentas.php?id_empresa=".$_POST['id_empresa']."&mensaje=Se modifico la cuenta!!");

        exit ();
    }
}


//fin del action

?>