<?php
/* 
 * muestra en pantalla la informacion de los registros de una consulta y de paginacion
 */
?>
<div align="center" class="label2">
    <table border="0">
        <tr>
            <td><?php if ($pageNum > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum=%d%s", $currentPage, 0, $queryString); ?>">Primero</a>
            <?php } // Show if not first page ?>                  </td>
            <td><?php if ($pageNum > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum=%d%s", $currentPage, max(0, $pageNum - 1), $queryString); ?>">Anterior</a>
            <?php } // Show if not first page ?>                  </td>
            <td><?php if ($pageNum < $totalPages) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum=%d%s", $currentPage, min($totalPages, $pageNum + 1), $queryString); ?>">Siguiente</a>
            <?php } // Show if not last page ?>                  </td>
            <td><?php if ($pageNum < $totalPages) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum=%d%s", $currentPage, $totalPages, $queryString); ?>">Ultimo</a>
            <?php } // Show if not last page ?>                  </td>
        </tr>
    </table>
    Registros <?php echo ($startRow + 1) ?> - <?php echo min($startRow + $maxRows, $totalRows) ?> de <?php echo $totalRows ?>
</div>

