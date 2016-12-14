<?php
/**
 * Subclase de la Clase Noticia, con informacion acerca a las noticias de television y radio
 *
 * @author Josue Morado
 */
class NoticiaElectronico extends Noticia
{
    private $hora;
    private $duracion;
    private $canal_estacion_txt;
    private $costo;

    function __construct($datos)
    {
        parent::__construct($datos);
        $this->hora = $datos['hora'];
        $this->duracion = $datos['duracion'];
        $this->canal_estacion_txt = $datos['canal_estacion_txt'];
        $this->costo = $datos['costo'];
    }

    function  __destruct() {
        ;
    }

    public function getHora() {
        return $this->hora;
    }

    public function getDuracion() {
        return $this->duracion;
    }

    public function getCanal_estacion_txt() {
        return $this->canal_estacion_txt;
    }

    public function getCosto() {
        return $this->costo;
    }



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
			case "float":
                $theValue = ($theValue != "") ? floatval($theValue) : "NULL";
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

    public function SQL_NUEVA_NOTICIA()
    {
        $query_nuevo = sprintf("SELECT NUEVA_NOTICIA_ELECTRONICO(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            $this->GetSQLValueString($this->encabezado,"text"),
            $this->GetSQLValueString($this->sintesis,"text"),
            $this->GetSQLValueString($this->autor,"text"),
            $this->GetSQLValueString($this->fecha, "date"),
            $this->GetSQLValueString($this->comentario, "text"),
            $this->GetSQLValueString($this->alcanse, "int"),
            $this->GetSQLValueString($this->id_tipo_fuente,"int"),
            $this->GetSQLValueString($this->id_fuente,"int"),
            $this->GetSQLValueString($this->id_seccion,"int"),
            $this->GetSQLValueString($this->id_sector,"int"),
            $this->GetSQLValueString($this->id_tipo_autor,"int"),
            $this->GetSQLValueString($this->id_genero, "int"),
            $this->GetSQLValueString($this->id_tendencia_monitorista, "int"),
            $this->GetSQLValueString($this->id_usuario, "int"),
            $this->GetSQLValueString($this->hora, "date"),
            $this->GetSQLValueString($this->duracion, "date"),
            $this->GetSQLValueString($this->costo, "float")
        );
        return $query_nuevo;
    }

        public function SQL_EDIT_NOTICIA()
    {
        $query_edit = sprintf("SELECT EDIT_NOTICIA_ELECTRONICO(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            $this->GetSQLValueString($this->id, "int"),
            $this->GetSQLValueString($this->encabezado,"text"),
            $this->GetSQLValueString($this->sintesis,"text"),
            $this->GetSQLValueString($this->autor,"text"),
            $this->GetSQLValueString($this->fecha, "date"),
            $this->GetSQLValueString($this->comentario, "text"),
            $this->GetSQLValueString($this->alcanse, "int"),
            $this->GetSQLValueString($this->id_tipo_fuente,"int"),
            $this->GetSQLValueString($this->id_fuente,"int"),
            $this->GetSQLValueString($this->id_seccion,"int"),
            $this->GetSQLValueString($this->id_sector,"int"),
            $this->GetSQLValueString($this->id_tipo_autor,"int"),
            $this->GetSQLValueString($this->id_genero, "int"),
            $this->GetSQLValueString($this->id_tendencia_monitorista, "int"),
            $this->GetSQLValueString($this->hora, "date"),
            $this->GetSQLValueString($this->duracion, "date"),
            $this->GetSQLValueString($this->costo, "float")
        );
        return $query_edit;
    }

}
?>
