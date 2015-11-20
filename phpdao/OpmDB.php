<?php
/* 
 * Objetos de conexion a base de datos
 *
 * @author Josue Morado Manríquez
 */
class OpmDB
{
    private $databaseURL;
    private $databaseUName;
    private $databasePWord;
    private $databaseName;
    private $id_conexion;
    private $id_conexion2;
    private $id_consulta;
    private $id_consulta2;
    private $id_consulta_usr;
    private $id_consulta_sector;
    private $error;
    private $errorno;


    function  __construct($datos)
    {
        $this->databaseURL = $datos['databaseURL'];
        $this->databaseUName = $datos['databaseUname'];
        $this->databasePWord = $datos['databasePword'];
        $this->databaseName = $datos['databaseName'];
        $this->id_conexion = 0;
        $this->id_conexion2 = 0;
        $this->id_consulta = 0;
        $this->id_consulta2 = 0;
        $this->id_consulta_usr = 0;
        $this->id_consulta_sector = 0;
        $this->error = "";
        $this->errorno = 0;
    }


    function  __destruct()
    {
        ;
    }

    function get_error()
    {
        return "Error ".$this->errorno."  ->  ".$this->error;
    }


    //inicia la base de datos
    function init()
    {
        $this->id_conexion = mysql_connect($this->databaseURL, $this->databaseUName, $this->databasePWord) or die("Hubo error al conectarse a la base");
		mysql_query("SET NAMES 'utf8'");
        //revisa si hubo error en al conexión
        if(!$this->id_conexion)
        {
            $this->error = "Error al iniciar la conexión";
            return 0;
        }		
        $db = mysql_select_db($this->databaseName, $this->id_conexion);
        //revisa si hubo error al seleccionar la base de datos
        if(!$db)
        {
            $this->Error = "Error al abrir la base de datos:  ".$this->databaseName;
            return 0;
        }
        //si todo sale bien nos regresa el id de la conexion
        return $this->id_conexion;
    }

    //cierra la conexion a la base de datos
    function close()
    {
        mysql_close($this->id_conexion);
    }

    //ejecuta consultas
    function execute_query($query="")
    {
        if($query=="")
        {
            $this->error = "No se introdujo ninguna sentencia SQL";
            return 0;
        }

        //ejecutar consulta
        $this->id_consulta = mysql_query($query,$this->id_conexion) or die("Hubo el siguiente error al ejecutar la consulta: <br>".mysql_error()." ".mysql_errno()."<br>La consulta fue la siguiente<br>".$query);
        if(!$this->id_consulta)
        {
            $this->errorno = mysql_errno();
            $this->error = mysql_error();
            return 0;
        }
        //Si todo sale chido
        return $this->id_consulta;
    }

    //regresa el numero de registros de la consulta actual
    function num_rows()
    {
        return mysql_num_rows($this->id_consulta);
    }

    // regresa el nombre del campo del arreglo
    function field_name($campo)
    {
        return mysql_field_name($this->id_consulta,$campo);
    }

    // muestra la info de la fila en arreglo numerico
    function get_row()
    {
        return mysql_fetch_row($this->id_consulta);
    }

    // muestra la info de la fila en arreglo asociativo
    function get_row_assoc()
    {
        return mysql_fetch_assoc($this->id_consulta);
    }

    //consulta2
    function execute_query2($query="")
    {
        if($query=="")
        {
            $this->error = "No se introdujo ninguna sentencia SQL";
            return 0;
        }

        //ejecutar consulta
        $this->id_consulta2 = mysql_query($query,$this->id_conexion) or die("Hubo el siguiente error al ejecutar la consulta: <br>".mysql_error()." ".mysql_errno()."<br>La consulta fue la siguiente<br>".$query);
        if(!$this->id_consulta2)
        {
            $this->errorno = mysql_errno();
            $this->error = mysql_error();
            return 0;
        }
        //Si todo sale chido
        return $this->id_consulta2;
    }

    //regresa el numero de registros de la consulta actual
    function num_rows2()
    {
        return mysql_num_rows($this->id_consulta2);
    }

    // regresa el nombre del campo del arreglo
    function field_name2($campo)
    {
        return mysql_field_name($this->id_consulta2,$campo);
    }

    // muestra la info de la fila en arreglo numerico
    function get_row2()
    {
        return mysql_fetch_row($this->id_consulta2);
    }

    // muestra la info de la fila en arreglo asociativo
    function get_row_assoc2()
    {
        return mysql_fetch_assoc($this->id_consulta2);
    }

     function free_result2()
    {
        mysql_free_result($this->id_consulta2);
    }

    //revisa si existe el username de una cuenta para el portal, regresa true si ya existe y false si no existe
    function exists_username_cuenta($username)
    {
        $query_check_username =sprintf("SELECT COUNT(*) FROM cuenta where username ='%s'", $username);
        $this->id_consulta_usr = mysql_query($query_check_username,$this->id_conexion);
        $usr = mysql_fetch_row($this->id_consulta_usr);
        if ($usr[0]>=1)
        {
            mysql_free_result($this->id_consulta_usr);
            return true;
        }
        else
        {
            mysql_free_result($this->id_consulta_usr);
            return false;
        }
    }

    //revisa si existe el username de un usuario del sistema. Regresa true si ya existe y false si no existe
    function exists_username_usuario($username)
    {
        $query_check_username =sprintf("SELECT COUNT(*) FROM usuario where username ='%s'", $username);
        $this->id_consulta_usr = mysql_query($query_check_username,$this->id_conexion);
        $usr = mysql_fetch_row($this->id_consulta_usr);
        if ($usr[0]>=1)
        {
            mysql_free_result($this->id_consulta_usr);
            return true;
        }
        else
        {
            mysql_free_result($this->id_consulta_usr);
            return false;
        }
    }

    function exists_sector($sector)
    {
        $query_check_sector =sprintf("SELECT COUNT(*) FROM sector where nombre ='%s'", $sector);
        $this->id_consulta_sector = mysql_query($query_check_sector,$this->id_conexion);
        $sec = mysql_fetch_row($this->id_consulta_sector);
        if ($sec[0]>=1)
        {
            mysql_free_result($this->id_consulta_sector);
            return true;
        }
        else
        {
            mysql_free_result($this->id_consulta_sector);
            return false;
        }
    }

    //libera el resultado de la memoria
    function free_result()
    {
        mysql_free_result($this->id_consulta);
    }


}//end class


?>
