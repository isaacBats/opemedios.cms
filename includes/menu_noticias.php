<!-- Menu de opciones de las noticias, debe de ir entre las etiquetas td de la tabla designada a ello -->

<?php

$redireccion_editar = array(1=>"edit_noticia_electronico.php",
    2=>"edit_noticia_electronico.php",
    3=>"edit_noticia_prensa.php",
    4=>"edit_noticia_prensa.php",
    5=>"edit_noticia_internet.php");

$menu_item_editar = '<li><a class="MenuBarItemSubmenu" href="#">Datos de Noticia</a>
                         <ul>
                             <li><a href="'.$redireccion_editar[$noticia->getId_tipo_fuente()].'?id_noticia='.$noticia->getId().'&id_tipo_fuente='.$noticia->getId_tipo_fuente().'">Editar Informacion</a></li>
                         </ul>
                     </li>';

$menu_item_archivos = '<li><a class="MenuBarItemSubmenu" href="#">Archivos</a>
                           <ul>
                               <li><a href="set_archivo_principal.php?id_noticia='.$noticia->getId().'">Establece Principal</a></li>
                               <li><a href="add_archivo_secundario.php?id_noticia='.$noticia->getId().'">Agregar Secundario</a></li>
                           </ul>
                       </li>';
$menu_item_archivos_prensa = '<li><a class="MenuBarItemSubmenu" href="#">Archivos</a>
                                  <ul>
                                      <li><a href="set_archivo_principal.php?id_noticia='.$noticia->getId().'">Establece Principal</a></li>
                                      <li><a href="add_archivo_secundario.php?id_noticia='.$noticia->getId().'">Agregar Secundario</a></li>
                                      <li><a href="set_archivo_contenedor.php?id_noticia='.$noticia->getId().'">Establecer Contenedor</a></li>
                                  </ul>
                              </li>';

$menu_item_enviar = '<li><a href="envia_noticia.php?id_noticia='.$noticia->getId().'">Enviar Noticia</a></li>';
$menu_item_vernoticia = '<li><a href="ver_noticia_selector.php?id_noticia='.$noticia->getId().'&id_tipo_fuente='.$noticia->getId_tipo_fuente().'">Visualizar Noticia</a></li>';
//borrar noticia
$menu_item_borrar_noticia = '<li><a onclick="if(!confirm(\'Está seguro de borrar la noticia '.$noticia->getId().'?\'))return false" href="borra_noticia.php?id_noticia='.$noticia->getId().'&id_tipo_fuente='.$noticia->getId_tipo_fuente().'">Eliminar Noticia</a></li>';
?>

<div align="center">
    <ul id="MenuBar2" class="MenuBarVertical">
    <?php
    if(($current_user->get_id() == $noticia->getId_usuario()) || ($current_user->get_tipo_usuario() == 1) || ($current_user->get_tipo_usuario() == 2))
    {
        echo $menu_item_editar;
        if($noticia->getId_tipo_fuente() == 3 || $noticia->getId_tipo_fuente() == 4)
        {
            echo $menu_item_archivos_prensa;
        }
        else
        {
            echo $menu_item_archivos;
        }
      
        echo $menu_item_vernoticia;
		
		echo $menu_item_borrar_noticia;
       
    }
    if(($current_user->get_tipo_usuario() == 1) || ($current_user->get_tipo_usuario() == 2) || ($current_user->get_tipo_usuario() == 3))
    {
        echo $menu_item_enviar;
    }
    ?>
    </ul>
</div>
