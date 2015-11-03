//creo array de imágenes 
 array_imagen = new Array(3); 
 array_imagen[0] = new Image(680,90);
 array_imagen[0].src = "/Nota/banner/Banner1.png";
 array_imagen[1] = new Image(680,90);
 array_imagen[1].src = "/Nota/banner/Banner2.png";
 array_imagen[2] = new Image(680,90); 
 array_imagen[2].src = "/Nota/banner/Banner3.png";

 //creo el array de URLs 
 array_url = new Array(3);
 array_url[0] = "http://www.liverpool.com.mx/";
 array_url[1] = "http://www.videocine.com.mx/";
 array_url[2] = "http://www.mtv.com.mx/";

 //variable para llevar la cuenta de la imagen siguiente 
 contador = 0;

 //función para rotar el banner 
 function alternar_banner(){ 
     window.document["banner"].src = array_imagen[contador].src;
     window.document.links[1].href = array_url[contador];
     contador ++;
     contador = contador % array_imagen.length;
     setTimeout("alternar_banner()",4000);
 }