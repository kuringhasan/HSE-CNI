<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<!-- Langkah 1 -->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyBHJxzPHD_egYnhxntqcvfem35YRjruzAg&callback=initMap""></script>
<script type="text/javascript">
// Langkah 4
var marker;
function initialize() {
	var latlng = new google.maps.LatLng(-7.176175, 107.571148);
	var myOptions = {
	zoom: 15,
	center: latlng,
	 mapTypeId:google.maps.MapTypeId.ROADMAP
	};
	// Langkah 3
	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	 map.setMapTypeId('satellite');
	 addMarker(-7.176404, 107.571830,'KPBS Pangalengan',map);
	  <?php
            //$query = mysql_query("select * from tbl_lokasi");
            while ($data = current($data_coord)) {
            $lat = $data['lat'];
            $lon = $data['lng'];
            $nama = $data['name'];?>
            addMarker(<?php echo $lat?>, <?php echo $lon?>, '<?php echo $nama?>',map); 
			<?php  
			next($data_coord);                     
           }
          ?>
}
 var infoWindow = new google.maps.InfoWindow;      
	var bounds = new google.maps.LatLngBounds();


	function bindInfoWindow(marker, map, infoWindow, html) {
	
	  google.maps.event.addListener(marker, 'click', function() {
		infoWindow.setContent(html);
		infoWindow.open(map, marker);
	  });
}
 function addMarker(lat, lng, info,peta) {
	 
	var pt = new google.maps.LatLng(lat, lng);
	bounds.extend(pt);
	var marker = new google.maps.Marker({
		map: peta,
		label:info,
		//title: info,
		//animation: google.maps.Animation.BOUNCE,
		position: pt
	});       
	peta.fitBounds(bounds);
	bindInfoWindow(marker, peta, infoWindow, info);
  }

/*
 var marker;
      function initialize() {
		  var latlng = new google.maps.LatLng(-7.176175, 107.571148);
        var mapCanvas = document.getElementById('map_canvass');
        var mapOptions = {
			zoom: 15,
			center: latlng,
           mapTypeId: google.maps.MapTypeId.ROADMAP//'satellite'
        };     
        var map = new google.maps.Map(mapCanvas, mapOptions);
        var infoWindow = new google.maps.InfoWindow;      
        var bounds = new google.maps.LatLngBounds();
 
 
        function bindInfoWindow(marker, map, infoWindow, html) {
          google.maps.event.addListener(marker, 'click', function() {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
          });
        }
 
          function addMarker(lat, lng, info) {
            var pt = new google.maps.LatLng(lat, lng);
            bounds.extend(pt);
            var marker = new google.maps.Marker({
                map: map,
                position: pt
            });       
            map.fitBounds(bounds);
            bindInfoWindow(marker, map, infoWindow, info);
          }
 
         
		  
        }*/
      //google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>
<!-- Langkah 5 -->
<body onLoad="initialize()">
<!-- Langkah 2 -->
<div id="map_canvas" style="width:1000px; height:700px"></div>
</body>
</html>

