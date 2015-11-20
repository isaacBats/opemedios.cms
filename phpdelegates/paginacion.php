<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$registros = 20;#numero de registros por pagina
function paginacion_init($get,$registros){
//$pagina = $_GET["pagina"];
$pagina = $get;
if (!$pagina) {
$inicio = 0;
$pagina = 1;
}
else {
$inicio = ($pagina - 1) * $registros;
}
//return $inicio;
$total = array($inicio,
               $pagina
              );
return $total;
}

function paginacion($url,$pagina,$total_registros,$registros){
$total_paginas = ceil($total_registros / $registros);
if($total_registros) {
		$paginacion.="<center>";
		if(($pagina - 1) > 0) {
			 $paginacion.="<a href='".$url."&pagina=".($pagina-1)."'>< Anterior</a> ";
		}
		for ($i=1; $i<=$total_paginas; $i++){
			if ($pagina == $i) {
				 $paginacion.="<b>".$pagina."</b> ";
			} else {
				 $paginacion.="<a href='".$url."&pagina=$i'>$i</a> ";
			}
		}
	   if(($pagina + 1)<=$total_paginas) {
			  $paginacion.= "<a href='".$url."&pagina=".($pagina+1)."'>Siguiente ></a>";
		}
		 $paginacion.="</center>";
}
return $paginacion;
}




?>
