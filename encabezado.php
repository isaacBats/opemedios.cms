<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>encabezado</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-image: url();
	background-repeat: no-repeat;
	background-color: #000000;
}
-->
</style>
<link href="CSS/opemedios.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="1000" height="87" border="0" align="center" cellpadding="0" cellspacing="0" background="images/images/BackGround_01.jpg">
  <!--DWLayoutTable-->
  <tr>
    <td width="567" rowspan="3" valign="top"></td>
    <td width="256" valign="bottom"></td>
    <td width="177" rowspan="3" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
  </tr>
  <tr>
    <td valign="bottom"></td>
  </tr>
  <tr>
    <td height="65" valign="bottom"><script>

/*Current date script credit: 
JavaScript Kit (www.javascriptkit.com)
Over 200+ free scripts here!
*/

var mydate=new Date()
var year=mydate.getYear()
if (year < 1000)
year+=1900
var day=mydate.getDay()
var month=mydate.getMonth()
var daym=mydate.getDate()
if (daym<10)
daym="0"+daym
var dayarray=new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado")
var montharray=new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre")
document.write("<small><font color='000000' face='Tahoma'>"+dayarray[day]+", "+daym+" de "+montharray[month]+" de "+year+"</font></small>")
</script></td>
  </tr>
  <tr>
    <td width="567" valign="top"></td>
    <td valign="middle"></td>
    <td width="177" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
  </tr>

</table>
</body>
</html>
