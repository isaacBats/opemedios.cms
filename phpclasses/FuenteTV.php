<?php
/**
 * Alberga la informacion de las fuentes de television, extiende la clase generica Fuente
 *
 * @author Josue
 */
class FuenteTV extends Fuente
{
    private $conductor;
    private $canal;
    private $horario;
    private $id_senal;
    private $tarifas;

    function __construct($datos)
    {
        parent::__construct($datos);
        $this->conductor = $datos['conductor'];
        $this->canal = $datos['canal'];
        $this->horario = $datos['horario'];
        $this->id_senal = $datos['id_senal'];
        $this->tarifas = array();
    }

    function  __destruct() {
        ;
    }

    //obtienen los valores de la fuente de tele
    function get_conductor()
    {
        return $this->conductor;
    }
    function get_canal()
    {
        return $this->canal;
    }
    function get_horario()
    {
        return $this->horario;
    }
    function get_id_senal()
    {
        return $this->id_senal;
    }
    function get_senal_txt()
    {
        switch($this->id_senal)
        {
            case 1:
                return "Television Abierta";
                break;
            case 2:
                return "Cablevisión";
                break;
            case 3:
                return "Sky";
                break;
            default:
                return "Error";
                break;
        }
    }

    function get_tarifas()
    {
        return $this->tarifas;
    }

    //establecen los valores de los atributos
    function set_conductor($conductor)
    {
        $this->conductor = $conductor;
    }
    function set_canal($canal)
    {
        $this->canal = $canal;
    }
    function set_horario($horario)
    {
        $this->horario = $horario;
    }
    function set_id_senal($id_senal)
    {
        $this->id_senal = $id_senal;
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

    function SQL_NEW_FUENTE_TV()//manda a llamar la stored function
    {
        $query_nuevo = sprintf("SELECT NEW_FUENTE_TV(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            $this->GetSQLValueString($this->nombre,"text"),
            $this->GetSQLValueString($this->empresa,"text"),
            $this->GetSQLValueString($this->comentario,"text"),
            $this->GetSQLValueString($this->activo, "int"),
            $this->GetSQLValueString($this->id_tipo_fuente,"int"),
            $this->GetSQLValueString($this->id_cobertura,"int"),
            $this->GetSQLValueString($this->conductor,"text"),
            $this->GetSQLValueString($this->canal,"text"),
            $this->GetSQLValueString($this->horario,"text"),
            $this->GetSQLValueString($this->id_senal, "int"));
        return $query_nuevo;
    }

    function SQL_UPDATE_FUENTE_TV() // manda a llamara stored function
    {
        $query_update = sprintf("SELECT EDIT_FUENTE_TV(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            $this->GetSQLValueString($this->id,"int"),
            $this->GetSQLValueString($this->nombre,"text"),
            $this->GetSQLValueString($this->empresa,"text"),
            $this->GetSQLValueString($this->comentario,"text"),
            $this->GetSQLValueString($this->activo, "int"),
            $this->GetSQLValueString($this->id_tipo_fuente,"int"),
            $this->GetSQLValueString($this->id_cobertura,"int"),
            $this->GetSQLValueString($this->conductor,"text"),
            $this->GetSQLValueString($this->canal,"text"),
            $this->GetSQLValueString($this->horario,"text"),
            $this->GetSQLValueString($this->id_senal, "int"));
        return $query_update;
    }

}
?>
