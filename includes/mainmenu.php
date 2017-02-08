<!-- Menu Principal del Sistema, debe de ir entre las etiquetas td de la tabla designada a ello -->
<div align="center">
    <ul id="MenuPrincipal" class="MenuBarHorizontal">
        <li><a href="#" class="MenuBarItemSubmenu">Noticias</a>
            <ul>
                <li><a href="#" class="MenuBarItemSubmenu">Agregar Noticia</a>
                    <ul>
                        <li><a href="#" onclick="window.open('add_noticia_television.php','','scrollbars=yes,resizable=yes,width=522,height=660')">Televisi&oacute;n</a></li>
                        <li><a href="#" onclick="window.open('add_noticia_radio.php','','scrollbars=yes,resizable=yes,width=522,height=650')">Radio</a></li>
                        <li><a href="#" onclick="window.open('add_noticia_periodico.php','','scrollbars=yes,resizable=yes,width=522,height=660')">Peri&oacute;dico</a></li>
                        <li><a href="#" onclick="window.open('add_noticia_revista.php','','scrollbars=yes,resizable=yes,width=522,height=660')">Revista</a></li>
                        <li><a href="#" onclick="window.open('add_noticia_internet.php','','scrollbars=yes,resizable=yes,width=522,height=660')">Internet</a></li>
                        <li><a href="#" onclick="window.open('add_noticia_red.php','','scrollbars=yes,resizable=yes,width=522,height=660')">Redes Sociales</a></li>
                    </ul>
                </li>
                <li><a href="noticiashoy.php">Noticias de Hoy</a></li>
                <li><a href="busqueda_avanzada.php">B&uacute;squeda Avanzada</a></li>
                <li><a href="envia_noticia_multiple.php?id_noticia=524931">Enviar Bloque Noticias</a></li>				
            </ul>
        </li>
        <li><a href="#" class="MenuBarItemSubmenu">Fuentes</a>
            <ul>
                <li><a href="#" class="MenuBarItemSubmenu">Agregar Fuentes</a>
                    <ul>
                        <li><a href="add_fuente_tv.php">Televisi&oacute;n</a></li>
                        <li><a href="add_fuente_radio.php">Radio</a></li>
                        <li><a href="add_fuente_periodico.php">Peri&oacute;dico</a></li>
                        <li><a href="add_fuente_revista.php">Revista</a></li>
                        <li><a href="add_fuente_internet.php">Internet</a></li>
                        <li><a href="add_fuente_red.php">Redes Sociales</a></li>
                    </ul>
                </li>
                <li><a href="admin_fuentes.php">Administrar Fuentes</a></li>
            </ul>
        </li>
        <li><a href="#" class="MenuBarItemSubmenu">Sectores</a>
            <ul>
                <li><a href="add_sector.php">Agregar Sector</a></li>
                <li><a href="admin_sectores.php">Administrar Sectores</a></li>
            </ul>
        </li>
        <li><a href="#" class="MenuBarItemSubmenu">Prensa</a>
            <ul>
                <li><a href="#" class="MenuBarItemSubmenu">Primeras Planas</a>
                    <ul>
                        <li><a href="agrega_primeraplana.php">Agregar</a></li>
                        <li><a href="admin_primerasplanas.php">Administrar</a></li>
                        <li><a href="envio_primerasplanas.php">Enviar a Clientes</a></li>
                    </ul>
                </li>
                <li><a href="#" class="MenuBarItemSubmenu">Portadas Financieras</a>
                    <ul>
                        <li><a href="agrega_portadafinanciera.php">Agregar</a></li>
                        <li><a href="admin_portadasfinancieras.php">Administrar</a></li>
                        <li><a href="envio_portadasfinancieras.php">Enviar a Clientes</a></li>
                    </ul>
                </li>
                <li><a href="#" class="MenuBarItemSubmenu">Columnas Pol&iacute;ticas</a>
                    <ul>
                        <li><a href="agrega_colpol.php">Agregar</a></li>
                        <li><a href="admin_colpol.php">Administrar</a></li>
                        <li><a href="envio_colpol.php">Enviar a Clientes</a></li>
                    </ul>
                </li>
                <li><a href="#" class="MenuBarItemSubmenu">Columnas Financieras</a>
                    <ul>
                        <li><a href="agrega_colfin.php">Agregar</a></li>
                        <li><a href="admin_colfin.php">Administrar</a></li>
                        <li><a href="envio_colfin.php">Enviar a Clientes</a></li>
                    </ul>
                </li>
                <li><a href="#" class="MenuBarItemSubmenu">Cartones</a>
                    <ul>
                        <li><a href="agrega_carton.php">Agregar</a></li>
                        <li><a href="admin_cartones.php">Administrar</a></li>
                        <li><a href="envio_cartones.php">Enviar a Clientes</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li><a href="#" class="MenuBarItemSubmenu">Asignaci&oacute;n</a>
            <ul>
                <li><a href="asignacion_noticia_cliente.php">Noticias por Cliente</a> </li>
            </ul>
        </li>
        <li><a href="#" class="MenuBarItemSubmenu">Reportes</a>
            <ul>
                <li><a href="reporte_noticia_cliente.php">Noticias por Cliente</a>
                	<ul>
                        <li><a href="reporte_noticia_cliente.php">PDF</a></li>
                        <li><a href="reporte_noticia_cliente_xls.php">Excel</a></li>
                    </ul>
                </li>
				<li><a href="reporte1.php">Reporte de Notas x Día</a>                	
                </li>
                <!--<li><a href="reporte_noticia_monitorista.php">Noticias por Monitorista</a></li>-->
            </ul>
        </li>
        <li><a href="#" class="MenuBarItemSubmenu">Gr&aacute;ficas</a>
            <ul>
                <li><a href="#" class="MenuBarItemSubmenu">Noticias por Cliente</a>
                    <ul>
                        <li><a href="grafica_clientes_analisis.php">An&aacute;lisis</a></li>
                        <li><a href="grafica_cliente_generales.php">generales</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li><a href="#" class="MenuBarItemSubmenu">Clientes</a>
            <ul>
                <li><a href="agrega_cliente.php">Agregar Cliente</a></li>
                <li><a href="admin_clientes.php">Administrar Clientes</a></li>
            </ul>
        </li>
        <li><a href="#" class="MenuBarItemSubmenu">Usuarios</a>
            <ul>
                <li><a href="agrega_usuario.php">Agregar Usuario</a></li>
                <li><a href="admin_usuarios.php">Administrar Usuarios</a></li>
            </ul>
        </li>
		<li><a href="#" class="MenuBarItemSubmenu">Sitio Web</a>
			<ul>
                <li><a href="publica_banners.php">Banners Redes Sociales</a></li>
                <li><a href="publica_banners_web.php">Banners Sitio Web</a></li>
				<li><a href="publica_noticia_web.php">Publicar Sitio Web</a></li>
            </ul>
		</li>
        <li><a href="<?php echo $logoutAction ?>" target="_parent">Salir</a></li>
    </ul>
</div>