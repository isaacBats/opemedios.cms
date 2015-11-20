<?php
/**
 * Clase Permiso: aqui se manejan los datos de acceso de una cmpresa  a cada seccion de portal
 *
 * @author Josue Morado Manriquez
 */
class Permiso
{

    private $id_empresa;
    private $primeras_planas;
    private $col_pol;
    private $col_fin;
    private $cartones;
    private $portadas_fin;


    private function id_to_string($num)
    {
        switch ($num)
        {
            case 1:
                return "Sin Permiso";
                break;
            case 2:
                return "Sólo el día actual";
                break;
            case 3:
                return "Todos los días";
                break;
            default:
                break;
        }
    }

    function  __construct($datos)
    {
        $this->id_empresa = $datos['id_empresa'];
        $this->primeras_planas = $datos['primeras_planas'];
        $this->col_pol = $datos['col_pol'];
        $this->col_fin = $datos['col_fin'];
        $this->cartones = $datos['cartones'];
        $this->portadas_fin = $datos['portadas_fin'];
    }

    function  __destruct()
    {
        ;
    }

    //establece los datos del objeto
    function set_primeras_planas($primeras_planas)
    {
        $this->primeras_planas = $primeras_planas;
    }
    function set_col_pol($col_pol)
    {
        $this->col_pol = $col_pol;
    }
    function set_col_fin($col_fin)
    {
        $this->col_fin = $col_fin;
    }
    function set_cartones($cartones)
    {
        $this->cartones = $cartones;
    }
    function set_portadas_fin($portadas_fin)
    {
        $this->portadas_fin = $portadas_fin;
    }

    //obtienen los valores del objeto
    function get_id_empresa()
    {
        return $this->id_empresa;
    }
    function get_primeras_planas()
    {
        return $this->id_to_string($this->primeras_planas);
    }
    function get_col_pol()
    {
        return $this->id_to_string($this->col_pol);
    }
    function get_col_fin()
    {
        return $this->id_to_string($this->col_fin);
    }
    function get_cartones()
    {
        return $this->id_to_string($this->cartones);
    }
    function get_portadas_fin()
    {
        return $this->id_to_string($this->portadas_fin);
    }
    // obtienen el valor numerico de los permisos
    function get_primeras_planas_id()
    {
        return $this->primeras_planas;
    }
    function get_col_pol_id()
    {
        return $this->col_pol;
    }
    function get_col_fin_id()
    {
        return $this->col_fin;
    }
    function get_cartones_id()
    {
        return $this->cartones;
    }
    function get_portadas_fin_id()
    {
        return $this->portadas_fin;
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

    //arma el query de actualizacion a de base de datos
    function SQL_update_permiso()
    {
        $query_update =sprintf("UPDATE permiso SET primeras_planas=%s, col_pol=%s, col_fin=%s, cartones=%s, portadas_fin=%s
                                WHERE id_empresa=%s LIMIT 1",
            $this->GetSQLValueString($this->primeras_planas, "int"),
            $this->GetSQLValueString($this->col_pol, "int"),
            $this->GetSQLValueString($this->col_fin, "int"),
            $this->GetSQLValueString($this->cartones, "int"),
            $this->GetSQLValueString($this->portadas_fin, "int"),
            $this->GetSQLValueString($this->id_empresa, "int"));
        return $query_update;
    }

}
?>
