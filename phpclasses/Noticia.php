<?php
/**
 * Las noticias son el elemento principal de este sistema. esta clase generica permite manejar los datos
 * genericos de una noticia, por lo tanto, de esta clase de definen subclases por cada tipo de noticia
 *
 * @author Josue Morado
 */
class Noticia
{
    protected $id;
    protected $encabezado;
    protected $sintesis;
    protected $autor;
    protected $fecha;
    protected $comentario;
    protected $id_tipo_fuente;
    protected $tipo_fuente;
    protected $id_fuente;
    protected $fuente;
    protected $id_seccion;
    protected $seccion;
    protected $id_sector;
    protected $sector;
    protected $id_tipo_autor;
    protected $tipo_autor;
    protected $id_genero;
    protected $genero;
    protected $id_tendencia_monitorista;
    protected $tendencia_monitorista;
    protected $id_usuario;
    protected $usuario;
    protected $archivo_principal;
    protected $archivos_alternos;
    // los siguientes atributos solo se usan cuando se ven las noticias que tiene un cliente especifico
    protected $id_tema;
    protected $tema;
    protected $id_tendencia;
    protected $tendencia;

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
        $this->id_tendencia_monitorista = $datos['id_tendencia_monitorista'];
        $this->tendencia_monitorista = $datos['tendencia_monitorista'];
        $this->id_usuario = $datos['id_usuario'];
        $this->usuario = "";
        $this->archivo_principal = "";
        $this->archivos_alternos = array();
        // los siguientes atributos solo se usan cuando se ven las noticias que tiene un cliente especifico
        $this->id_tema = $datos['id_tema'];
        $this->tema = $datos['tema'];
        $this->id_tendencia = $datos['id_tendencia'];
        $this->tendencia = $datos['tendencia'];
    }

    function  __destruct()
    {
        ;
    }
    
    
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
        
    public function getId_tendencia_monitorista() {
        return $this->id_tendencia_monitorista;
    }
        
    public function getTendencia_monitorista() {
        return $this->tendencia_monitorista;
    }
        
    public function getId_usuario() {
        return $this->id_usuario;
    }
        
    public function getUsuario() {
        return $this->usuario;
    }
        
    public function getArchivo_principal() {
        return $this->archivo_principal;
    }
        
    public function getArchivos_alternos() {
        return $this->archivos_alternos;
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


    
    //setters
    public function setId($id) {
        $this->id = $id;
    }
    public function setUsuario(Usuario $usuario) {
        $this->usuario = $usuario;
    }

    //asigna archivo principal a la noticia

    public function setArchivo_principal(Archivo $archivo)
    {
        $this->archivo_principal = $archivo;
    }
    // agregamos archivo alterno a la noticia
    public function addArchivo_alterno(Archivo $archivo) {
        $this->archivos_alternos[$archivo->getId()] = $archivo;
    }
}
?>
