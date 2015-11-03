<?php
/**
 * Contiene los valores de la ubicacion fisica de una nota dentro de la pagina que la contiene,
 * solo aplica para las fuentes de periodico y revista
 *
 * @author Josue
 */
class Ubicacion {

    private $id_noticia;
    private $uno;
    private $dos;
    private $tres;
    private $cuatro;
    private $cinco;
    private $seis;
    private $siete;
    private $ocho;
    private $nueve;
    private $diez;
    private $once;
    private $doce;

    function __construct($datos) {
        $this->id_noticia = $datos['id_noticia'];
        $this->uno = $datos['uno'];
        $this->dos = $datos['dos'];
        $this->tres = $datos['tres'];
        $this->cuatro = $datos['cuatro'];
        $this->cinco = $datos['cinco'];
        $this->seis = $datos['seis'];
        $this->siete = $datos['siete'];
        $this->ocho = $datos['ocho'];
        $this->nueve = $datos['nueve'];
        $this->diez = $datos['diez'];
        $this->once = $datos['once'];
        $this->doce = $datos['doce'];
    }
    
    function  __destruct()
    {
        ;
    }

    public function getId_noticia() {
        return $this->id_noticia;
    }

    public function getUno() {
        return $this->uno;
    }

    public function getDos() {
        return $this->dos;
    }

    public function getTres() {
        return $this->tres;
    }

    public function getCuatro() {
        return $this->cuatro;
    }

    public function getCinco() {
        return $this->cinco;
    }

    public function getSeis() {
        return $this->seis;
    }

    public function getSiete() {
        return $this->siete;
    }

    public function getOcho() {
        return $this->ocho;
    }

    public function getNueve() {
        return $this->nueve;
    }

    public function getDiez() {
        return $this->diez;
    }

    public function getOnce() {
        return $this->once;
    }

    public function getDoce() {
        return $this->doce;
    }

    public function setId_noticia($id_noticia) {
        $this->id_noticia = $id_noticia;
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

    function SQL_Insert_Ubicacion()
    {
        $query_insert = sprintf("INSERT INTO ubicacion (id_noticia, uno, dos, tres, cuatro, cinco, seis, siete, ocho,
                                                        nueve, diez, once, doce)
                                 VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            $this->GetSQLValueString($this->id_noticia, "int"),
            $this->GetSQLValueString($this->uno, "int"),
            $this->GetSQLValueString($this->dos, "int"),
            $this->GetSQLValueString($this->tres, "int"),
            $this->GetSQLValueString($this->cuatro, "int"),
            $this->GetSQLValueString($this->cinco, "int"),
            $this->GetSQLValueString($this->seis, "int"),
            $this->GetSQLValueString($this->siete, "int"),
            $this->GetSQLValueString($this->ocho, "int"),
            $this->GetSQLValueString($this->nueve, "int"),
            $this->GetSQLValueString($this->diez, "int"),
            $this->GetSQLValueString($this->once, "int"),
            $this->GetSQLValueString($this->doce, "int"));
        return $query_insert;
    }

        function SQL_Update_Ubicacion()
    {
        $query_update = sprintf("UPDATE ubicacion SET uno=%s, dos=%s, tres=%s, cuatro=%s, cinco=%s, seis=%s,
                                                      siete=%s, ocho=%s, nueve=%s, diez=%s, once=%s, doce=%s
                                 WHERE id_noticia=%s LIMIT 1;",
            $this->GetSQLValueString($this->uno, "int"),
            $this->GetSQLValueString($this->dos, "int"),
            $this->GetSQLValueString($this->tres, "int"),
            $this->GetSQLValueString($this->cuatro, "int"),
            $this->GetSQLValueString($this->cinco, "int"),
            $this->GetSQLValueString($this->seis, "int"),
            $this->GetSQLValueString($this->siete, "int"),
            $this->GetSQLValueString($this->ocho, "int"),
            $this->GetSQLValueString($this->nueve, "int"),
            $this->GetSQLValueString($this->diez, "int"),
            $this->GetSQLValueString($this->once, "int"),
            $this->GetSQLValueString($this->doce, "int"),
            $this->GetSQLValueString($this->id_noticia, "int"));
        return $query_update;
    }

}
?>
