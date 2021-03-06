<?php
/**
 * Clase Usuario: contienen al informacion de los usuarios que utilizaran el sistema,
 * para el modulo primera plana
 *
 * @author Roberto
 */
class PrimeraPlana
{
    private $id;
    private $fecha;
    private $imagen;
    private $id_fuente;

    function  __construct($datos)
    {
        $this->id = $datos['id_primera_plana'];
        $this->fecha = $datos['fecha'];
        $this->imagen = $datos['imagen'];
        $this->id_fuente = $datos['id_fuente'];
    }

    function __destruct()
    {
        ;
    }

    // obtienen los datos del objeto

    function get_id()
    {
        return $this->id;
    }
    function get_fecha()
    {
        return $this->fecha;
    }
    function get_imagen()
    {
        return $this->imagen;
    }
    function get_id_fuente()
    {
        return $this->id_fuente;
    }
  

    //establecen los atributos de la clase

    function set_id($id)
    {
        $this->id = $id;
    }
    function set_fecha($fecha)
    {
        $this->fecha = $fecha;
    }
    function set_imagen($imagen)
    {
        $this->imagen =$imagen;
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

    function SQL_insert()
    {
        $query_insert = sprintf("INSERT INTO primera_plana (fecha, imagen,id_fuente)
                                 VALUES (%s, %s, %s)",
            $this->GetSQLValueString($this->fecha, "date"),
            $this->GetSQLValueString($this->imagen, "text"),
            $this->GetSQLValueString($this->id_fuente, "int"));
        return $query_insert;
    }
    // arma el query de actualizacion

    function SQL_update()
    {
        $query_update =sprintf("UPDATE primera_plana SET fecha=%s, imagen=%s, id_fuente=%s
                                                WHERE id_primera_plana=%s LIMIT 1",
            $this->GetSQLValueString($this->fecha, "date"),
            $this->GetSQLValueString($this->imagen, "text"),
            $this->GetSQLValueString($this->id_fuente, "int"),
            $this->GetSQLValueString($this->id, "int"));

        return $query_update;
    }

    //arma el query de eliminacion de tema
    function SQL_delete()
    {
        $query_delete =sprintf("DELETE FROM primera_plana WHERE id_primera_plana =%s LIMIT 1",
            $this->GetSQLValueString($this->id, "int"));
        return $query_delete;
    }
}
?>
