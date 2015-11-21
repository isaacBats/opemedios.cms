  <?php
  include("phpdelegates/db_array.php");
  include("phpdao/OpmDB.php");
  include("phpclasses/Archivo.php");
  include("phpclasses/Noticia.php");
  include("phpclasses/NoticiaElectronico.php");
  include("phpclasses/NoticiaExtra.php");
  include("phpclasses/Ubicacion.php");
  include("phpdelegates/thumbnailer.php");


  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
  {
      $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

      $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

      switch ($theType) {
          case "text":
              $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
              break;

          case "int":
              $theValue = ($theValue != "") ? intval($theValue) : "NULL";
              break;
          case "date":
              $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
              break;
          case "defined":
              $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
              break;

      } // end switch
      return $theValue;
  } // end function


  if(isset ($_POST['insertar']) && $_POST['insertar'] == true){
      //creamos el objeto de base de datos, introduciendo como parametro el arreglo que genera la funcion
      $base = new OpmDB(genera_arreglo_BD());
      //iniciamos conexion
      $base->init();

      $tipo = $_POST['id_tipo_fuente'];

      switch($tipo){
          case 1:
              // insertamos noticia en base de datos
              $datos_noticia = array("id_noticia"=>"",
                                     "encabezado"=>$_POST['encabezado'],
                                     "sintesis"=>$_POST['sintesis'],
                                     "autor"=>$_POST['autor'],
                                     "fecha"=>date("Y-m-d",mktime(0,0,0,$_POST['fecha_m'],$_POST['fecha_d'],$_POST['fecha_y'])),
                                     "comentario"=>$_POST['comentario'],
                                     "id_tipo_fuente"=>$_POST['id_tipo_fuente'],
                                     "id_fuente"=>$_POST['id_fuente'],
                                     "id_seccion"=>$_POST['id_seccion'],
                                     "id_sector"=>$_POST['id_sector'],
                                     "id_tipo_autor"=>$_POST['id_tipo_autor'],
                                     "id_genero"=>$_POST['id_genero'],
                                     "id_tendencia_monitorista"=>$_POST['id_tendencia_monitorista'],
                                     "id_usuario"=>$_POST['id_usuario'],
                                     "hora"=>date("H:i:s",mktime($_POST['hora_HH'],$_POST['hora_MM'],$_POST['hora_SS'],1,1,2000)),
                                     "duracion"=>date("H:i:s",mktime($_POST['duracion_HH'],$_POST['duracion_MM'],$_POST['duracion_SS'],1,1,2000)),
  								   "costo"=>$_POST['costo']);

              $noticia = new NoticiaElectronico($datos_noticia);
              $base->execute_query($noticia->SQL_NUEVA_NOTICIA());

              $id_registro = $base->get_row();
              $noticia->setId($id_registro[0]);

              // copiamos archivo a disco duro

              // se ve si el archivo va a ser principal o secundario

              if(isset($_POST['secundario']) && ($_POST['secundario'] == "yes"))
              {
                  $principal = 0;
              }
              else
              {
                  $principal = 1;
              }

              $url = "data/noticias/television";// directorio donde se copian los archivos de las noticias

              $nombre_archivo_nuevo = $_FILES['archivo']['name'];
              $tamano_archivo_nuevo = $_FILES['archivo']['size'];
              $tipo_archivo_nuevo = $_FILES['archivo']['type'];

              //se crea el directorio de tipo de archivo si no existe

              if(!is_dir($url))
              {
                  mkdir($url, 0777);
              }

              //Se comprueba tamaño de archivo y se coloca en su destino final

              if (!($tamano_archivo_nuevo < 536870912)) //  512 M
              {
                  $mensaje ="Error: El archivo excede el tamaño limite";
                  $error_upload = true;
              }
              else
              {
                  $error_upload= false;
  				$clave = rand();
                  if (move_uploaded_file($_FILES['archivo']['tmp_name'], $url."/ID".$noticia->getId()."_".$clave."_".$nombre_archivo_nuevo))
                  {
                      $mensaje = "exito";
                  }
                  else
                  {
                      $mensaje = "Error en la copia del archivo a establacer";
                      $error_upload = true;
                  }
              }
              // si si se sube bien, hay que establecer en la base
              if($mensaje == "exito")
              {
                  //se obtienen los datos del archivo para crear el objeto

                  $datos_archivo = array("id_adjunto"=>"",
                                         "nombre"=>$nombre_archivo_nuevo,
                                         "nombre_archivo"=>"ID".$noticia->getId()."_".$clave."_".$nombre_archivo_nuevo,
                                         "tipo"=>$tipo_archivo_nuevo,
                                         "carpeta"=>"",
                                         "principal"=>$principal,
                                         "id_noticia"=>$noticia->getId());

                  //y se crea el objeto archivo para la insercion a BD

                  $archivo = new Archivo($datos_archivo);
                  $base->execute_query($archivo->SQL_Insert_Archivo());

                  $principal = 0;

              }

              $base->close();
              header("Location:agrega_archivo.php?id_noticia=".$noticia->getId()."&principal=".$principal."&error_upload=".$error_upload.
                                                  "&mensaje=".$mensaje."&archivo=".$nombre_archivo_nuevo."&folder=television");
              exit ();


              break;

          case 2:

              // insertamos noticia en base de datos
              $datos_noticia = array("id_noticia"=>"",
                                     "encabezado"=>$_POST['encabezado'],
                                     "sintesis"=>$_POST['sintesis'],
                                     "autor"=>$_POST['autor'],
                                     "fecha"=>date("Y-m-d",mktime(0,0,0,$_POST['fecha_m'],$_POST['fecha_d'],$_POST['fecha_y'])),
                                     "comentario"=>$_POST['comentario'],
                                     "id_tipo_fuente"=>$_POST['id_tipo_fuente'],
                                     "id_fuente"=>$_POST['id_fuente'],
                                     "id_seccion"=>$_POST['id_seccion'],
                                     "id_sector"=>$_POST['id_sector'],
                                     "id_tipo_autor"=>$_POST['id_tipo_autor'],
                                     "id_genero"=>$_POST['id_genero'],
                                     "id_tendencia_monitorista"=>$_POST['id_tendencia_monitorista'],
                                     "id_usuario"=>$_POST['id_usuario'],
                                     "hora"=>date("H:i:s",mktime($_POST['hora_HH'],$_POST['hora_MM'],$_POST['hora_SS'],1,1,2000)),
                                     "duracion"=>date("H:i:s",mktime($_POST['duracion_HH'],$_POST['duracion_MM'],$_POST['duracion_SS'],1,1,2000)),
  								   "costo"=>$_POST['costo']);

              $noticia = new NoticiaElectronico($datos_noticia);
              $base->execute_query($noticia->SQL_NUEVA_NOTICIA());

              $id_registro = $base->get_row();
              $noticia->setId($id_registro[0]);

              // copiamos archivo a disco duro

              // se ve si el archivo va a ser principal o secundario

              if(isset($_POST['secundario']) && ($_POST['secundario'] == "yes"))
              {
                  $principal = 0;
              }
              else
              {
                  $principal = 1;
              }

              $url = "data/noticias/radio";// directorio donde se copian los archivos de las noticias

              $nombre_archivo_nuevo = $_FILES['archivo']['name'];
              $tamano_archivo_nuevo = $_FILES['archivo']['size'];
              $tipo_archivo_nuevo = $_FILES['archivo']['type'];

              //se crea el directorio de tipo de archivo si no existe

              if(!is_dir($url))
              {
                  mkdir($url, 0777);
              }

              //Se comprueba tamaño de archivo y se coloca en su destino final

              if (!($tamano_archivo_nuevo < 536870912)) //  512 M
              {
                  $mensaje ="Error: El archivo excede el tamaño limite";
                  $error_upload = true;
              }
              else
              {
                  $error_upload= false;
                  if (move_uploaded_file($_FILES['archivo']['tmp_name'], $url."/ID".$noticia->getId()."_".$nombre_archivo_nuevo))
                  {
                      $mensaje = "exito";
                  }
                  else
                  {
                      $mensaje = "Error en la copia del archivo a establacer";
                      $error_upload = true;
                  }
              }
              // si si se sube bien, hay que establecer en la base
              if($mensaje == "exito")
              {
                  //se obtienen los datos del archivo para crear el objeto

                  $datos_archivo = array("id_adjunto"=>"",
                                         "nombre"=>$nombre_archivo_nuevo,
                                         "nombre_archivo"=>"ID".$noticia->getId()."_".$nombre_archivo_nuevo,
                                         "tipo"=>$tipo_archivo_nuevo,
                                         "carpeta"=>"",
                                         "principal"=>$principal,
                                         "id_noticia"=>$noticia->getId());

                  //y se crea el objeto archivo para la insercion a BD

                  $archivo = new Archivo($datos_archivo);
                  $base->execute_query($archivo->SQL_Insert_Archivo());

                  $principal = 0;

              }

              $base->close();
              header("Location:agrega_archivo.php?id_noticia=".$noticia->getId()."&principal=".$principal."&error_upload=".$error_upload.
                                                  "&mensaje=".$mensaje."&archivo=".$nombre_archivo_nuevo."&folder=radio");
              exit ();

              break;

          case 3:

              // insertamos noticia de periodicos en base de datos 
              $datos_noticia = array("id_noticia"=>"",
                                     "encabezado"=>$_POST['encabezado'],
                                     "sintesis"=>$_POST['sintesis'],
                                     "autor"=>$_POST['autor'],
                                     "fecha"=>date("Y-m-d",mktime(0,0,0,$_POST['fecha_m'],$_POST['fecha_d'],$_POST['fecha_y'])),
                                     "comentario"=>$_POST['comentario'],
                                     "id_tipo_fuente"=>$_POST['id_tipo_fuente'],
                                     "id_fuente"=>$_POST['id_fuente'],
                                     "id_seccion"=>$_POST['id_seccion'],
                                     "id_sector"=>$_POST['id_sector'],
                                     "id_tipo_autor"=>$_POST['id_tipo_autor'],
                                     "id_genero"=>$_POST['id_genero'],
                                     "id_tendencia_monitorista"=>$_POST['id_tendencia_monitorista'],
                                     "id_usuario"=>$_POST['id_usuario'],
                                     "pagina"=>$_POST['pagina'],
                                     "id_tipo_pagina"=>$_POST['id_tipo_pagina'],
                                     "porcentaje_pagina"=>$_POST['porcentaje_pagina'],
  								   "costo"=>$_POST['costo']);

              $noticia = new NoticiaExtra($datos_noticia,$tipo);
              $base->execute_query($noticia->SQL_NUEVA_NOTICIA());

              $id_registro = $base->get_row();
              $noticia->setId($id_registro[0]);

              //Insertamos la ubicacion del archivo
              $datos_ubicacion = array("id_noticia"=>$noticia->getId(),
                                       "uno"=>GetSQLValueString(isset($_POST['checkbox1']) ? "true" : "", "defined","1","0"),
                                       "dos"=>GetSQLValueString(isset($_POST['checkbox2']) ? "true" : "", "defined","1","0"),
                                       "tres"=>GetSQLValueString(isset($_POST['checkbox3']) ? "true" : "", "defined","1","0"),
                                       "cuatro"=>GetSQLValueString(isset($_POST['checkbox4']) ? "true" : "", "defined","1","0"),
                                       "cinco"=>GetSQLValueString(isset($_POST['checkbox5']) ? "true" : "", "defined","1","0"),
                                       "seis"=>GetSQLValueString(isset($_POST['checkbox6']) ? "true" : "", "defined","1","0"),
                                       "siete"=>GetSQLValueString(isset($_POST['checkbox7']) ? "true" : "", "defined","1","0"),
                                       "ocho"=>GetSQLValueString(isset($_POST['checkbox8']) ? "true" : "", "defined","1","0"),
                                       "nueve"=>GetSQLValueString(isset($_POST['checkbox9']) ? "true" : "", "defined","1","0"),
                                       "diez"=>GetSQLValueString(isset($_POST['checkbox10']) ? "true" : "", "defined","1","0"),
                                       "once"=>GetSQLValueString(isset($_POST['checkbox11']) ? "true" : "", "defined","1","0"),
                                       "doce"=>GetSQLValueString(isset($_POST['checkbox12']) ? "true" : "", "defined","1","0"));

              $ubicacion = new Ubicacion($datos_ubicacion);
              $base->execute_query($ubicacion->SQL_Insert_Ubicacion());

              // copiamos archivo a disco duro

              // se ve si el archivo va a ser principal o secundario

              if(isset($_POST['secundario']) && ($_POST['secundario'] == "yes"))
              {
                  $principal = 0;
              }
              else
              {
                  $principal = 1;
              }

              $url = "data/noticias/periodico";// directorio donde se copian los archivos de las noticias

              $nombre_archivo_nuevo = $_FILES['archivo_noticia']['name'];
              $tamano_archivo_nuevo = $_FILES['archivo_noticia']['size'];
              $tipo_archivo_nuevo = $_FILES['archivo_noticia']['type'];

              //se crea el directorio de tipo de archivo si no existe

              if(!is_dir($url))
              {
                  mkdir($url, 0777);
              }

              //Se comprueba tamaño de archivo y se coloca en su destino final

              if (!($tamano_archivo_nuevo < 536870912)) //  512 M
              {
                  $mensaje ="Error: El archivo de noticia excede el tamaño limite";
                  $error_upload = true;
              }
              else
              {
                  $clave = rand();
  				        $error_upload= false;
  				$path =  $url."/ID".$clave."_".strtolower(str_replace(" ","_",$nombre_archivo_nuevo));
  				if (move_uploaded_file($_FILES['archivo_noticia']['tmp_name'],$path))
  				{
  					$mensaje = "exito";
  					$thumbnail = new thumbnail($path,$url."/thumbs",250,350,90,"_tn.");
  				}
                  else
                  {
                      $mensaje = "Error en la copia del archivo a establacer";
                      $error_upload = true;
                  }
              }
              // si si se sube bien, hay que establecer en la base
              if($mensaje == "exito")
              {
                  //se obtienen los datos del archivo para crear el objeto

                  $datos_archivo = array("id_adjunto"=>"",
                                         "nombre"=>$nombre_archivo_nuevo,
                                         "nombre_archivo"=>"ID".$clave."_".strtolower(str_replace(" ","_",$nombre_archivo_nuevo)),
                                         "tipo"=>$tipo_archivo_nuevo,
                                         "carpeta"=>"",
                                         "principal"=>$principal,
                                         "id_noticia"=>$noticia->getId());

                  //y se crea el objeto archivo para la insercion a BD

                  $archivo = new Archivo($datos_archivo);
                  $base->execute_query($archivo->SQL_Insert_Archivo());

                  $principal = 0;

              }

              // ahora se maneja el archivo de la pagina donde se publico la noticia

              $nombre_archivo_pagina = $_FILES['archivo_pagina']['name'];
              $tamano_archivo_pagina = $_FILES['archivo_pagina']['size'];
              $tipo_archivo_pagina = $_FILES['archivo_pagina']['type'];



              if (!($tamano_archivo_nuevo < 536870912)) //  512 M
              {
                  $mensaje_pagina ="Error: El archivo de noticia excede el tamaño limite";
                  $error_upload_pagina = true;
              }
              else
              {
                  $error_upload_pagina= false;
                  if (move_uploaded_file($_FILES['archivo_pagina']['tmp_name'], $url."/ID".$noticia->getId()."_".$nombre_archivo_pagina))
                  {
                      $mensaje_pagina = "exito";
                  }
                  else
                  {
                      $mensaje_pagina = "Error en la copia del archivo de la pagina";
                      $error_upload_pagina = true;
                  }
              }
              // si si se sube bien, hay que establecer en la base
              if($mensaje_pagina == "exito")
              {
                  //se obtienen los datos del archivo para crear el objeto

                  $datos_archivo_pagina = array("id_adjunto"=>"",
                                         "nombre"=>$nombre_archivo_pagina,
                                         "nombre_archivo"=>"ID".$noticia->getId()."_".$nombre_archivo_pagina,
                                         "tipo"=>$tipo_archivo_pagina,
                                         "carpeta"=>"",
                                         "principal"=>2, // Principal = 2 significa que el archivo es la pagina donde se encuentra la noticia
                                         "id_noticia"=>$noticia->getId());

                  //y se crea el objeto archivo para la insercion a BD

                  $archivo_pagina = new Archivo($datos_archivo_pagina);
                  $base->execute_query($archivo_pagina->SQL_Insert_Archivo());

              }


              //finalizamos
              $base->close();
              header("Location:agrega_archivo.php?id_noticia=".$noticia->getId()."&principal=".$principal."&error_upload=".$error_upload.
                                                  "&mensaje=".$mensaje."&archivo=".$nombre_archivo_nuevo."&folder=periodico");
              exit ();

              break;

          case 4:

              // insertamos noticia de revista en base de datos
              $datos_noticia = array("id_noticia"=>"",
                                     "encabezado"=>$_POST['encabezado'],
                                     "sintesis"=>$_POST['sintesis'],
                                     "autor"=>$_POST['autor'],
                                     "fecha"=>date("Y-m-d",mktime(0,0,0,$_POST['fecha_m'],$_POST['fecha_d'],$_POST['fecha_y'])),
                                     "comentario"=>$_POST['comentario'],
                                     "id_tipo_fuente"=>$_POST['id_tipo_fuente'],
                                     "id_fuente"=>$_POST['id_fuente'],
                                     "id_seccion"=>$_POST['id_seccion'],
                                     "id_sector"=>$_POST['id_sector'],
                                     "id_tipo_autor"=>$_POST['id_tipo_autor'],
                                     "id_genero"=>$_POST['id_genero'],
                                     "id_tendencia_monitorista"=>$_POST['id_tendencia_monitorista'],
                                     "id_usuario"=>$_POST['id_usuario'],
                                     "pagina"=>$_POST['pagina'],
                                     "id_tipo_pagina"=>$_POST['id_tipo_pagina'],
                                     "porcentaje_pagina"=>$_POST['porcentaje_pagina'],
  								   "costo"=>$_POST['costo']
  								   );

              $noticia = new NoticiaExtra($datos_noticia,$tipo);
              $base->execute_query($noticia->SQL_NUEVA_NOTICIA());

              $id_registro = $base->get_row();
              $noticia->setId($id_registro[0]);

              //Insertamos la ubicacion del archivo
              $datos_ubicacion = array("id_noticia"=>$noticia->getId(),
                                       "uno"=>GetSQLValueString(isset($_POST['checkbox1']) ? "true" : "", "defined","1","0"),
                                       "dos"=>GetSQLValueString(isset($_POST['checkbox2']) ? "true" : "", "defined","1","0"),
                                       "tres"=>GetSQLValueString(isset($_POST['checkbox3']) ? "true" : "", "defined","1","0"),
                                       "cuatro"=>GetSQLValueString(isset($_POST['checkbox4']) ? "true" : "", "defined","1","0"),
                                       "cinco"=>GetSQLValueString(isset($_POST['checkbox5']) ? "true" : "", "defined","1","0"),
                                       "seis"=>GetSQLValueString(isset($_POST['checkbox6']) ? "true" : "", "defined","1","0"),
                                       "siete"=>GetSQLValueString(isset($_POST['checkbox7']) ? "true" : "", "defined","1","0"),
                                       "ocho"=>GetSQLValueString(isset($_POST['checkbox8']) ? "true" : "", "defined","1","0"),
                                       "nueve"=>GetSQLValueString(isset($_POST['checkbox9']) ? "true" : "", "defined","1","0"),
                                       "diez"=>GetSQLValueString(isset($_POST['checkbox10']) ? "true" : "", "defined","1","0"),
                                       "once"=>GetSQLValueString(isset($_POST['checkbox11']) ? "true" : "", "defined","1","0"),
                                       "doce"=>GetSQLValueString(isset($_POST['checkbox12']) ? "true" : "", "defined","1","0"));

              $ubicacion = new Ubicacion($datos_ubicacion);
              $base->execute_query($ubicacion->SQL_Insert_Ubicacion());

              // copiamos archivo a disco duro

              // se ve si el archivo va a ser principal o secundario

              if(isset($_POST['secundario']) && ($_POST['secundario'] == "yes"))
              {
                  $principal = 0;
              }
              else
              {
                  $principal = 1;
              }

              $url = "data/noticias/revista";// directorio donde se copian los archivos de las noticias

              $nombre_archivo_nuevo = $_FILES['archivo_noticia']['name'];
              $tamano_archivo_nuevo = $_FILES['archivo_noticia']['size'];
              $tipo_archivo_nuevo = $_FILES['archivo_noticia']['type'];

              //se crea el directorio de tipo de archivo si no existe

              if(!is_dir($url))
              {
                  mkdir($url, 0777);
              }

              //Se comprueba tamaño de archivo y se coloca en su destino final

              if (!($tamano_archivo_nuevo < 536870912)) //  512 M
              {
                  $mensaje ="Error: El archivo excede el tamaño limite";
                  $error_upload = true;
              }
              else
              {
                $clave = rand();
  				      $error_upload= false;
  				      $path =  $url."/ID".$clave."_".strtolower(str_replace(" ","_",$nombre_archivo_nuevo));
  				        if (move_uploaded_file($_FILES['archivo_noticia']['tmp_name'],$path)){
  					       $mensaje = "exito";
  					       $thumbnail = new thumbnail($path,$url."/thumbs",250,350,90,"_tn.");
  				        }
                  else
                  {
                      $mensaje = "Error en la copia del archivo a establecer";
                      $error_upload = true;
                  }
              }
              // si si se sube bien, hay que establecer en la base
              if($mensaje == "exito")
              {
                  //se obtienen los datos del archivo para crear el objeto

                  $datos_archivo = array("id_adjunto"=>"",
                                         "nombre"=>$nombre_archivo_nuevo,
                                         "nombre_archivo"=>"ID".$clave."_".strtolower(str_replace(" ","_",$nombre_archivo_nuevo)),
                                         "tipo"=>$tipo_archivo_nuevo,
                                         "carpeta"=>"",
                                         "principal"=>$principal,
                                         "id_noticia"=>$noticia->getId());

                  //y se crea el objeto archivo para la insercion a BD

                  $archivo = new Archivo($datos_archivo);
                  $base->execute_query($archivo->SQL_Insert_Archivo());

                  $principal = 0;

              }

              // ahora se maneja el archivo de la pagina donde se publico la noticia

              $nombre_archivo_pagina = $_FILES['archivo_pagina']['name'];
              $tamano_archivo_pagina = $_FILES['archivo_pagina']['size'];
              $tipo_archivo_pagina = $_FILES['archivo_pagina']['type'];



              if (!($tamano_archivo_nuevo < 536870912)) //  512 M
              {
                  $mensaje_pagina ="Error: El archivo de noticia excede el tamaño limite";
                  $error_upload_pagina = true;
              }
              else
              {
                  $error_upload_pagina= false;
                  if (move_uploaded_file($_FILES['archivo_pagina']['tmp_name'], $url."/ID".$noticia->getId()."_".$nombre_archivo_pagina))
                  {
                      $mensaje_pagina = "exito";
                  }
                  else
                  {
                      $mensaje_pagina = "Error en la copia del archivo de la pagina";
                      $error_upload_pagina = true;
                  }
              }
              // si si se sube bien, hay que establecer en la base
              if($mensaje_pagina == "exito")
              {
                  //se obtienen los datos del archivo para crear el objeto

                  $datos_archivo_pagina = array("id_adjunto"=>"",
                                         "nombre"=>$nombre_archivo_pagina,
                                         "nombre_archivo"=>"ID".$noticia->getId()."_".$nombre_archivo_pagina,
                                         "tipo"=>$tipo_archivo_pagina,
                                         "carpeta"=>"",
                                         "principal"=>2, // Principal = 2 significa que el archivo es la pagina donde se encuentra la noticia
                                         "id_noticia"=>$noticia->getId());

                  //y se crea el objeto archivo para la insercion a BD

                  $archivo_pagina = new Archivo($datos_archivo_pagina);
                  $base->execute_query($archivo_pagina->SQL_Insert_Archivo());

              }


              //finalizamos
              $base->close();
              header("Location:agrega_archivo.php?id_noticia=".$noticia->getId()."&principal=".$principal."&error_upload=".$error_upload.
                                                  "&mensaje=".$mensaje."&archivo=".$nombre_archivo_nuevo."&folder=revista");
              exit ();

              break;

          case 5:

              // insertamos noticia en base de datos para Internet
              $datos_noticia = array("id_noticia"=>"",
                                     "encabezado"=>$_POST['encabezado'],
                                     "sintesis"=>$_POST['sintesis'],
                                     "autor"=>$_POST['autor'],
                                     "fecha"=>date("Y-m-d",mktime(0,0,0,$_POST['fecha_m'],$_POST['fecha_d'],$_POST['fecha_y'])),
  								   "hora_publicacion"=>date("H:i:s",mktime($_POST['hora_HH'],$_POST['hora_MM'],$_POST['hora_SS'],1,1,2000)),
                                     "comentario"=>$_POST['comentarios'],
                                     "id_tipo_fuente"=>$_POST['id_tipo_fuente'],
                                     "id_fuente"=>$_POST['id_fuente'],
                                     "id_seccion"=>$_POST['id_seccion'],
                                     "id_sector"=>$_POST['id_sector'],
                                     "id_tipo_autor"=>$_POST['id_tipo_autor'],
                                     "id_genero"=>$_POST['id_genero'],
                                     "id_tendencia_monitorista"=>$_POST['id_tendencia_monitorista'],
                                     "id_usuario"=>$_POST['id_usuario'],
                                     "url"=>$_POST['url'],
  								   "costo"=>$_POST['costo']);
              $noticia = new NoticiaExtra($datos_noticia,$tipo);
  			      $SQL = $noticia->SQL_NUEVA_NOTICIA();
              $base->execute_query($noticia->SQL_NUEVA_NOTICIA());

              $id_registro = $base->get_row();
              $noticia->setId($id_registro[0]);

              // copiamos archivo a disco duro

              // se ve si el archivo va a ser principal o secundario

              if(isset($_POST['secundario']) && ($_POST['secundario'] == "yes"))
              {
                  $principal = 0;
              }
              else
              {
                  $principal = 1;
              }

              $url = "data/noticias/internet";// directorio donde se copian los archivos de las noticias

              $nombre_archivo_nuevo = $_FILES['archivo']['name'];
              $tamano_archivo_nuevo = $_FILES['archivo']['size'];
              $tipo_archivo_nuevo = $_FILES['archivo']['type'];

              //se crea el directorio de tipo de archivo si no existe

              if(!is_dir($url))
              {
                  mkdir($url, 0777);
              }

              //Se comprueba tamaño de archivo y se coloca en su destino final

              if (!($tamano_archivo_nuevo < 536870912)) //  512 Mb
              {
                  $mensaje ="Error: El archivo excede el tamaño limite";
                  $error_upload = true;
              }
              else
              {
                  $error_upload= false;
                  if (move_uploaded_file($_FILES['archivo']['tmp_name'], $url."/ID".$noticia->getId()."_".$nombre_archivo_nuevo))
                  {
                      $mensaje = "exito";
                  }
                  else
                  {
                      $mensaje = "Error en la copia del archivo a establacer";
                      $error_upload = true;
                  }
              }
              // si si se sube bien, hay que establecer en la base
              if($mensaje == "exito")
              {
                  //se obtienen los datos del archivo para crear el objeto

                  $datos_archivo = array("id_adjunto"=>"",
                                         "nombre"=>$nombre_archivo_nuevo,
                                         "nombre_archivo"=>"ID".$noticia->getId()."_".$nombre_archivo_nuevo,
                                         "tipo"=>$tipo_archivo_nuevo,
                                         "carpeta"=>"",
                                         "principal"=>$principal,
                                         "id_noticia"=>$noticia->getId());

                  //y se crea el objeto archivo para la insercion a BD

                  $archivo = new Archivo($datos_archivo);
                  $base->execute_query($archivo->SQL_Insert_Archivo());

                  $principal = 0;

              }

              $base->close();
              header("Location:agrega_archivo.php?id_noticia=".$noticia->getId()."&principal=".$principal."&error_upload=".$error_upload.
                                                  "&mensaje=".$mensaje."SQL=".$SQL."Costo=".$_POST['costo']."&archivo=".$nombre_archivo_nuevo."&folder=internet");
              exit ();

              break;
     }

}
  else
  {
      echo "Error. No se detecta insercion desde formulario";
  }

  ?>
