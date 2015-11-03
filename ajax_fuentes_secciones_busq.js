/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 var http_request = false;

   function makeRequest_Cate2(url) {
      http_request = false;
      if (window.XMLHttpRequest) { // Mozilla, Safari,...
         http_request = new XMLHttpRequest();
         if (http_request.overrideMimeType) {
         	// set type accordingly to anticipated content type
            //http_request.overrideMimeType('text/xml');
            http_request.overrideMimeType('text/html');
         }
      } else if (window.ActiveXObject) { // IE
         try {
            http_request = new ActiveXObject("Msxml2.XMLHTTP");
         } catch (e) {
            try {
               http_request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}
         }
      }
      if (!http_request) {
         alert('No se pudo crear la instancia XMLHTTP');
         return false;
      }
      http_request.onreadystatechange = alertContentsCat2;
      http_request.open('GET', url, true);
      http_request.send(null);
   }

   function alertContentsCat2() {
      if (http_request.readyState == 4) {
         if (http_request.status == 200) {
            //alert(http_request.responseText);

            result = http_request.responseText;

			var update = new Array();
			if(result.indexOf('|') != -1) {
				update = result.split('|');
				changeTextCat2(update[0], update[1]);
			}
         } else {
            alert('Hubo un problema en ejecutar la petición.');
         }
      }
   }

	function sndReqCat2(fuente) {

		var thediv = document.getElementById('seccion'); // the div

		if(fuente == 0){
			thediv.innerHTML = 'Selecciona una Fuente';
			return false;
		}

		// switch div with a loading div
		thediv.innerHTML = '<span class="label2">Cargando...<img src="images/working.gif" /></span>';
		makeRequest_Cate2 ('ajax_fuentes_secciones_busq.php?f='+fuente);
	}

	function changeTextCat2( div2show, text) {
		// Detect Browser

		id = parseInt(div2show);
		var IE = (document.all) ? 1 : 0;
		var DOM = 0;
		if (parseInt(navigator.appVersion) >=5) {DOM=1};

		// Grab the content from the requested "div" and show it in the "container"
		if (DOM) {
			var viewer = document.getElementById('seccion');
			//alert(viewer.innerHTML );
			viewer.innerHTML = text;
		}  else if(IE) {
			document.all['seccion'].innerHTML = text;
		}
	}