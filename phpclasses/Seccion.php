<?php
/**
 * Contiene los atributos y metodos de las secciones de una fuente del sistema
 *
 * @author Josue
 */
class Seccion
{
    private $id;
    private $nombre;
    private $descripcion;
    private $activo;
    private $id_fuente;
    
    function  __construct($datos)
    {
        $this->id = $datos['id_seccion'];
        $this->nombre = $datos['nombre'];
        $this->descripcion = $datos['descripcion'];
        $this->activo = $datos['activo'];
        $this->id_fuente = $datos['id_fuente'];
    }

    function  __destruct()
    {
        ;
    }
    
    // obtienen los valores de los aributos
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
    function get_id_fuente()
    {
        return $this->id_fuente;
    }
    function get_activo_txt()
    {
        if($this->activo == 1)
        {
            return "Sí";
        }
        else
        {
            return "No";
        }
    }

    //establecen los valores a los atributos
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
    function set_id_fuente($id_fuente)
    {
        $this->id_fuente = $id_fuente;
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

    function SQL_insert_seccion()
    {
        $query_insert = sprintf("INSERT INTO seccion (nombre, descripcion, activo, id_fuente)
                                 VALUES (%s, %s, %s, %s)",
                         $this->GetSQLValueString($this->nombre, "text"),
                         $this->GetSQLValueString($this->descripcion, "text"),
                         $this->GetSQLValueString($this->activo, "int"),
                         $this->GetSQLValueString($this->id_fuente, "int"));
        return $query_insert;
    }

    // arma el query de actualizacion
  function SQL_update_seccion()
    {
        $query_update =sprintf("UPDATE seccion SET nombre=%s, descripcion=%s, activo=%s
                                                WHERE id_seccion=%s LIMIT 1",
                       $this->GetSQLValueString($this->nombre, "text"),
                       $this->GetSQLValueString($this->descripcion, "text"),
                       $this->GetSQLValueString($this->activo, "int"),
                       $this->GetSQLValueString($this->id, "int"));

        return $query_update;
    }
//arma el query de eliminacion de tema
    function SQL_delete_seccion()
    {
        $query_delete =sprintf("DELETE FROM seccion WHERE id_seccion =%s LIMIT = 1",
                       $this->GetSQLValueString($this->id, "int"));
        return $query_delete;
    }
}
?>
