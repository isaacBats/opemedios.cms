<?php
// llamamos los archivos a utilizar
include("phpdelegates/db_array.php");
//llamamos la clase DAO
include("phpdao/OpmDB.php");

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

if(isset($_POST['busqueda']) && $_POST['busqueda']== true)
{
    $base = new OpmDB(genera_arreglo_BD());
    //iniciamos conexion
    $base->init();

    $base->execute_query("SELECT id_noticia, id_tipo_fuente FROM noticia WHERE id_noticia=".$_POST['id_noticia']." LIMIT 1;");

    if($base->num_rows()== 0){
        $base->close();
        
        exit ();
    }
    else{
        $noticia = $base->get_row_assoc();
        $tipo_noticia = $noticia['id_tipo_fuente'];
        $base->close();
        
        exit ();
    }


}

?>
