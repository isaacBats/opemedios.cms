<?php
/**
 * Objetos que devuelven nombres de meses
 *
 * @author Josue Morado
 */
class Mes
{
    private $id;
    private $descripcion;

    function  __construct($datos)
    {
        $this->id = $datos['id_mes'];
        $this->descripcion = $datos['descripcion'];
    }

    function  __destruct()
    {
        ;
    }

    //obtienen los valores
    function get_id()
    {
        return $this->id;
    }
    function get_descripcion()
    {
        return $this->descripcion;
    }
}
?>
