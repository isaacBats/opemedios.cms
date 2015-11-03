<?php
/**
 * cada empresa tiene  varios temas de los cuales quiere las noticias
 * entonces el atributo temas de la clase empresa sera una arreglo de objetos tema
 *
 * @author Josue Morado Manríquez
 */

class Tema
{
    private $id;
    private $nombre;
    private $descripcion;
    private $id_empresa;

    function  __construct($datos)
    {
        $this->id = $datos['id_tema'];
        $this->nombre = $datos['nombre'];
        $this->descripcion = $datos['descripcion'];
        $this->id_empresa = $datos['id_empresa'];
    }

    function  __destruct()
    {
        ;
    }

    //Establecen los atributos de la clase
    function set_nombre($nombre)
    {
        $this->nombre = $nombre;
    }
    function set_descripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    //obtienen los valores de los atributos de la clase
    function get_id()
    {
        return $this->id;
    }
    function get_nombre()
    {
        return $this->nombre;
    }

    function get_descripcion()
    {
        return $this->descripcion;
    }
    function get_id_empresa()
    {
        return $this->id_empresa;
    }
// funcion de validacion de valores para que entre en el query
    private function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
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

// arma el query de insercion  a la base de datos
    function SQL_insert_tema()
    {
         $query_insert = sprintf("INSERT INTO tema (nombre, descripcion, id_empresa) VALUES (%s, %s, %s)",
                         $this->GetSQLValueString($this->nombre, "text"),
                         $this->GetSQLValueString($this->descripcion, "text"),
                         $this->GetSQLValueString($this->id_empresa, "int"));
        return $query_insert;
    }
//arma el query de actualizacion de tema
    function SQL_update_tema()
    {
        $query_update =sprintf("UPDATE tema SET nombre=%s, descripcion=%s WHERE id_tema=%s LIMIT 1",
                       $this->GetSQLValueString($this->nombre, "text"),
                       $this->GetSQLValueString($this->descripcion, "text"),
                       $this->GetSQLValueString($this->id, "int"));
        return $query_update;
    }
//arma el query de eliminacion de tema
    function SQL_delete_tema()
    {
        $query_delete =sprintf("DELETE FROM tema WHERE id_tema =%s LIMIT = 1",
                       $this->GetSQLValueString($this->id, "int"));
        return $query_delete;
    }
}

?>