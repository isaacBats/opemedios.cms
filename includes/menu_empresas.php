<!-- Menu de opciones de las empresas, debe de ir entre las etiquetas td de la tabla designada a ello -->
<div align="center">
    <ul id="MenuBar2" class="MenuBarVertical">
        <li><a class="MenuBarItemSubmenu" href="#">Datos de Empresa</a>
            <ul>
                <li><a href="<?php echo "edit_info_cliente.php?id_empresa=".$empresa->get_id(); ?>">Editar Informacion</a></li>
                <li><a href="<?php echo "set_logo_empresa.php?id_empresa=".$empresa->get_id(); ?>">Cambiar Logo</a></li>
            </ul>
        </li>
        <li><a href="#" class="MenuBarItemSubmenu">Temas</a>
            <ul>
                <li><a href="<?php echo "admin_temas.php?id_empresa=".$empresa->get_id(); ?>">Administrar  Temas</a></li>
            </ul>
        </li>
        <li><a href="#" class="MenuBarItemSubmenu">Cuentas</a>
            <ul>
                <li><a href="<?php echo "add_cuenta.php?id_empresa=".$empresa->get_id(); ?>">Agregar Cuenta</a></li>
                <li><a href="<?php echo "admin_cuentas.php?id_empresa=".$empresa->get_id(); ?>">Administrar Cuentas</a></li>
            </ul>
        </li>
        <li><a href="#" class="MenuBarItemSubmenu">Permisos</a>
            <ul>
                <li><a href="<?php echo "set_permisos.php?id_empresa=".$empresa->get_id(); ?>">Establecer Permisos</a></li>
            </ul>
        </li>
        <li><a href="<?php echo "ver_cliente.php?id_empresa=".$empresa->get_id(); ?>">Ver Informacion</a></li>
        <li><a onclick="if(!confirm('¿Está seguro de eliminar completamente el cliente: <?php echo $empresa->get_nombre();?> junto con sus cuentas y temas?'))return false" href="borra_empresa.php?id_empresa=<?php echo $empresa->get_id();?>">Eliminar Cliente</a></li>
    </ul>
</div>