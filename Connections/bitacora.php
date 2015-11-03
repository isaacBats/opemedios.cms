<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_bitacora = "localhost";
$database_bitacora = "opemedios";
$username_bitacora = "opemedios";
$password_bitacora = "opemedios";
$bitacora = mysql_pconnect($hostname_bitacora, $username_bitacora, $password_bitacora) or trigger_error(mysql_error(),E_USER_ERROR); 
?>