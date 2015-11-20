<?php
/**
 * Clase Usuario: contienen al informacion de los usuarios que utilizaran el sistema,
 * datos personales, asi como nivel de acceso a las paginas
 *
 * @author Josue
 */
class Usuario
{
    private $id;
    private $nombre;
    private $apellidos;
    private $direccion;
    private $telefono1;
    private $telefono2;
    private $email;
    private $cargo;
    private $comentario;
    private $username;
    private $password;
    private $activo;
    private $tipo_usuario; // 1: administrador 2:encargado de area,3:monitorista

    function  __construct($datos)
    {
        $this->id = $datos['id_usuario'];
        $this->nombre = $datos['nombre'];
        $this->apellidos = $datos['apellidos'];
        $this->direccion = $datos['direccion'];
        $this->telefono1 = $datos['telefono1'];
        $this->telefono2 = $datos['telefono2'];
        $this->email = $datos['email'];
        $this->cargo = $datos['cargo'];
        $this->comentario = $datos['comentario'];
        $this->username = $datos['username'];
        $this->password = $datos['password'];
        $this->activo = $datos['activo'];
        $this->tipo_usuario = $datos['id_tipo_usuario'];
    }

    function __destruct()
    {
        ;
    }

    // obtienen los datos del objeto

    function get_id()
    {
        return $this->id;
    }
    function get_nombre()
    {
        return $this->nombre;
    }
    function get_apellidos()
    {
        return $this->apellidos;
    }
    function get_direccion()
    {
        return $this->direccion;
    }
    function get_telefono1()
    {
        return $this->telefono1;
    }
    function get_telefono2()
    {
        return $this->telefono2;
    }
    function get_email()
    {
        return $this->email;
    }
    function get_cargo()
    {
        return $this->cargo;
    }
    function get_comentario()
    {
        return $this->comentario;
    }
    function get_username()
    {
        return $this->username;
    }
    function get_password()
    {
        return $this->password;
    }
    function get_activo()
    {
        return $this->activo;
    }
    function get_tipo_usuario()
    {
        return $this->tipo_usuario;
    }
    function get_nombre_completo()
    {
        $nombre = $this->nombre." ".$this->apellidos;
        return $nombre;
    }

    //establecen los atributos de la clase

    function set_id($id)
    {
        $this->id = $id;
    }
    function set_nombre($nombre)
    {
        $this->nombre = $nombre;
    }
    function set_apellidos($apellidos)
    {
        $this->apellidos =$apellidos;
    }
    function set_direccion($direccion)
    {
        $this->direccion = $direccion;
    }
    function set_telefono1($telefono1)
    {
        $this->telefono1 =$telefono1;
    }
    function set_telefono2($telefono2)
    {
        $this->telefono2 = $telefono2;
    }
    function set_email($email)
    {
        $this->email = $email;
    }
    function set_cargo($cargo)
    {
        $this->cargo = $cargo;
    }
    function set_comentario($comentario)
    {
        $this->comentario = $comentario;
    }
    function set_username($username)
    {
        $this->username = $username;
    }
    function set_password($password)
    {
        $this->password = $password;
    }
    function set_activo($activo)
    {
        $this->activo = $activo;
    }
    function set_tipo_usuario($tipo_usuario)
    {
        $this->tipo_usuario = $tipo_usuario;
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

    function SQL_insert_usuario()
    {
        $query_insert = sprintf("INSERT INTO usuario (nombre, apellidos, direccion, telefono1, telefono2, email, cargo, comentario, username, password, activo, id_tipo_usuario)
                                 VALUES (%s, %s, %s %s, %s %s, %s %s, %s %s, %s, %s)",
            $this->GetSQLValueString($this->nombre, "text"),
            $this->GetSQLValueString($this->apellidos, "text"),
            $this->GetSQLValueString($this->direccion, "text"),
            $this->GetSQLValueString($this->telefono1, "text"),
            $this->GetSQLValueString($this->telefono2, "text"),
            $this->GetSQLValueString($this->email, "text"),
            $this->GetSQLValueString($this->cargo, "text"),
            $this->GetSQLValueString($this->comentario, "text"),
            $this->GetSQLValueString($this->username, "text"),
            $this->GetSQLValueString($this->password, "text"),
            $this->GetSQLValueString($this->activo, "int"),
            $this->GetSQLValueString($this->tipo_usuario, "int"));
        return $query_insert;
    }
    // arma el query de actualizacion

    function SQL_update_usuario()
    {
        $query_update =sprintf("UPDATE tema SET nombre=%s, apellidos=%s, direccion=%s,
                                                telefono1=%s, telefono2=%s, email=%s,
                                                cargo =%s, comentario=%s, username=%s, password=%s,
                                                activo=s%
                                                WHERE id_usuario=%s LIMIT 1",
            $this->GetSQLValueString($this->nombre, "text"),
            $this->GetSQLValueString($this->apellidos, "text"),
            $this->GetSQLValueString($this->direccion, "text"),
            $this->GetSQLValueString($this->telefono1, "text"),
            $this->GetSQLValueString($this->telefono2, "text"),
            $this->GetSQLValueString($this->email, "text"),
            $this->GetSQLValueString($this->cargo, "text"),
            $this->GetSQLValueString($this->comentario, "text"),
            $this->GetSQLValueString($this->username, "text"),
            $this->GetSQLValueString($this->password, "text"),
            $this->GetSQLValueString($this->activo, "int"),
            $this->GetSQLValueString($this->id, "int"));

        return $query_update;
    }

    //arma el query de eliminacion de tema
    function SQL_delete_usuario()
    {
        $query_delete =sprintf("DELETE FROM usuario WHERE id_usuario =%s LIMIT 1",
            $this->GetSQLValueString($this->id, "int"));
        return $query_delete;
    }
}
?>
