<?php
/* 
 * Action para la insercion de una nueva cuenta  a la base de datos, para despues mandar a admin_cuentas.php
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

//obtenemos los datos del formulario y se meten en un arreglo para el objeto cuenta
$datos_cuenta = array("id_cuenta"=>"",
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
//revisamos si el username de la cuenta ya se encuentra registrado

if($base->exists_username_cuenta($_POST['username']))
{
    $base->close();
    //mandamos los datos del cliente a sesion para q no se pierdan
    $_SESSION['datos_nueva_cuenta'] = $datos_cuenta;
    header("Location:add_cuenta.php?id_empresa=".$_POST['id_empresa'].
                                    "&mensaje=El nombre de usuario: ".$_POST['username']." ya esta en uso
                                      &error=true");
    exit ();
}
else
{
    //creamos el objeto Cuenta
    $nueva_cuenta = new Cuenta($datos_cuenta);

    // hacemos la insercion a base de datos
    $base->execute_query($nueva_cuenta->SQL_insert_cuenta());

    $base->close();
    $_SESSION['datos_nueva_cuenta'] = 0;

    header("Location:admin_cuentas.php?id_empresa=".$_POST['id_empresa']."&mensaje=Se ha agregado una nueva cuenta!!");

    exit ();
}


//fin del action
?>
