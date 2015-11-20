<?php
/**
 * Clase Usuario: contienen al informacion de los usuarios que utilizaran el sistema,
 * para el modulo primera plana
 *
 * @author Roberto
 */
class ColumnaFinanciera
{
    private $id;
    private $fecha;
    private $titulo;
    private $autor;
    private $contenido;
    private $imagen_jpg;
    private $archivo_pdf;
    private $id_fuente;

    function  __construct($datos)
    {
        $this->id = $datos['id_columna_financiera'];
        $this->fecha = $datos['fecha'];
        $this->titulo = $datos['titulo'];
        $this->autor = $datos['autor'];
        $this->contenido = $datos['contenido'];
        $this->imagen_jpg = $datos['imagen_jpg'];
        $this->archivo_pdf = $datos['archivo_pdf'];
        $this->id_fuente = $datos['id_fuente'];
    }

    function __destruct()
    {
        ;
    }
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function getAutor() {
        return $this->autor;
    }

    public function setAutor($autor) {
        $this->autor = $autor;
    }

    public function getContenido() {
        return $this->contenido;
    }

    public function setContenido($contenido) {
        $this->contenido = $contenido;
    }

    public function getImagen_jpg() {
        return $this->imagen_jpg;
    }

    public function setImagen_jpg($imagen_jpg) {
        $this->imagen_jpg = $imagen_jpg;
    }

    public function getArchivo_pdf() {
        return $this->archivo_pdf;
    }

    public function setArchivo_pdf($archivo_pdf) {
        $this->archivo_pdf = $archivo_pdf;
    }

    public function getId_fuente() {
        return $this->id_fuente;
    }

    public function setId_fuente($id_fuente) {
        $this->id_fuente = $id_fuente;
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

    function SQL_insert()
    {
        $query_insert = sprintf("INSERT INTO columna_financiera (fecha, titulo, autor, contenido, imagen_jpg, archivo_pdf, id_fuente)
                                 VALUES (%s, %s, %s, %s, %s, %s, %s)",
            $this->GetSQLValueString($this->fecha, "date"),
            $this->GetSQLValueString($this->titulo, "text"),
            $this->GetSQLValueString($this->autor, "text"),
            $this->GetSQLValueString($this->contenido, "text"),
            $this->GetSQLValueString($this->imagen_jpg, "text"),
            $this->GetSQLValueString($this->archivo_pdf, "text"),
            $this->GetSQLValueString($this->id_fuente, "int"));
        return $query_insert;
    }
    // arma el query de actualizacion

    function SQL_update()
    {
        $query_update =sprintf("UPDATE columna_financiera SET
                                                          fecha=%s,
                                                          titulo=%s,
                                                          autor=%s,
                                                          contenido=%s,
                                                          imagen_jpg=%s,
                                                          archivo_pdf=%s,
                                                          id_fuente=%s
                             WHERE id_columna_financiera=%s LIMIT 1",
            $this->GetSQLValueString($this->fecha, "date"),
            $this->GetSQLValueString($this->titulo, "text"),
            $this->GetSQLValueString($this->autor, "text"),
            $this->GetSQLValueString($this->contenido, "text"),
            $this->GetSQLValueString($this->imagen_jpg, "text"),
            $this->GetSQLValueString($this->archivo_pdf, "text"),
            $this->GetSQLValueString($this->id_fuente, "int"),
            $this->GetSQLValueString($this->id, "int"));

        return $query_update;
    }

    //arma el query de eliminacion de tema
    function SQL_delete()
    {
        $query_delete =sprintf("DELETE FROM columna_financiera WHERE id_columna_financiera =%s LIMIT 1",
            $this->GetSQLValueString($this->id, "int"));
        return $query_delete;
    }
}
?>
