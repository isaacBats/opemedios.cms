<?php
/**
 * Maneja las relaciones de una fuente con su seccion. tamaño etc
 *
 * @author Josue Morado
 */
class TarifaPrensa
{
    private $id_fuente;
    private $fuente;
    private $seccion;
    private $id_tipo_pagina;
    private $tipo_pagina;
    private $precio; // precio por pagina
    private $precio_noticia; // aqui sale el valor de la nota

    function  __construct($datos)
    {
        $this->id_fuente = $datos['id_fuente'];
        $this->fuente = $datos['fuente'];
        $this->seccion = "";
        $this->id_tipo_pagina = $datos['id_tipo_pagina'];
        $this->tipo_pagina = $datos['tipo_pagina'];
        $this->precio = $datos['precio'];
        $this->precio_noticia = $datos['precio_noticia'];
    }

    function  __destruct()
    {
        ;
    }


    //establece la seccion de una tarifaprensa
    function set_seccion(Seccion $seccion)
    {
        $this->seccion=$seccion;
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
    //obtiene los valores
    function get_id_fuente()
    {
        return $this->id_fuente;
    }
    function get_seccion()
    {
        return $this->seccion;
    }
    function get_id_tipo_pagina()
    {
        return $this->id_tipo_pagina;
    }
    function get_precio()
    {
        return $this->precio;
    }

    function get_tipo_pagina()
    {
        $arreglo_tipo_pagina = array(
            1=>"Portada",
            2=>"Contraportada",
            3=>"Par",
            4=>"Impar");

        return $arreglo_tipo_pagina[$this->id_tipo_pagina];
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

    //arma el query de insercion de tarifa a base de datos
    function SQL_insert_tarifa()
    {
        $query_insert = sprintf("INSERT INTO cuesta_prensa (id_fuente, id_seccion, id_tipo_pagina, precio)
                                 VALUES (%s, %s, %s, %s)",
            $this->GetSQLValueString($this->id_fuente, "int"),
            $this->GetSQLValueString($this->seccion->get_id(), "int"),
            $this->GetSQLValueString($this->id_tipo_pagina, "int"),
            $this->GetSQLValueString($this->precio, "int"));
        return $query_insert;
    }

    // arma el query de actualizacion
    function SQL_update_tarifa()
    {
        $query_update =sprintf("UPDATE cuesta_prensa SET id_seccion=%s, id_tipo_pagina=%s, precio=%s
                                WHERE id_fuente=%s LIMIT 1;",
            $this->GetSQLValueString($this->seccion->get_id(), "int"),
            $this->GetSQLValueString($this->id_tipo_pagina, "int"),
            $this->GetSQLValueString($this->precio, "int"),
            $this->GetSQLValueString($this->id_fuente, "int"));

        return $query_update;
    }

    //arma el query de eliminacion de tema
    function SQL_delete_tarifa()
    {
        $query_delete =sprintf("DELETE FROM cuesta_prensa
                                       WHERE (id_fuente=%s AND id_seccion=%s AND id_tipo_pagina=%s) LIMIT 1;",
            $this->GetSQLValueString($this->id_fuente, "int"),
            $this->GetSQLValueString($this->seccion->get_id(), "int"),
            $this->GetSQLValueString($this->id_tipo_pagina, "int"));
        return $query_delete;
    }

}
?>
