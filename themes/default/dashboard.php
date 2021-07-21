<script type="text/javascript" src="<?php echo url::base();?>plugins/fusionchart/charts/fusioncharts.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>plugins/fusionchart/charts/themes/fusioncharts.theme.fint.js"></script>
<script src="http://maps.googleapis.com/maps/api/js"></script>
<script>
	$(function() {
		$( "#tabs" ).tabs();
	});
</script>

<script>
function initialize() {
  var mapProp = {
    center:new google.maps.LatLng(-7.332203,108.225072),
    zoom:13,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
  

      // Change this depending on the name of your PHP file
      downloadUrl("phpsqlajax_genxml.php", function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
          var name = markers[i].getAttribute("name");
          var address = markers[i].getAttribute("address");
          var type = markers[i].getAttribute("type");
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
          var html = "<b>" + name + "</b> <br/>" + address;
          var icon = customIcons[type] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon
          });
          bindInfoWindow(marker, map, infoWindow, html);
        }
      });
}
google.maps.event.addDomListener(window, 'load', initialize);

</script>


<script type="text/javascript">
  FusionCharts.ready(function(){
	var revenueChart = new FusionCharts({
        "type": "column3d",
        "renderAt": "ChartJumlahBarang",
        "width": "1300",
        "height": "700",
        "dataFormat": "json",
        "dataSource":  {
          "chart": {
            "caption": "Rekap Buku Inventaris",
            "subCaption": "Per SKPD",
            "xAxisName": "SKPD",
            "yAxisName": "Jumlah Barang",
            "theme": "ocean"
         },
         "data": <?php echo $json_jumlah;?>
      }

   });
	revenueChart.render();
    var revenueChart = new FusionCharts({
        "type": "column3d",
        "renderAt": "chartContainer",
        "width": "1300",
        "height": "700",
        "dataFormat": "json",
        "dataSource":  {
          "chart": {
            "caption": "Rekap Buku Inventaris",
            "subCaption": "Per SKPD",
            "xAxisName": "SKPD",
            "yAxisName": "Jumlah Harga Aset",
            "theme": "ocean"
         },
         "data": <?php echo $json;?>
      }

  });
  revenueChart.render();
   var revenueChart = new FusionCharts({
        "type": "pie3d",
        "renderAt": "chartGolonganJumlah",
        "width": "640",
        "height": "500",
        "dataFormat": "json",
        "dataSource":  {
          "chart": {
            "caption": "Rekap Jumlah Barang Inventaris",
            "subCaption": "Berdasarkan Golongan",
            "xAxisName": "SKPD",
            "yAxisName": "Jumlah Harga Aset",
            "theme": "ocean"
         },
         "data": <?php echo $json_gol_jumlah;?>
      }

  });
  revenueChart.render();
  var revenueChart = new FusionCharts({
        "type": "pie3d",
        "renderAt": "chartGolongan",
        "width": "640",
        "height": "500",
        "dataFormat": "json",
        "dataSource":  {
          "chart": {
            "caption": "Rekap Jumlah Harga Inventaris",
            "subCaption": "Berdasarkan Golongan",
            "xAxisName": "SKPD",
            "yAxisName": "Jumlah Harga Aset",
            "theme": "ocean"
         },
         "data": <?php echo $json_gol;?>
      }

  });
  revenueChart.render();
})
</script>

<div id="ChartJumlahBarang" style="width:99%; border:1px solid #999; margin:4px 4px 4px 4px;">FusionCharts XT will load here!</div>
<div id="chartContainer" style="width:99%; border:1px solid #999; margin:4px 4px 4px 4px;">FusionCharts XT will load here!</div>
<table width="100%" border="0">
<tr>
<td style="text-align:center;"><div id="chartGolonganJumlah" style="width:100%;  margin:4px 4px 4px 4px;">FusionCharts XT will load here!</div></td>
<td style="text-align:center;"><div id="chartGolongan" style="width:40%;  margin:4px 4px 4px 4px;">FusionCharts XT will load here!</div></td>
</tr></table>


			
