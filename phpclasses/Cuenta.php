<?php
/**
 * cada empresa cliente tiene varias cuentas para acceder al sistema y tienen diferentes correos electronicos
 * el atributo cuentas  de la clase empresa sera  un arreglo de objetos de la clase Cuenta
 *
 * @author Josue Morado Manríquez
 */
class Cuenta
{
    private $id;
    private $nombre;
    private $apellidos;
    private $cargo;
    private $telefono1;
    private $telefono2;
    private $email;
    private $comentario;
    private $username;
    private $password;
    private $activo;
    private $id_empresa;

    function  __construct($datos)
    {
        $this->id = $datos['id_cuenta'];
        $this->nombre = $datos['nombre'];
        $this->apellidos = $datos['apellidos'];
        $this->cargo = $datos['cargo'];
        $this->telefono1 = $datos['telefono1'];
        $this->telefono2 = $datos['telefono2'];
        $this->email = $datos['email'];
        $this->comentario = $datos['comentario'];
        $this->username = $datos['username'];
        $this->password = $datos['password'];
        $this->activo = $datos['activo'];
        $this->id_empresa = $datos['id_empresa'];
    }

    function  __destruct()
    {
        ;
    }

//establecen las propiedades del objeto
    function set_nombre($nombre)
    {
        $this->nombre = $nombre;
    }
    function set_apellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }
    function set_cargo($cargo)
    {
        $this->cargo = $cargo;
    }
    function set_telefono1($telefono1)
    {
        $this->telefono1 = $telefono1;
    }
    function set_telefono2($telefono2)
    {
        $this->telefono2 = $telefono2;
    }
    function set_email($email)
    {
        $this->email = $email;
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
//Obtienen los valores de los atributos del objeto
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
    function get_cargo()
    {
        return $this->cargo;
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
    function get_id_empresa()
    {
        return $this->id_empresa;
    }
    function get_nombre_completo()
    {
        return $this->nombre." ".$this->apellidos;
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

    function SQL_insert_cuenta()
    {
        $query_insert = sprintf("INSERT INTO cuenta (nombre, apellidos, cargo, telefono1, telefono2, email, comentario, username, password, activo, id_empresa )
                                 VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                         $this->GetSQLValueString($this->nombre, "text"),
                         $this->GetSQLValueString($this->apellidos, "text"),
                         $this->GetSQLValueString($this->cargo, "text"),
                         $this->GetSQLValueString($this->telefono1, "text"),
                         $this->GetSQLValueString($this->telefono2, "text"),
                         $this->GetSQLValueString($this->email, "text"),
                         $this->GetSQLValueString($this->comentario, "text"),
                         $this->GetSQLValueString($this->username, "text"),
                         $this->GetSQLValueString($this->password, "text"),
                         $this->GetSQLValueString($this->activo, "int"),
                         $this->GetSQLValueString($this->id_empresa, "int"));
        return $query_insert;
    }
    // arma el query de actualizacion

  function SQL_update_cuenta()
    {
        $query_update =sprintf("UPDATE cuenta SET nombre=%s, apellidos=%s, cargo =%s,
                                                telefono1=%s, telefono2=%s, email=%s,
                                                comentario=%s, username=%s, password=%s,
                                                activo=%s
                                                WHERE id_cuenta=%s LIMIT 1",
                       $this->GetSQLValueString($this->nombre, "text"),
                       $this->GetSQLValueString($this->apellidos, "text"),
                       $this->GetSQLValueString($this->cargo, "text"),
                       $this->GetSQLValueString($this->telefono1, "text"),
                       $this->GetSQLValueString($this->telefono2, "text"),
                       $this->GetSQLValueString($this->email, "text"),
                       $this->GetSQLValueString($this->comentario, "text"),
                       $this->GetSQLValueString($this->username, "text"),
                       $this->GetSQLValueString($this->password, "text"),
                       $this->GetSQLValueString($this->activo, "int"),
                       $this->GetSQLValueString($this->id, "int"));
                   
        return $query_update;
    }
//arma el query de eliminacion de tema
    function SQL_delete_cuenta()
    {
        $query_delete =sprintf("DELETE FROM cuenta WHERE id_cuenta =%s LIMIT = 1",
                       $this->GetSQLValueString($this->id_cuenta, "int"));
        return $query_delete;
    }

   

}//end class
?>
