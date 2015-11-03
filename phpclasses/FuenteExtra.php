<?php
/**
 * Esta clase extiende la clase generica Fuente y se usa para las fuentes de periodico
 * revista e internet, ya que solo tienen un atributo extra, y llamaran a un mismo
 * stored function
 *
 * @author Josue
 */
class FuenteExtra extends Fuente
{
    private $tiraje;
    private $url;
    private $tarifas;

    function __construct($datos, $tipo) // 3 = periodico, 4=revista, 5=internet
    {
        switch ($tipo)
        {
            case 3:
                parent::__construct($datos);
                $this->tiraje = $datos['tiraje'];
                $this->url = "www";// se coloca www solo para evitar un NULL en la funcion y que no marque error
                $this->tarifas = array();
                break;
            case 4:
                parent::__construct($datos);
                $this->tiraje = $datos['tiraje'];
                $this->url = "www"; // se coloca www solo para evitar un NULL en la funcion y que no marque error
                $this->tarifas = array();
                break;
            case 5:
                parent::__construct($datos);
                $this->tiraje = "";
                $this->url = $datos['url'];
                $this->tarifas = "";
                break;

            default:
                echo "Error: se especifico un tipo de dato no valido!!";
                break;
        }
    }

    function  __destruct() {
        ;
    }

    //obtienen los valores de los atributos
    function get_tiraje()
    {
        return $this->tiraje;
    }
    function get_url()
    {
        return $this->url;
    }
    function get_tarifas()
    {
        return $this->tarifas;
    }

    //establecen los valores de lso atributos
    function set_tiraje($tiraje)
    {
        $this->tiraje = $tiraje;
    }
    function set_url($url)
    {
        $this->url = $url;
    }

    //agrega tarifa a la fuente
    function add_tarifa(TarifaPrensa $tarifa)
    {
        $this->tarifas[$tarifa->get_id_fuente()."_".$tarifa->get_seccion()->get_id()."_".$tarifa->get_id_tipo_pagina()] =$tarifa;
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
    function SQL_NEW_FUENTE_EXTRA()
    {
        $query_nuevo = sprintf("SELECT NEW_FUENTE_EXTRA(%s,%s,%s,%s,%s,%s,%s,%s)",
            $this->GetSQLValueString($this->nombre,"text"),
            $this->GetSQLValueString($this->empresa,"text"),
            $this->GetSQLValueString($this->comentario,"text"),
            $this->GetSQLValueString($this->activo, "int"),
            $this->GetSQLValueString($this->id_tipo_fuente,"int"),
            $this->GetSQLValueString($this->id_cobertura,"int"),
            $this->GetSQLValueString($this->tiraje,"text"),
            $this->GetSQLValueString($this->url,"text"));
        return $query_nuevo;
    }

    //llama el stored function para actualizar una fuente de radio
    function SQL_EDIT_FUENTE_EXTRA()
    {
        $query_update = sprintf("SELECT EDIT_FUENTE_EXTRA(%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            $this->GetSQLValueString($this->id,"int"),
            $this->GetSQLValueString($this->nombre,"text"),
            $this->GetSQLValueString($this->empresa,"text"),
            $this->GetSQLValueString($this->comentario,"text"),
            $this->GetSQLValueString($this->activo, "int"),
            $this->GetSQLValueString($this->id_tipo_fuente,"int"),
            $this->GetSQLValueString($this->id_cobertura,"int"),
            $this->GetSQLValueString($this->tiraje,"text"),
            $this->GetSQLValueString($this->url,"text"));
        return $query_update;
    }
}
?>
