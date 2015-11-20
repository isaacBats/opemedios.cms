<html>
<head>
<script type="text/javascript" src="js/swfobject.js"></script>
<script type="text/javascript" src="js/json/json2.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<style type="text/css">
    #resize { width: 500px; height: 300px; background:#D7FFD7; padding:8px}
	#resize2 { width: 500px; height: 300px; background:#D7FFD7; padding:8px}
</style>
  
<script type="text/javascript">
swfobject.embedSWF(
  "open-flash-chart.swf", "my_chart", "100%", "100%",
  "9.0.0", "expressInstall.swf",
  {"data-file":"data.php?id=2", "id":"chart_1"} );
</script>
<script type="text/javascript">
swfobject.embedSWF(
  "open-flash-chart.swf", "my_chart2", "100%", "100%",
  "9.0.0", "expressInstall.swf",
  {"data-file":"data.php?id=1", "id":"chart_2"} );
</script>

<script>
  $(document).ready(function() {
    $("#resize").resizable();
	$("#resize2").resizable();
  });
  
</script>

<script type="text/javascript">

OFC = {};

OFC.jquery = {
    name: "jQuery",
    version: function(src) { return $('#'+ src)[0].get_version() },
    rasterize: function (src, dst) { $('#'+ dst).replaceWith(OFC.jquery.image(src)) },
    image: function(src) { return "<img src='data:image/png;base64," + $('#'+src)[0].get_img_binary() + "' />"},
    popup: function(src) {
        var img_win = window.open('', 'Charts: Export as Image')
        with(img_win.document) {
            write('<html><head><title>Charts: Export as Image<\/title><\/head><body>' + OFC.jquery.image(src) + '<\/body><\/html>') }
		// stop the 'loading...' message
		img_win.document.close();
     }
}

// Using an object as namespaces is JS Best Practice. I like the Control.XXX style.
//if (!Control) {var Control = {}}
//if (typeof(Control == "undefined")) {var Control = {}}
if (typeof(Control == "undefined")) {var Control = {OFC: OFC.jquery}}


// By default, right-clicking on OFC and choosing "save image locally" calls this function.
// You are free to change the code in OFC and call my wrapper (Control.OFC.your_favorite_save_method)
// function save_image() { alert(1); Control.OFC.popup('my_chart') }
function save_image() { alert(1); OFC.jquery.popup('my_chart') }
function moo() { alert(99); };
</script>



</head>
<body>
<div id="resize">
	<div id="my_chart"></div>
</div><br>
<INPUT TYPE=BUTTON OnClick="OFC.jquery.popup('my_chart')" VALUE="Open in other window">
<br>
<div id="resize2">
	<div id="my_chart2"></div>
</div>
<br>
<INPUT TYPE=BUTTON OnClick="OFC.jquery.popup('my_chart2')" VALUE="Open in other window">
<br>
</body>
</html>