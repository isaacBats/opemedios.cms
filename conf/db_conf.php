<?php
/*
 * Datos de Configuracion de la Base de datos
 *
 * @author Josue Morado Manríquez
*/

class OpmDBConf
{
    private $databaseURL = "localhost";
    private $databaseUName = "opemedios";
    private $databasePWord = "opemedios";
    private $databaseName = "opemedios";

    function get_databaseURL()
    {
        return $this->databaseURL;
    }
    function get_databaseUName()
    {
        return $this->databaseUName;
    }
    function get_databasePWord()
    {
        return $this->databasePWord;
    }
    function get_databaseName()
    {
        return $this->databaseName;
    }


    function  __destruct()
    {

    }
}
?>