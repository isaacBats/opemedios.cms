<?php
/**
 * Clase que contiene toda la informacion requerida de una noticia, solo es para lectura del portal de clientes
 *
 * @author Josue Morado
 */
class SuperNoticia
{
    private $id;
    private $encabezado;
    private $sintesis;
    private $autor;
    private $fecha;
    private $comentario;
    private $id_tipo_fuente;
    private $tipo_fuente;
    private $id_fuente;
    private $fuente;
    private $id_seccion;
    private $seccion;
    private $id_sector;
    private $sector;
    private $id_tipo_autor;
    private $tipo_autor;
    private $id_genero;
    private $genero;
    // los siguientes atributos solo se usan cuando se ven las noticias que tiene un cliente especifico
    private $id_tema;
    private $tema;
    private $id_tendencia;
    private $tendencia;
    // exclusivos medios electronicos
    private $hora;
    private $canal;
    private $estacion;
    private $duracion;
	private $costo;
    //exclusivos medios impresos
    private $pagina;
    private $id_tipo_pagina;
    private $tipo_pagina;
    private $porcentaje_pagina;
    private $tiraje;
    // exclusivos internet
    private $url;
	private $hora_publicacion;


    function __construct($datos)
    {
        $this->id = $datos['id_noticia'];
        $this->encabezado = $datos['encabezado'];
        $this->sintesis = $datos['sintesis'];
        $this->autor = $datos['autor'];
        $this->fecha = $datos['fecha'];
        $this->comentario = $datos['comentario'];
        $this->id_tipo_fuente = $datos['id_tipo_fuente'];
        $this->tipo_fuente = $datos['tipo_fuente'];
        $this->id_fuente = $datos['id_fuente'];
        $this->fuente = $datos['fuente'];
        $this->id_seccion = $datos['id_seccion'];
        $this->seccion = $datos['seccion'];
        $this->id_sector = $datos['id_sector'];
        $this->sector = $datos['sector'];
        $this->id_tipo_autor = $datos['id_tipo_autor'];
        $this->tipo_autor = $datos['tipo_autor'];
        $this->id_genero = $datos['id_genero'];
        $this->genero = $datos['genero'];
        // los siguientes atributos solo se usan cuando se ven las noticias que tiene un cliente especifico
        $this->id_tema = $datos['id_tema'];
        $this->tema = $datos['tema'];
        $this->id_tendencia = $datos['id_tendencia'];
        $this->tendencia = $datos['tendencia'];
        //medios electronicos
        $this->hora = $datos['hora'];
        $this->canal = $datos['canal'];
        $this->estacion = $datos['estacion'];
        $this->duracion = $datos['duracion'];
		$this->costo = $datos['costo'];
        //medios impresos
        $this->pagina = $datos['pagina'];
        $this->id_tipo_pagina = $datos['id_tipo_pagina'];
        $this->tipo_pagina = $datos['tipo_pagina'];
        $this->porcentaje_pagina = $datos['porcentaje_pagina'];
        $this->tiraje = $datos['tiraje'];
        //internet
        $this->url = $datos['url'];
		$this->hora_publicacion = $datos['hora_publicacion'];
    }

    function  __destruct()
    {
        ;
    }
    
    
   //getters

   public function getId() {
       return $this->id;
   }

   public function getEncabezado() {
       return $this->encabezado;
   }

   public function getSintesis() {
       return $this->sintesis;
   }

   public function getAutor() {
       return $this->autor;
   }

   public function getFecha() {
       return $this->fecha;
   }

   public function getComentario() {
       return $this->comentario;
   }

   public function getId_tipo_fuente() {
       return $this->id_tipo_fuente;
   }

   public function getTipo_fuente() {
       return $this->tipo_fuente;
   }

   public function getId_fuente() {
       return $this->id_fuente;
   }

   public function getFuente() {
       return $this->fuente;
   }

   public function getId_seccion() {
       return $this->id_seccion;
   }

   public function getSeccion() {
       return $this->seccion;
   }

   public function getId_sector() {
       return $this->id_sector;
   }

   public function getSector() {
       return $this->sector;
   }

   public function getId_tipo_autor() {
       return $this->id_tipo_autor;
   }

   public function getTipo_autor() {
       return $this->tipo_autor;
   }

   public function getId_genero() {
       return $this->id_genero;
   }

   public function getGenero() {
       return $this->genero;
   }

   public function getId_tema() {
       return $this->id_tema;
   }

   public function getTema() {
       return $this->tema;
   }

   public function getId_tendencia() {
       return $this->id_tendencia;
   }

   public function getTendencia() {
       return $this->tendencia;
   }

   public function getHora() {
       return $this->hora;
   }

   public function getCanal() {
       return $this->canal;
   }

   public function getEstacion() {
       return $this->estacion;
   }

   public function getDuracion(){
       return $this->duracion;
   }
   
   public function getCosto(){
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


   public function getTiraje() {
       return $this->tiraje;
   }

   public function getUrl() {
       return $this->url;
   }
   
    public function getHora_publicacion() {
       return $this->hora_publicacion;
   }


    public function getFecha_larga()
    {
        $arreglo_meses = array(
            1=>"Enero",
            2=>"Febrero",
            3=>"Marzo",
            4=>"Abril",
            5=>"Mayo",
            6=>"Junio",
            7=>"Julio",
            8=>"Agosto",
            9=>"Septiembre",
            10=>"Octubre",
            11=>"Noviembre",
            12=>"Diciembre",);

        $dia = substr($this->getFecha(),8,2);
        $mes = date("n",mktime(00,00,00,substr($this->getFecha(),5,2),01,2000));
        $año = substr($this->getFecha(),0,4);

        return $dia." de ".$arreglo_meses[$mes].", ".$año;
    }
    
}
?>
