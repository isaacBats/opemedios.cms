<?php
/**
 * contiene todos los datos necesarios de la relacion fuente, horario, mes tiempo y precio
 *
 * @author Josue Morado Manríquez
 */
class TarifaElectronico
{
    private $id_fuente;
    private $horario;
    private $id_mes;
    private $tiempo;
    private $precio;
    private $precio_noticia; // solo se usa al momento de ver cuanto cuesta una noticia

    function  __construct($datos)
    {
        $this->id_fuente = $datos['id_fuente'];
        $this->horario ="";
        $this->id_mes = $datos['id_mes'];
        $this->tiempo = $datos['tiempo'];
        $this->precio = $datos['precio'];
        $this->precio_noticia = "";
    }

    function  __destruct()
    {
        ;
    }



// establece el horario de la tarifa
    function set_horario(Horario $horario)
    {
        $this->horario = $horario;
    }

    function setPrecio_noticia($precio)
    {
        $this->precio_noticia = $precio;
    }

    //obtienen los valores de los atributos

    function getPrecio_noticia()
    {
        return  $this->precio_noticia;
    }
    function get_id_fuente()
    {
        return $this->id_fuente;
    }
    function get_mes()
    {
        $arreglo_meses = array(
            1=>"Enero",
            2=>"Febrero",
            3=>"Marzo",
            4=>"Abril",
            5=>"Mayo",
            6=>"Junio",
            7=>"Julio",
            8=>"Agosto",
            9=>"Septiembre",
            10=>"Octubre",
            11=>"Noviembre",
            12=>"Diciembre",);

        return $arreglo_meses[$this->id_mes];
    }
    function get_tiempo()
    {
        return $this->tiempo;
    }
    function get_precio()
    {
        return $this->precio;
    }
    function get_horario()
    {
        return $this->horario;
    }
    function get_id_mes()
    {
        return $this->id_mes;
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
    function SQL_insert_tarifa()
    {
        $query_insert = sprintf("INSERT INTO cuesta_electronico (id_fuente, id_horario, id_mes, tiempo, precio)
                                 VALUES (%s, %s, %s, %s, %s)",
            $this->GetSQLValueString($this->id_fuente, "int"),
            $this->GetSQLValueString($this->horario->get_id(), "int"),
            $this->GetSQLValueString($this->id_mes, "int"),
            $this->GetSQLValueString($this->tiempo, "date"),
            $this->GetSQLValueString($this->precio, "int"));
        return $query_insert;
    }

    // arma el query de actualizacion
    function SQL_update_tarifa()
    {
        $query_update =sprintf("UPDATE cuesta_electronico SET id_horario=%s, id_mes=%s, tiempo=%s, precio=%s
                                WHERE id_fuente=%s LIMIT 1;",
            $this->GetSQLValueString($this->horario->get_id(), "int"),
            $this->GetSQLValueString($this->id_mes, "int"),
            $this->GetSQLValueString($this->tiempo, "date"),
            $this->GetSQLValueString($this->precio, "int"),
            $this->GetSQLValueString($this->id_fuente, "int"));

        return $query_update;
    }

    //arma el query de eliminacion de tema
    function SQL_delete_tarifa()
    {
        $query_delete =sprintf("DELETE FROM cuesta_electronico
                                       WHERE (id_fuente=%s AND id_mes=%s AND id_horario=%s) LIMIT 1;",
            $this->GetSQLValueString($this->id_fuente, "int"),
            $this->GetSQLValueString($this->id_mes, "int"),
            $this->GetSQLValueString($this->horario->get_id(), "int"));
        return $query_delete;
    }

}
?>