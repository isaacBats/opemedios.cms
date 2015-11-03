<?php 

include("phpdelegates/rest_access_1.php");
include("phpdelegates/logout.php");

foreach($_SESSION as $sesion)
{
	echo $sesion['MM_Username'];
}


?>