<?php
/**
 * Alberga la informacion de las fuentes de radio, extiende la clase generica Fuente
 *
 * @author Josue
 */
class FuenteRadio extends Fuente
{
    private $conductor;
    private $estacion;
    private $horario;
    private $tarifas;
    
    function __construct($datos)
    {
        parent::__construct($datos);
        $this->conductor = $datos['conductor'];
        $this->estacion = $datos['estacion'];
        $this->horario = $datos['horario'];
        $this->tarifas = array();
    }

    function  __destruct() {
        ;
    }
    
    // obtienen los atributos de la clase
    function get_conductor()
    {
        return $this->conductor;
    }
    function get_estacion()
    {
        return $this->estacion;
    }
    function get_horario()
    {
        return $this->horario;
    }
    function get_tarifas()
    {
        return $this->tarifas;
    }


    //establecen los atributos de la clase
    function set_conductor($conductor)
    {
        $this->conductor = $conductor;
    }
    function set_estacion($estacion)
    {
        $this->estacion = $estacion;
    }
    function set_horario($horario)
    {
        $this->horario = $horario;
    }

    function add_tarifa(TarifaElectronico $tarifa)
    {
        $this->tarifas[$tarifa->get_id_fuente()."_".$tarifa->get_horario()->get_id()."_".$tarifa->get_id_mes()] =$tarifa;
    }
    
    //Operaciones con base de datos
        
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
    
    //llama el stored function para meter una nueeva fuente de radio
    function SQL_NEW_FUENTE_RADIO()
    {
        $query_nuevo = sprintf("SELECT NEW_FUENTE_RADIO(%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            $this->GetSQLValueString($this->nombre,"text"),
            $this->GetSQLValueString($this->empresa,"text"),
            $this->GetSQLValueString($this->comentario,"text"),
            $this->GetSQLValueString($this->activo, "int"),
            $this->GetSQLValueString($this->id_tipo_fuente,"int"),
            $this->GetSQLValueString($this->id_cobertura,"int"),
            $this->GetSQLValueString($this->conductor,"text"),
            $this->GetSQLValueString($this->estacion,"text"),
            $this->GetSQLValueString($this->horario,"text"));
        return $query_nuevo;
    }
    
    //llama el stored function para actualizar una fuente de radio
    function SQL_UPDATE_FUENTE_RADIO()
    {
        $query_update = sprintf("SELECT EDIT_FUENTE_RADIO(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            $this->GetSQLValueString($this->id,"int"),
            $this->GetSQLValueString($this->nombre,"text"),
            $this->GetSQLValueString($this->empresa,"text"),
            $this->GetSQLValueString($this->comentario,"text"),
            $this->GetSQLValueString($this->activo, "int"),
            $this->GetSQLValueString($this->id_tipo_fuente,"int"),
            $this->GetSQLValueString($this->id_cobertura,"int"),
            $this->GetSQLValueString($this->conductor,"text"),
            $this->GetSQLValueString($this->estacion,"text"),
            $this->GetSQLValueString($this->horario,"text"));
        return $query_update;        
    }
    
}
?>
