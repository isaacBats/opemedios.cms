<?php 

//============================================================+
// File name   : pdf_not_cli.php
// 
// Description : Genera el Reporte de las Noticias que tiene
//               un determinado cliente segun determinados
//               parámetros
// 
// Author: Josué Morado
//         
// 
//============================================================+


require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false); 

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Operadora de Medios Informativos S.A de C.V');
$pdf->SetTitle('Noticias de CLIENTE '); 
$pdf->SetSubject('Reporte del FECHA1 a FECHA2'); 
$pdf->SetKeywords('TCPDF, PDF, Reporte, Opemedios, clientes');

// set default header data
$pdf->SetHeaderData('logo_pdf.jpg', 40, 'OPERADORA DE MEDIOS INFORMATIVOS S.A DE C.V', 'Noticias del Cliente: CLIENTE.');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

//set some language-dependent strings
$pdf->setLanguageArray($l); 

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 8);

// add a page
$pdf->AddPage();

// -----------------------------------------------------------------------------


// aqui empieza el pedo


// Imprimimos HTML

// parametros del reporte

$htmlcode = '<p>
<span align="right" style="font-weight: bold; font-size:medium;">Reporte generado el 30 de Febrero de 2010</span><br>
<span style="font-weight: bold; font-size:large;">I. Par&aacute;metros del Reporte:</span><br><br><br>
<table border="1" cellspacing="2" cellpadding="2">
	<tr style="background-color:#fddde0;">
		<th align="center" colspan="1">Monitoreo efectuado del <strong>01 de Febrero de 2010</strong> al <strong>30 de Febrero del 2010</strong></th>
	</tr>
	<tr>
		<td>
			<table cellspacing ="0">
				<tr style="background-color:#eaf5f6;">
					<th style="font-weight:bold;" align="center" colspan="1">Tema:</th>
					<th style="font-weight:bold;" align="center" colspan="1">Tipo de Fuente</th>
					<th style="font-weight:bold;" align="center" colspan="1">Fuente</th>
					<th style="font-weight:bold;" align="center" colspan="1">Sección</th>
				</tr>
				<tr>
					<td align="center">Todos</td>
					<td align="center">Todos</td>
					<td align="center">Todos</td>
					<td align="center">Todos</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table cellspacing ="0">
				<tr style="background-color:#eaf5f6;">
					<th style="font-weight:bold;" align="center" colspan="1">Sector</th>
					<th style="font-weight:bold;" align="center" colspan="1">Género</th>
					<th style="font-weight:bold;" align="center" colspan="1">Tipo de Autor</th>
					<th style="font-weight:bold;" align="center" colspan="1">Tendencia</th>
				</tr>
				<tr>
					<td align="center">Todos</td>
					<td align="center">Todos</td>
					<td align="center">Todos</td>
					<td align="center">Todos</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br><br>

</p>';

$pdf->writeHTML($htmlcode, true, 0, true, 0);


//estadisticas  de tipofuente/fuente/seccion

$htmlcode = '<p>
<span style="font-weight: bold; font-size:large;">II. Estadísticas:</span><br><br><br>
<span style="font-size:medium; text-decoration:underline;">2.1 Tipo de Fuente / Fuente / Sección: (Número de Noticias)</span><br><br>
<table border="1" cellspacing="0" cellpadding="3">
 <thead>
  <tr cellspacing="0" border="0" style="background-color:#eaf5f6; font-weight:bold;">
    <td>Tipo de Fuente</td>
    <td>Fuente</td>
    <td>Sección</td>
  </tr>
 </thead>
  <tr>
    <td rowspan="4">Televisión: 100</td>
    <td rowspan="2">A LAS TRES: 10</td>
    <td>Salud: 2</td>
  </tr>
  <tr>
    <td>Cultura: 8</td>
  </tr>
  <tr>
    <td rowspan="2">ACCION: 90</td>
    <td>El Oso Internacional: 60</td>
  </tr>
    <tr>
    <td>Gol, Error y Figura: 30</td>
  </tr>
  <tr>
    <td rowspan="2">Radio: 200</td>
    <td rowspan="1">Cúpula Empresarial: 100</td>
    <td>General: 100</td>
  </tr>
  <tr>
    <td rowspan="1">Horizonte: 100</td>
    <td>General: 100</td>
  </tr>
  <tr>
    <td rowspan="4">Periódico: 100</td>
    <td rowspan="2">EL UNIVERSAL: 10</td>
    <td>Salud: 2</td>
  </tr>
  <tr>
    <td>Cultura: 8</td>
  </tr>
  <tr>
    <td rowspan="2">REFORMA: 90</td>
    <td>CULTURA: 60</td>
  </tr>
  <tr>
    <td>ESPECTÁCULOS: 30</td>
  </tr>
  <tr>
    <td rowspan="2">Revista: 200</td>
    <td rowspan="1">Automovil Panamericano: 100</td>
    <td>Pruebas: 100</td>
  </tr>
  <tr>
    <td rowspan="1">Cosmopolitan: 100</td>
    <td>General: 100</td>
  </tr>
  <tr>
    <td rowspan="2">Internet: 200</td>
    <td rowspan="1">El Universal Online: 100</td>
    <td>Deportes: 100</td>
  </tr>
  <tr>
    <td rowspan="1">Prodigy MSN: 100</td>
    <td>General: 100</td>
  </tr>
  <tr style="background-color:#fddde0; font-weight:bold;">
  	<td colspan="3" align="center">NOTICIAS TOTALES: 800</td>
  </tr>
