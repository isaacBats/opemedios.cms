<?php
/* 
 * Codigo para manejar paginacion de resultados de un query
 */


if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
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
}


$currentPage = $_SERVER["PHP_SELF"];

$maxRows = 50;
$pageNum = 0;
if (isset($_GET['pageNum'])) {
  $pageNum = $_GET['pageNum'];
}
$startRow = $pageNum * $maxRows;

$query_noticias = sprintf("SELECT 
                              noticia.id_noticia AS Clave,
                              noticia.encabezado AS Encabezado,
                              noticia.fecha AS Fecha,
                              noticia.id_tipo_fuente AS TipoFuente,
                              fuente.nombre AS NombreFuente,
                              fuente.logo AS LogoFuente,
                              seccion.nombre AS NombreSeccion
                            FROM
                             noticia
                             INNER JOIN fuente ON (noticia.id_fuente=fuente.id_fuente)
                             INNER JOIN seccion ON (noticia.id_seccion=seccion.id_seccion)
                            WHERE
                              noticia.fecha = CURDATE()
                            ORDER BY
                              Clave DESC");

$query_limit_noticias = sprintf("%s LIMIT %d, %d", $query_noticias, $startRow, $maxRows);

$base->execute_query($query_limit_noticias);

//ahora metemos el resultado en un arreglo de objetos empresa
if($base->num_rows()>0)
{
    $arreglo_noticias = array();
    while($row_noticias = $base->get_row_assoc())
    {
        $noticia = new Noticia($row_noticias);
        $arreglo_noticias[$noticia->getId()] = $noticia;
    }
}

if (isset($_GET['totalRows']))
{
  $totalRows = $_GET['totalRows'];
}
else
{
    $base->execute_query($query_noticias);
    $totalRows = $base->num_rows();
}

$totalPages = ceil($totalRows/$maxRows)-1;

$queryString = "";
if (!empty($_SERVER['QUERY_STRING']))
{
    $params = explode("&", $_SERVER['QUERY_STRING']);
    $newParams = array();
    foreach ($params as $param)
    {
        if (stristr($param, "pageNum") == false &&
            stristr($param, "totalRows") == false)
        {
            array_push($newParams, $param);
        }
    }
    if (count($newParams) != 0) {
        $queryString = "&" . htmlentities(implode("&", $newParams));
    }
}
$queryString = sprintf("&totalRows=%d%s", $totalRows, $queryString);

?>
