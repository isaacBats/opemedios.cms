<?php
/**
 * los horarios son solo para indicar intervalos de tiempo en las tarifas de medios electronicos
 *
 * @author Josue Morado
 */
class Horario
{
    private $id;
    private $hora_inicio;
    private $hora_final;

    function  __construct($datos)
    {
        $this->id = $datos['id_horario'];
        $this->hora_inicio = $datos['hora_inicio'];
        $this->hora_final = $datos['hora_final'];
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
    function get_hora_inicio()
    {
        return $this->hora_inicio;
    }
    function get_hora_final()
    {
        return $this->hora_final;
    }
}
?>