</table>
<br><br>

</p>';

$pdf->writeHTML($htmlcode, true, 0, true, 0);


//estadisticas  de otros atributos

$htmlcode = '<p>
<span style="font-size:medium; text-decoration:underline;">2.2 Otros Atributos: (Número de Noticias)</span><br><br>
	<table cellspacing ="0" cellpadding ="2" border="1">
		<thead>
				<tr style="background-color:#eaf5f6;">
					<td style="font-weight:bold;" align="center" colspan="1">Sector:</td>
					<td style="font-weight:bold;" align="center" colspan="1">Género</td>
					<td style="font-weight:bold;" align="center" colspan="1">Tipo de Autor:</td>
					<td style="font-weight:bold;" align="center" colspan="1">Tendencia:</td>
				</tr>
		</thead>
				<tr>
					<td align="center">
						<table border="0" cellspacing ="0" cellpadding ="2">
							<tr>
								<td>Alimentos: 3</td>
							</tr>
							<tr>
								<td>Automotriz: 16</td>
							</tr>
						</table>
					</td>
					<td align="center">
						<table border="0" cellspacing ="0" cellpadding ="2">
							<tr>
								<td>Entrevista: 3</td>
							</tr>
							<tr>
								<td>Noticia: 16</td>
							</tr>
						</table>
					</td>
					<td align="center">
						<table border="0" cellspacing ="0" cellpadding ="2">
							<tr>
								<td>Conductor: 3</td>
							</tr>
							<tr>
								<td>Reportero: 16</td>
							</tr>
						</table>
					</td>
					<td align="center">
						<table border="0" cellspacing ="0" cellpadding ="2">
							<tr>
								<td>Positiva: 3</td>
							</tr>
							<tr>
								<td>Neutral: 16</td>
							</tr>
							<tr>
								<td>Negativa: 19</td>
							</tr>
						</table>
					</td>
				</tr>
	</table>
<br><br>

</p>';

$pdf->writeHTML($htmlcode, true, 0, true, 0);


//Ahora mostramos las Noticias

// por tema

$htmlcode = '<p>
<span style="font-weight: bold; font-size:large;">III. Noticias:</span><br><br><br>
<span style="font-size:medium; text-decoration:underline;">3.1 Por Tema: (Número de Noticias)</span><br><br>
<table border="1" cellspacing="0" cellpadding="1">
	<thead>
	<tr style="background-color:#eaf5f6;">
		<td style="font-weight:bold;" align="center" colspan="1">Tema</td>
		<td style="font-weight:bold;" align="center" colspan="1">Noticias</td>
	</tr>
	</thead>
	<tr>
		<td>Tema1</td>
		<td>30</td>
	</tr>
	<tr>
		<td>Tema2</td>
		<td>60</td>
	</tr>
	<tr>
		<td>Tema3</td>
		<td>10</td>
	</tr>
	<tr>
		<td>Tema4</td>
		<td>400</td>
	</tr>
	<tr>
		<td>Tema5</td>
		<td>300</td>
	</tr>
</table>
<br><br>

</p>';

$pdf->writeHTML($htmlcode, true, 0, true, 0);


