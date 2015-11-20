<?php
include("phpdelegates/thumbnailer.php");

// demo
$thumb = new thumbnail("data/fuentes/ID14_8.jpg","data/thumbs",370,285,70);
echo '<img src=\''.$thumb.'\' alt=\'Hacer clic para Agrandar\' title=\'Noticia\'/>';

    ?>