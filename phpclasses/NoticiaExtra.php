<?php
/**
 * En esta subclase se muestrabn los datos especificos de noticias de periodico, revista e internet
 *
 * @author Josue Morado
 */

class NoticiaExtra extends Noticia
{

    private $pagina;
    private $id_tipo_pagina;
    private $tipo_pagina;
    private $porcentaje_pagina;
    private $is_social;
    private $url;
    private $ubicacion;
    private $archivo_pagina;
	private $hora_publicacion;
	private $costo;

    function __construct($datos, $tipo) // 3 = periodico, 4=revista, 5=internet
    {
        switch ($tipo)
        {
            case 3:
                parent::__construct($datos);
                $this->pagina = $datos['pagina'];
                $this->id_tipo_pagina = $datos['id_tipo_pagina'];
                $this->tipo_pagina = $datos['tipo_pagina'];
                $this->porcentaje_pagina = $datos['porcentaje_pagina'];
                $this->url = "www"; // se coloca www para evitar NULL en la stored function
                $this->ubicacion = "";
                $this->archivo_pagina = "";
				$this->costo = $datos['costo'];
                break;
            case 4:
                parent::__construct($datos);
                $this->pagina = $datos['pagina'];
                $this->id_tipo_pagina = $datos['id_tipo_pagina'];
                $this->tipo_pagina = $datos['tipo_pagina'];
                $this->porcentaje_pagina = $datos['porcentaje_pagina'];
                $this->url = "www"; // se coloca www para evitar NULL en la stored function
                $this->ubicacion = "";
                $this->archivo_pagina = "";
				$this->costo = $datos['costo'];
                break;
            case 5:
                parent::__construct($datos);
                $this->pagina = "-1";
                $this->id_tipo_pagina = "-1";// se coloca  -1 para evitar null
                $this->tipo_pagina = "";
                $this->id_tamano_nota = "-1";// se coloca -1 para evitar null
                $this->tamano_nota = "";
                $this->url = $datos['url'];
                $this->is_social = $datos['is_social'];
				$this->hora_publicacion = $datos['hora_publicacion'];
                $this->ubicacion = "";
                $this->archivo_pagina = "";
				$this->costo = $datos['costo'];
                break;

            default:
                echo "Error: se especifico un tipo de dato no valido!!";
                break;
        }
    }

    function  __destruct() {
        ;
    }
	
	public function getCosto() {
        return $this->costo;
    }
    
    public function getPagina() {
        return $this->pagina;
    }
        
    public function getId_tipo_pagina() {
        return $this->id_tipo_pagina;
    }
        
    public function getTipo_pagina() {
        return $this->tipo_pagina;
    }
        
    public function getPorcentaje_pagina() {
        return $this->porcentaje_pagina;
    }
        
    public function getUrl() {
        return $this->url;
    }
	
	public function getHora_publicacion() {
        return $this->hora_publicacion;
    }

    public function setUbicacion(Ubicacion $ubicacion){
        $this->ubicacion = $ubicacion;
    }

    public function setArchivoPagina(Archivo $archivo_pagina)
    {
        $this->archivo_pagina = $archivo_pagina;
    }

    public function getArchivo_pagina()
    {
        return $this->archivo_pagina;
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
        $query_nuevo = sprintf("SELECT NUEVA_NOTICIA_EXTRA(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%f)",
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
            $this->GetSQLValueString($this->pagina, "int"),
            $this->GetSQLValueString($this->id_tipo_pagina, "int"),
            $this->GetSQLValueString($this->porcentaje_pagina, "double"),
            $this->GetSQLValueString($this->url, "text"),
            $this->GetSQLValueString($this->is_social, "int"),
			$this->GetSQLValueString($this->hora_publicacion, "date"),
			$this->GetSQLValueString($this->costo, "float")
        );
        return $query_nuevo;
    }

        public function SQL_EDIT_NOTICIA()
    {
        $query_edit = sprintf("SELECT EDIT_NOTICIA_EXTRA(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            $this->GetSQLValueString($this->id,"int"),
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
            $this->GetSQLValueString($this->pagina, "int"),
            $this->GetSQLValueString($this->id_tipo_pagina, "int"),
            $this->GetSQLValueString($this->porcentaje_pagina, "double"),
            $this->GetSQLValueString($this->url, "text"),
            $this->GetSQLValueString($this->is_social, "int"),
			$this->GetSQLValueString($this->hora_publicacion, "date"),
			$this->GetSQLValueString($this->costo, "float")
        );
        return $query_edit;
    }


}//end class
?>
