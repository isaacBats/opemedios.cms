<!-- Menu de opciones de las fuentes, debe de ir entre las etiquetas td de la tabla designada a ello -->

<?php

$redireccion_editar = array(1=>"edit_fuente_tv.php",
                         2=>"edit_fuente_radio.php",
                         3=>"edit_fuente_prensa.php",
                         4=>"edit_fuente_prensa.php",
                         5=>"edit_fuente_internet.php");

$redireccion_ver = array(1=>"ver_fuente_tv.php",
                         2=>"ver_fuente_radio.php",
                         3=>"ver_fuente_prensa.php",
                         4=>"ver_fuente_prensa.php",
                         5=>"ver_fuente_internet.php");

$redireccion_tarifas = array(1=>"#",
                             2=>"#",
                             3=>"admin_tarifas_prensa.php",
                             4=>"admin_tarifas_prensa.php",
                             5=>"#");

$redireccion_tarifas_add = array(1=>"#",
                                 2=>"#",
                                 3=>"add_tarifa_prensa.php",
                                 4=>"add_tarifa_prensa.php",
                                 5=>"#");

?>


<div align="center">
    <ul id="MenuBar2" class="MenuBarVertical">
        <li><a class="MenuBarItemSubmenu" href="#">Datos de Fuente</a>
            <ul>
                <li><a href="<?php if($fuente->get_id_tipo_fuente() == 3 || $fuente->get_id_tipo_fuente() == 4){echo $redireccion_editar[$fuente->get_id_tipo_fuente()].'?id_fuente='.$fuente->get_id().'&id_tipo_fuente='.$fuente->get_id_tipo_fuente();}else{echo $redireccion_editar[$fuente->get_id_tipo_fuente()].'?id_fuente='.$fuente->get_id();} ?>">Editar Informacion</a></li>
                <li><a href="<?php echo "set_logo_fuente.php?id_fuente=".$fuente->get_id(); ?>">Cambiar Logo</a></li>
            </ul>
        </li>
        <li><a href="#" class="MenuBarItemSubmenu">Secciones</a>
            <ul>
                <li><a href="<?php echo "admin_secciones.php?id_fuente=".$fuente->get_id(); ?>">Administrar  Secciones</a></li>
            </ul>
        </li>
        <?php if($fuente->get_id_tipo_fuente() == 3 || $fuente->get_id_tipo_fuente() == 4){?>
        <li><a href="#" class="MenuBarItemSubmenu">Tarifas</a>
            <ul>
                <li><a href="<?php echo $redireccion_tarifas_add[$fuente->get_id_tipo_fuente()].'?id_fuente='.$fuente->get_id(); ?>">Agregar Tarifa</a></li>
                <li><a href="<?php echo $redireccion_tarifas[$fuente->get_id_tipo_fuente()].'?id_fuente='.$fuente->get_id(); ?>">Administrar Tarifas</a></li>
            </ul>
        </li>
        <?php }//end if fuentes = 3 o 4?>
        <li><a href="<?php if($fuente->get_id_tipo_fuente() == 3 || $fuente->get_id_tipo_fuente() == 4){echo $redireccion_ver[$fuente->get_id_tipo_fuente()].'?id_fuente='.$fuente->get_id().'&id_tipo_fuente='.$fuente->get_id_tipo_fuente();}else{echo $redireccion_ver[$fuente->get_id_tipo_fuente()].'?id_fuente='.$fuente->get_id();} ?>">Ver Informacion</a></li>
    </ul>
</div>