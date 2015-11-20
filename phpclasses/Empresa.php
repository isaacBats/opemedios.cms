<?php
/**
 * Clase Empresa
 * nuestros clientes son empresas y cada empresa tiene cuentas, temas, permisos y sus datos
 *
 * @author Josue Morado Manríquez
 */
class Empresa
{
    private $id;
    private $nombre;
    private $direccion;
    private $telefono;
    private $contacto;
    private $email;
    private $giro;
    private $logo;
    private $color_fondo;
    private $color_letra;
    private $fecha_registro;
    private $temas;
    private $cuentas;
    private $permisos;


    function __construct($datos)
    {
        $this->id = $datos['id_empresa'];
        $this->nombre = $datos['nombre'];
        $this->direccion = $datos['direccion'];
        $this->telefono = $datos['telefono'];
        $this->contacto = $datos['contacto'];
        $this->email = $datos['email'];
        $this->giro = $datos['giro'];
        $this->logo = $datos['logo'];
        $this->color_fondo = $datos['color_fondo'];
        $this->color_letra = $datos['color_letra'];
        $this->fecha_registro = $datos['fecha_registro'];
        $this->temas = array();
        $this->cuentas = array();
        $this->permisos = 0;
    }

    function  __destruct()
    {
        ;
    }

//establece los valores del objeto

    function set_id($id)
    {
        $this->id = $id;
    }
    function set_nombre($nombre)
    {
        $this->nombre = $nombre;
    }
    function set_direccion($direccion)
    {
        $this->direccion = $direccion;
    }
    function set_telefono($telefono)
    {
        $this->telefono = $telefono;
    }
    function set_contacto($contacto)
    {
        $this->contacto = $contacto;
    }
    function set_email($email)
    {
        $this->email = $email;
    }
    function set_giro($giro)
    {
        $this->giro = $giro;
    }
    function set_logo($logo)
    {
        $this->logo = $logo;
    }
    function set_color_fondo($color_fondo)
    {
        $this->color_fondo = $color_fondo;
    }
    function set_color_letra($color_letra)
    {
        $this->color_letra = $color_letra;
    }
 

// Obtienen los atributos del objeto
    function get_id()
    {
        return $this->id;
    }
    function get_nombre()
    {
        return $this->nombre;
    }
    function get_direccion()
    {
        return $this->direccion;
    }
    function get_telefono()
    {
        return $this->telefono;
    }
    function get_contacto()
    {
        return $this->contacto;
    }
    function get_email()
    {
        return $this->email;
    }
    function get_giro()
    {
        return $this->giro;
    }
    function get_logo()
    {
        return $this->logo;
    }
    function get_color_fondo()
    {
        return $this->color_fondo;
    }
    function get_color_letra()
    {
        return $this->color_letra;
    }
    function get_fecha_registro()
    {
        return $this->fecha_registro;
    }
//    function get_fecha_registro_larga()
//    {
//        $dayarray = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
//        $montharray = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Deciembre");
//        $dia = date("j",)
//        return
//    }
    function get_temas()//arreglo de objetos de la clase tema
    {
        return $this->temas;
    }
    function get_cuentas()//arreglo de objetos de la clase cuenta
    {
        return $this->cuentas;
    }
    function get_permisos()//un objeto de la clase permiso
    {
        return $this->permisos;
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


    // arma el query que llama el stored function nuevo_cliente, el stored function regresa el Id del nuevo cliente
    function SQL_nuevo_empresa()
    {
        $query_nuevo = sprintf("SELECT NUEVO_CLIENTE(%s,%s,%s,%s,%s,%s,%s,%s)",
                         $this->GetSQLValueString($this->nombre, "text"),
                         $this->GetSQLValueString($this->direccion, "text"),
                         $this->GetSQLValueString($this->telefono, "text"),
                         $this->GetSQLValueString($this->contacto, "text"),
                         $this->GetSQLValueString($this->email, "text"),
                         $this->GetSQLValueString($this->giro, "text"),
                         $this->GetSQLValueString($this->color_fondo, "text"),
                         $this->GetSQLValueString($this->color_letra, "text"));
        return $query_nuevo;
    }

    //arma el query de actualizacion de datos

    function SQL_update_datos_empresa()
    {
        $query_update =sprintf("UPDATE empresa SET nombre=%s, direccion=%s, telefono=%s, contacto=%s,
                                                   email=%s, giro=%s, color_fondo=%s, color_letra=%s
                                WHERE id_empresa=%s LIMIT 1",
                       $this->GetSQLValueString($this->nombre, "text"),
                       $this->GetSQLValueString($this->direccion, "text"),
                       $this->GetSQLValueString($this->telefono, "text"),
                       $this->GetSQLValueString($this->contacto, "text"),
                       $this->GetSQLValueString($this->email, "text"),
                       $this->GetSQLValueString($this->giro, "text"),
                       $this->GetSQLValueString($this->color_fondo, "text"),
                       $this->GetSQLValueString($this->color_letra, "text"),
                       $this->GetSQLValueString($this->id, "int"));
        return $query_update;
    }

    //arma el query para actualizar el logo de la empresa
    function SQL_update_logo()
    {
        $query_logo =sprintf("UPDATE empresa SET logo=%s
                              WHERE id_empresa=%s LIMIT 1",
                       $this->GetSQLValueString($this->logo, "text"),
                       $this->GetSQLValueString($this->id, "int"));
        return $query_logo;
    }

//asigna un objeto tema a la empresa
    function add_tema(Tema $tema)
    {
        $this->temas[$tema->get_id()] = $tema;
    }
    //asigna un objeto cuenta a la empresa
    function add_cuenta(Cuenta $cuenta)
    {
        $this->cuentas[$cuenta->get_id()] = $cuenta;
    }
    //eestablece los permisos de una empresa
    function set_permisos(Permiso $permiso)
    {
        $this->permisos = $permiso;
    }
    function remove_tema(Tema $tema)
    {
        $this->temas[$tema->get_id()]= null;
    }
    function remove_cuenta(Cuenta $cuenta)
    {
        $this->cuentas[$cuenta->get_id()] = null;
    }
    

}
?>
