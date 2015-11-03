<?php
/**
 * La clase sector contiene la informacion de los sectores a los cuales pertenecen las noticias, asi como los metodos
 * necesarios para interactuar con la base de datos
 *
 * @author Josue
 */
class Sector
{
    private $id;
    private $nombre;
    private $descripcion;
    private $activo;

    function  __construct($datos)
    {
        $this->id = $datos['id_sector'];
        $this->nombre = $datos['nombre'];
        $this->descripcion = $datos['descripcion'];
        $this->activo = $datos['activo'];
    }

    function  __destruct()
    {
        ;
    }

    //establecen los atributos de la clase
    function set_nombre($nombre)
    {
        $this->nombre = $nombre;
    }
    function set_descripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    function set_activo($activo)
    {
        $this->activo = $activo;
    }
    //obtienen ls valores de la clase
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
    function get_activo()
    {
        return $this->activo;
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

    //arma el query de insercion de sector a base de datos
    function SQL_insert_sector()
    {
        $query_insert = sprintf("INSERT INTO sector (nombre, descripcion, activo)
                                 VALUES (%s, %s, %s)",
            $this->GetSQLValueString($this->nombre, "text"),
            $this->GetSQLValueString($this->descripcion, "text"),
            $this->GetSQLValueString($this->activo, "int"));
        return $query_insert;
    }

    // arma el query de actualizacion
    function SQL_update_sector()
    {
        $query_update =sprintf("UPDATE sector SET nombre=%s, descripcion=%s, activo=%s
                                WHERE id_sector=%s LIMIT 1;",
            $this->GetSQLValueString($this->nombre, "text"),
            $this->GetSQLValueString($this->descripcion, "text"),
            $this->GetSQLValueString($this->activo, "int"),
            $this->GetSQLValueString($this->id, "int"));

        return $query_update;
    }
    
    //arma el query de eliminacion de tema
    function SQL_delete_sector()
    {
        $query_delete =sprintf("DELETE FROM sector WHERE id_sector =%s LIMIT 1",
            $this->GetSQLValueString($this->id, "int"));
        return $query_delete;
    }
}

?>
