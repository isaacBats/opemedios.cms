<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
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

$colname_detalle_entradas = "-1";
if (isset($_GET['id'])) {
  $colname_detalle_entradas = $_GET['id'];
}
mysql_select_db($database_guru, $guru);
$query_detalle_entradas = sprintf("SELECT * FROM entradas WHERE id = %s", GetSQLValueString($colname_detalle_entradas, "int"));
$detalle_entradas = mysql_query($query_detalle_entradas, $guru) or die(mysql_error());
$row_detalle_entradas = mysql_fetch_assoc($detalle_entradas);
$totalRows_detalle_entradas = mysql_num_rows($detalle_entradas);

$colname_detalle_reclu = "-1";
if (isset($_GET['id_reclu'])) {
  $colname_detalle_reclu = $_GET['id_reclu'];
}
   
      require_once("../pdf1/dompdf_config.inc.php");
   
      $html ='
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<style>
body {
  margin: 18pt 18pt 24pt 18pt;
}

* {
  font-family: georgia,serif;
  font-weight: bold;
}

p {
  text-align: justify;
  font-size: 1em;
  margin: 0.5em;
  padding: 10px;
}
.titulo_principal {	font-family: Arial, Helvetica, sans-serif;
	font-size: 18px;
	font-weight: bold;
}
.contenido {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 9.5px;
	align
; 							white-space: normal;
	text-align: justify;
}
</style>
<script type="text/php">

if ( isset($pdf) ) {

  $font = Font_Metrics::get_font("verdana");;
  $size = 6;
  $color = array(0,0,0);
  $text_height = Font_Metrics::get_font_height($font, $size);

  $foot = $pdf->open_object();
  
  $w = $pdf->get_width();
  $h = $pdf->get_height();

  // Draw a line along the bottom
  $y = $h - $text_height - 24;
  $pdf->line(16, $y, $w - 16, $y, $color, 0.5);

  $pdf->close_object();
  $pdf->add_object($foot, "all");

  $text = "Page {PAGE_NUM} of {PAGE_COUNT}";  

  // Center the text
  $width = Font_Metrics::get_text_width("Page 1 of 2", $font, $size);
  $pdf->page_text($w / 2 - $width / 2, $y, $text, $font, $size, $color);
  
}
</script>
</head>
<body>
'.$cadena.'
</body> 
</html>

';
   
       
  
      $dompdf = new DOMPDF();
	  
	//  $dompdf ->image('../pdf1/www/images/dompdf_simple.png');
   
      $dompdf->load_html($html);
	 
	  $dompdf->set_paper("610","792", "landscape");
  
      $dompdf->render();
 
      $dompdf->stream($row_detalle_entradas['nombre'].".pdf");

?>
<?php
mysql_free_result($Recordset1);

mysql_free_result($detalle_reclu);
?>