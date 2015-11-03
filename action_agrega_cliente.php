<?php
/* 
 * Action para agregar un nuevo cliente al sistema, recoje los datos del formulario
 * arma objeto empresa y llama stored function para creacion de un nuevo cliente
 * despues nos manda la siguiente pantalla: ver cliente
 *
 *@autor: Josue Morado Manríquez
 *
 */

// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
include("phpdao/OpmDB.php");
include("phpclasses/Empresa.php");


//obtenemos los datos del formulario y se meten en un arreglo para el objeto empresa
$datos_empresa = array("id_empresa"=>"",
                       "nombre"=>$_POST['nombre'],
                       "direccion"=>$_POST['direccion'],
                       "telefono"=>$_POST['telefono'],
                       "contacto"=>$_POST['contacto'],
                       "email"=>$_POST['email'],
                       "giro"=>$_POST['giro'],
                       "logo"=>"",
                       "color_fondo"=>$_POST['color_fondo'],
                       "color_letra"=>$_POST['color_letra'],
                       "fecha_registro"=>date("Y-m-d"));

//creamos el objeto empresa

$nueva_empresa = new Empresa($datos_empresa);


//creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
$base = new OpmDB(genera_arreglo_BD());


//iniciamos conexion
if($base->init()!=0)
{
    //si no hay error y nos da la conexion seguimos:
    //introducimos a base de datos el nuevo cliente
    if($base->execute_query($nueva_empresa->SQL_nuevo_empresa())!=0)
    {
        //si hay exito, continuamos
        //obtenemos el valor del id que nos da la tabla y actualizamos el id del objeto empresa
        $id_registro = $base->get_row();
        $nueva_empresa->set_id($id_registro[0]);

        //cerramos la conexion
        $base->close();

        //terminamos. ahora redireccionamos a la siguiente pantalla: ver_cliente.php
        header("Location: ver_cliente.php?id_empresa=".$nueva_empresa->get_id());
        exit;
    }
    else
    {
        //no se ejecuto el query
        echo $base->get_error();
        $base->close();
    }
}
else
{
    // no se conecto a la base de datos
    echo $base->get_error();
    $base->close();
}

//fin del action
?>