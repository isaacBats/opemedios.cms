<?php
/* 
 * Action para actualizar la informacion de un cliente (objeto de la clase empresa) en la base de datos
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Empresa.php");

//obtenemos los datos del formulario y se meten en un arreglo para el objeto empresa
$datos_empresa = array("id_empresa"=>$_POST['id_empresa'],
                       "nombre"=>$_POST['nombre'],
                       "direccion"=>$_POST['direccion'],
                       "telefono"=>$_POST['telefono'],
                       "contacto"=>$_POST['contacto'],
                       "email"=>$_POST['email'],
                       "giro"=>$_POST['giro'],
                       "logo"=>"",
                       "color_fondo"=>$_POST['color_fondo'],
                       "color_letra"=>$_POST['color_letra'],
                       "fecha_registro"=>$_POST['fecha_registro']);

//creamos el objeto empresa
$empresa = new Empresa($datos_empresa);

//creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
$base = new OpmDB(genera_arreglo_BD());


//iniciamos conexion
if($base->init()!=0)
{
    //si no hay error y nos da la conexion seguimos:
    //
    //actualizamos la informacion
    $base->execute_query($empresa->SQL_update_datos_empresa());

    //cerramos conexion
    $base->close();

    //una vez listos los datos, vamos a la pagina de ver_cliente para verificar los cambios
    header("Location: ver_cliente.php?id_empresa=".$empresa->get_id());
    exit;
}
else
{
    // no se conecto a la base de datos
    $base->close();
    echo $base->get_error();
}

//fin del action

?>
