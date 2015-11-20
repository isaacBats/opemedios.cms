<?php
/**
 * Clase generica que contiene los atributos y metodos  de todas las fuentes
 *
 * @author Josue
 */
class Fuente
{
    protected $id;
    protected $nombre;
    protected $empresa;
    protected $comentario;
    protected $logo;
    protected $activo;
    protected $id_tipo_fuente;
    protected $id_cobertura;
    protected $secciones;

    function __construct($datos)
    {
        $this->id = $datos['id_fuente'];
        $this->nombre = $datos['nombre'];
        $this->empresa =$datos['empresa'];
        $this->comentario = $datos['comentario'];
        $this->logo = $datos['logo'];
        $this->activo = $datos['activo'];
        $this->id_cobertura = $datos['id_cobertura'];
        $this->id_tipo_fuente = $datos['id_tipo_fuente'];
        $this->secciones = array();
    }

    function  __destruct()
    {
        ;
    }

    //establecen los atributos del objeto

    function set_id($id)
    {
        $this->id = $id;
    }
    function set_nombre($nombre)
    {
        $this->nombre = $nombre;
    }
    function set_empresa($empresa)
    {
        $this->empresa = $empresa;
    }
    function set_comentario($comentario)
    {
        $this->comentario = $comentario;
    }
    function set_logo($logo)
    {
        $this->logo = $logo;
    }
    function set_id_cobertura($id_cobertura)
    {
        $this->id_cobertura = $id_cobertura;
    }
    function set_activo($activo)
    {
        $this->activo = $activo;
    }
    function set_id_tipo_fuente($id_tipo_fuente)
    {
        $this->id_tipo_fuente = $id_tipo_fuente;
    }

    //obtienen los valores de lso atriibutos
    function get_id()
    {
        return $this->id;
    }
    function get_nombre()
    {
        return $this->nombre;
    }
    function get_empresa()
    {
        return $this->empresa;
    }
    function get_comentario()
    {
        return $this->comentario;
    }
    function get_logo()
    {
        return $this->logo;
    }
    function get_id_cobertura()
    {
        return $this->id_cobertura;
    }
    function get_activo()
    {
        return $this->activo;
    }
    function get_id_tipo_fuente()
    {
        return $this->id_tipo_fuente;
    }
    function get_secciones()
    {
        return $this->secciones;
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

    function get_cobertura_txt()
    {
        switch($this->id_cobertura)
        {
            case 1:
                return "Local";
                break;
            case 2:
                return "Nacional";
                break;
            case 3:
                return "Internacional";
                break;
            default:
                return "Error";
                break;
        }
    }

    //asigna un objeto seccion a la fuente
    function add_seccion(Seccion $seccion)
    {
        $this->secciones[$seccion->get_id()] = $seccion;
    }

    function SQL_update_logo()
    {
        $query_logo =sprintf("UPDATE fuente SET logo=%s
                              WHERE id_fuente=%s LIMIT 1",
            $this->GetSQLValueString($this->logo, "text"),
            $this->GetSQLValueString($this->id, "int"));
        return $query_logo;
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

}
?>