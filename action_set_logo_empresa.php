<?php
/* 
 * Action que recibe el archivo de imagen y lo coloca en una carpeta del servidor
 * y que  registra en la base de datos
 * 
 */

//llamamos archivos extras a utilizar
include("phpdelegates/db_array.php");

// llamamos las clases a utilizar
include("phpclasses/Empresa.php");

//llamamos la clase  Data Access Object
include("phpdao/OpmDB.php");

//creamos un DAO para obtener los datos de la empresa dada mediante el metodo GET
//recibe como parametro el resultado de la funcion
$base = new OpmDB(genera_arreglo_BD());
//iniciamos conexion
$base->init();

//creamos el objeto empresa segun  la variable POST id_empresa
$base->execute_query("SELECT * FROM empresa WHERE id_empresa =".$_POST['id_empresa']);
//creamos el objeto empresa con los datos que nos regrese la consulta
$empresa = new Empresa($base->get_row_assoc());

$base->free_result();

$url = "data/empresas";


//si el logo de la empresa es default.jpg, solo se sube el archivo y se actualiza la base de datos
if($empresa->get_logo() == "default.jpg")
{

    $nombre_archivo_nuevo = $_FILES['archivo']['name'];
    $tamano_archivo_nuevo = $_FILES['archivo']['size'];

    //se crea el directorio de tipo de archivo si no existe

    if(!is_dir($url))
    {
        mkdir($url, 0777);
    }

    //Se comprueba tamaño de archivo y se coloca en su destino final

    if (!($tamano_archivo_nuevo < 100000)) //  100 Kb
    {
        $mensaje ="Error: El archivo excede el tamaño limite";
        $error_upload = true;
    }
    else
    {
        $error_upload= false;
        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $url."/ID".$empresa->get_id()."_".$nombre_archivo_nuevo))
        {
            $mensaje = "exito";
        }
        else
        {
            $mensaje = "Error en la copia del archivo a establacer";
            $error_upload = true;
        }
    }
    // si si se sube bien, hay que establecer en la base
    if($mensaje == "exito")
    {
        //se actualiza la informacion del logo del objeto empresa
        //y se hace la operacion en la base de datos
        $logo = "ID".$empresa->get_id()."_".$nombre_archivo_nuevo;
        $empresa->set_logo($logo);
        $base->execute_query($empresa->SQL_update_logo());
    }
    //cerramos conexion
    $base->close();
} // end if logo = default
else
{
    //borramos archivo fisicamente
    if (unlink($url."/".$empresa->get_logo()))  // si si se borra
    {
        // si hay exito regresamos el valor de la base a default
        $empresa->set_logo("default.jpg");
        $base->execute_query($empresa->SQL_update_logo());

        // continuamos con la copia del archivo nuevo al servidor
        $nombre_archivo_nuevo = $_FILES['archivo']['name'];
        $tamano_archivo_nuevo = $_FILES['archivo']['size'];

        //se crea el directorio de tipo de archivo si no existe

        if(!is_dir($url))
        {
            mkdir($url, 0777);
        }

        //Se comprueba tamaño de archivo y se coloca en su destino final

        if (!($tamano_archivo_nuevo < 100000)) // 100 Kb
        {
            $mensaje ="Error: El archivo excede el tamaño limite";
            $error_upload = true;
        }
        else
        {
            $error_upload= false;
            if (move_uploaded_file($_FILES['archivo']['tmp_name'], $url."/ID".$empresa->get_id()."_".$nombre_archivo_nuevo))
            {
                $mensaje = "exito";
            }
            else
            {
                $mensaje = "Error en la copia del archivo a establecer";
                $error_upload = true;
            }
        }

        // si si se sube bien, hay que establecer en la base sdethr
        if($mensaje == "exito")
        {
            //se actualiza la informacion del logo del objeto empresa
            //y se hace la operacion en la base de datos
            $logo = "ID".$empresa->get_id()."_".$nombre_archivo_nuevo;
            $empresa->set_logo($logo);
            $base->execute_query($empresa->SQL_update_logo());
        }

    } // end sis i se borra
    else // si no se borra
    {
        $mensaje= 'Error!  No se pudo borrar el archivo'.$archivo_establecido;
        $error_delete = true;
    }

    //cerramos conexion
    $base->close();


}//end else logo=default



// si hubo error en cualquier parte mandamos a que suba el archivo otra vez, si no  pues ya a ver la noticia

if($error_delete == true || $error_upload == true || $mensaje!= "exito")
{
    header("Location:set_logo_empresa.php?id_empresa=".$empresa->get_id());
    exit ();
}
else
{
    header("Location:ver_cliente.php?id_empresa=".$empresa->get_id());
    exit ();
}
?>