// Detalle Noticias
$htmlcode = '<p>
<span style="font-size:medium; text-decoration:underline;">3.2 Detalle Noticias:</span><br><br>
<table border="1" cellspacing="2" cellpadding="2">
	<thead>
		<tr style="background-color:#fddde0; font-weight:bold;">
			<td align="center">TEMA: TEMA 1</td>
		</tr>
	</thead>
		<tr align="center">
			<td>
				<table border="1" cellspacing="0" cellpadding="1">
					<tr align="center" style="background-color:#eaf5f6; font-weight:bold;">
						<td width="35"></td>
						<td width="75">Fecha</td>
						<td width="150">Encabezado</td>
						<td width="150">Síntesis</td>
						<td width="111">Fuente</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Televisión</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Radio</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Periódico</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Revista</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Internet</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
				</table>
			</td>
		</tr>
</table>
<table border="1" cellspacing="2" cellpadding="2">
	<thead>
		<tr style="background-color:#fddde0; font-weight:bold;">
			<td align="center">TEMA: TEMA 2</td>
		</tr>
	</thead>
		<tr align="center">
			<td>
				<table border="1" cellspacing="0" cellpadding="1">
					<tr align="center" style="background-color:#eaf5f6; font-weight:bold;">
						<td width="35"></td>
						<td width="75">Fecha</td>
						<td width="150">Encabezado</td>
						<td width="150">Síntesis</td>
						<td width="111">Fuente</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Televisión</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Radio</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Periódico</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Revista</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Internet</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
				</table>
			</td>
		</tr>
</table>
<table border="1" cellspacing="2" cellpadding="2">
	<thead>
		<tr style="background-color:#fddde0; font-weight:bold;">
			<td align="center">TEMA: TEMA 3</td>
		</tr>
	</thead>
		<tr align="center">
			<td>
				<table border="1" cellspacing="0" cellpadding="1">
					<tr align="center" style="background-color:#eaf5f6; font-weight:bold;">
						<td width="35"></td>
						<td width="75">Fecha</td>
						<td width="150">Encabezado</td>
						<td width="150">Síntesis</td>
						<td width="111">Fuente</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Televisión</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Radio</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Periódico</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Revista</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Internet</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
				</table>
			</td>
		</tr>
</table>
<table border="1" cellspacing="2" cellpadding="2">
	<thead>
		<tr style="background-color:#fddde0; font-weight:bold;">
			<td align="center">TEMA: TEMA 4</td>
		</tr>
	</thead>
		<tr align="center">
			<td>
				<table border="1" cellspacing="0" cellpadding="1">
					<tr align="center" style="background-color:#eaf5f6; font-weight:bold;">
						<td width="35"></td>
						<td width="75">Fecha</td>
						<td width="150">Encabezado</td>
						<td width="150">Síntesis</td>
						<td width="111">Fuente</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Televisión</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Radio</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Periódico</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Revista</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Internet</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
				</table>
			</td>
		</tr>
</table>
<table border="1" cellspacing="2" cellpadding="2">
	<thead>
		<tr style="background-color:#fddde0; font-weight:bold;">
			<td align="center">TEMA: TEMA 5</td>
		</tr>
	</thead>
		<tr align="center">
			<td>
				<table border="1" cellspacing="0" cellpadding="1">
					<tr align="center" style="background-color:#eaf5f6; font-weight:bold;">
						<td width="35"></td>
						<td width="75">Fecha</td>
						<td width="150">Encabezado</td>
						<td width="150">Síntesis</td>
						<td width="111">Fuente</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Televisión</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Radio</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Periódico</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Revista</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
					<tr align="center" style="font-size:small;">
						<td width="35">Internet</td>
						<td width="75">2010/02/23</td>
						<td width="150">IRREGULARIDADES EN EL SISTEMA DE AGUAS DEL D.F.</td>
						<td width="150">Los diputados del PAN, Juan Carlos Zárraga, Jorge Palacios y Mariana Gómez del Campo observan que existen irregularidades en la clasificación hecha para fijar las tarifas del agua en el Distrito Federal.</td>
						<td width="111">FORMATO 21</td>
					</tr>
				</table>
			</td>
		</tr>
</table>

<br><br>
</p>';

$pdf->writeHTML($htmlcode, true, 0, true, 0);





// -----------------------------------------------------------------------------

//Close and output PDF document
$pdf->Output('Reporte_Clientes.pdf', 'I');

//============================================================+
// END OF FILE                                                 
//============================================================+
?>