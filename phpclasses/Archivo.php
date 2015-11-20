<?php
/**
 * Los las noticias tienen relacionados archivos , uno principal y n secundarios, todos ellos de tipo Archivo,
 * que contienen la informacion de lso archivos y aqui se despliega
 *
 * @author Josue Morado
 */
class Archivo
{
    private $id;
    private $nombre;
    private $nombre_archivo;
    private $tipo;
    private $carpeta;
    private $principal;
    private $id_noticia;

    function __construct($datos) {
        $this->id = $datos['id_adjunto'];
        $this->nombre = $datos['nombre'];
        $this->nombre_archivo = $datos['nombre_archivo'];
        $this->tipo = $datos['tipo'];
        $this->carpeta = $datos['carpeta'];
        $this->principal = $datos['principal'];
        $this->id_noticia = $datos['id_noticia'];
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getNombre_archivo() {
        return $this->nombre_archivo;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getCarpeta() {
        return $this->carpeta;
    }

    public function getPrincipal() {
        return $this->principal;
    }

    public function getId_noticia() {
        return $this->id_noticia;
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

    // arma el query de insercion  a la base de datos

    function SQL_Insert_Archivo()
    {
        $query_insert = sprintf("INSERT INTO adjunto (nombre, nombre_archivo, tipo, carpeta, principal, id_noticia)
                                 VALUES (%s, %s, %s, %s, %s, %s)",
                         $this->GetSQLValueString($this->nombre, "text"),
                         $this->GetSQLValueString($this->nombre_archivo, "text"),
                         $this->GetSQLValueString($this->tipo, "text"),
                         $this->GetSQLValueString($this->carpeta, "text"),
                         $this->GetSQLValueString($this->principal, "int"),
                         $this->GetSQLValueString($this->id_noticia, "int"));
        return $query_insert;
    }
    // arma el query de actualizacion

  function SQL_Update_Archivo()
    {
        $query_update =sprintf("UPDATE adjunto SET nombre=%s, nombre_archivo=%s, tipo=%s, carpeta =%s, principal=%s, id_noticia=%s
                                               WHERE id_archivo=%s LIMIT 1",
                       $this->GetSQLValueString($this->nombre, "text"),
                       $this->GetSQLValueString($this->nombre_archivo, "text"),
                       $this->GetSQLValueString($this->tipo, "text"),
                       $this->GetSQLValueString($this->carpeta, "text"),
                       $this->GetSQLValueString($this->principal, "text"),
                       $this->GetSQLValueString($this->id_noticia, "int"),
                       $this->GetSQLValueString($this->id, "int"));

        return $query_update;
    }
//arma el query de eliminacion de archivo
    function SQL_Delete_Archivo()
    {
        $query_delete =sprintf("DELETE FROM adjunto WHERE id_adjunto =%s LIMIT 1",
                       $this->GetSQLValueString($this->id, "int"));
        return $query_delete;
    }


}//end class
?>
