<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>
<!-- ChartJS -->
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/chart.js/Chart.js"></script>

<script>
//var $j = jQuery.noConflict();

   $(function () {
		
	
	 //-------------
    //- LINE CHART -
    //--------------
	 var areaChartOptions = {
      //Boolean - If we should show the scale at all
      showScale               : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines      : false,
      //String - Colour of the grid lines
      scaleGridLineColor      : 'rgba(0,0,0,.05)',
      //Number - Width of the grid lines
      scaleGridLineWidth      : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines  : true,
      //Boolean - Whether the line is curved between points
      bezierCurve             : true,
      //Number - Tension of the bezier curve between points
      bezierCurveTension      : 0.3,
      //Boolean - Whether to show a dot for each point
      pointDot                : false,
      //Number - Radius of each point dot in pixels
      pointDotRadius          : 4,
      //Number - Pixel width of point dot stroke
      pointDotStrokeWidth     : 1,
      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius : 20,
      //Boolean - Whether to show a stroke for datasets
      datasetStroke           : true,
      //Number - Pixel width of dataset stroke
      datasetStrokeWidth      : 2,
      //Boolean - Whether to fill the dataset with a color
      datasetFill             : true,
      //String - A legend template
      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio     : true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive              : true
    }
	 var areaChartData = <?php echo $line_chart;?>/*{
      labels  : ['September', 'Oktober', 'Nopember', 'Desember', 'Januari', 'Februari', 'Maret', 'April'],
      datasets: [
        {
          label               : 'Kota Cirebon',
          fillColor           : 'rgba(210, 214, 222, 1)',
          strokeColor         : '#0CF',//'rgba(210, 214, 222, 1)',
          pointColor          : '#0CF',//'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#0CF',//'#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [65, 59, 80, 81, 56, 55, 40,0]
        },
        {
          label               : 'Kab. Cirebon',
          fillColor           : 'rgba(60,141,188,0.9)',
          strokeColor         : '#F93',//'rgba(60,141,188,0.8)',
          pointColor          : '#F93',//'#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [28, 48, 40, 19, 86, 27, 90,0]
        },
        {
          label               : 'Kab. Indramayu',
          fillColor           : 'rgba(60,141,188,0.9)',
          strokeColor         : '#0C6',//'rgba(60,141,188,0.8)',
          pointColor          : '#0C6',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [0, 0, 25, 19, 86, 27, 0,0]
        }
      ]
    }*/
    var lineChartCanvas          = $('#lineChart').get(0).getContext('2d')
    var lineChart                = new Chart(lineChartCanvas)
    var lineChartOptions         = areaChartOptions
    lineChartOptions.datasetFill = false
    lineChart.Bar(areaChartData, lineChartOptions)
   });
</script> 
 <div class="row">
 <?php
// echo "<pre>";print_r($statistik);echo "</pre>";
 ?>
<div class="col-lg-3 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-blue">
    <div class="inner">
      <h3><?php echo number_format($rekap->jml_populsi,0,",",".");?></h3>
      <h4><strong>Jumlah Populasi</strong></h4>
    </div>
    <div class="icon">
      <i class="fa fa-sapi" style="font-size:75%;"></i>
    </div>
    <a href="<?php echo $url_populasi;?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div><!-- ./col -->

<div class="col-lg-3 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-aqua">
    <div class="inner">
      <h3><?php echo number_format($rekap_populasi->jml_induk,0,",",".");?></h3>
      <h4><strong>Jumlah Induk</strong></h4>
    </div>
    <div class="icon">
      <i class="fa fa-sapi" style="font-size:75%;"></i>
    </div>
    <a href="<?php echo $url_populasi;?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div><!-- ./col -->

<div class="col-lg-3 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-green">
    <div class="inner">
      <h3><?php echo number_format($rekap_populasi->jml_dara,0,",",".");?></h3>
      <h4><strong>Jumlah Dara</strong></h4>
    </div>
    <div class="icon">
      <i class="fa fa-sapi" style="font-size:75%;"></i>
    </div>
    <a href="<?php echo $url_populasi;?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div><!-- ./col -->
   
<div class="col-lg-3 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-fuchsia">
    <div class="inner">
      <h3><?php echo number_format($rekap_populasi->jml_bm,0,",",".");?></h3>
      <h4><strong>Betina Muda</strong></h4>
    </div>
    <div class="icon">
      <i class="fa fa-sapi" style="font-size:75%;"></i>
    </div>
    <a href="<?php echo $url_populasi;?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div><!-- ./col -->   
<div class="col-lg-3 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-yellow">
    <div class="inner">
      <h3><?php echo number_format($rekap_populasi->jml_pedet_btn,0,",",".");?></h3>
      <h4><strong>Pedet Betina</strong></h4>
    </div>
    <div class="icon">
      <i class="fa fa-sapi" style="font-size:75%;"></i>
    </div>
    <a href="<?php echo $url_populasi;?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div><!-- ./col -->   
    
<div class="col-lg-3 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-red">
    <div class="inner">
      <h3><?php echo number_format($rekap_populasi->jml_pedet_jtn,0,",",".");?></h3>
      <h4><strong>Pedet Jantan</strong></h4>
    </div>
    <div class="icon">
      <i class="fa fa-sapi" style="font-size:75%;"></i>
    </div>
    <a href="<?php echo $url_populasi;?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div><!-- ./col -->   
    
<div class="col-lg-3 col-xs-6">
  <!-- small box -->
  <div class="small-box bg-gray">
    <div class="inner">
      <h3><?php echo number_format($rekap->jml_unverified,0,",",".");?></h3>
       <h4><strong>Belum Verifikasi</strong></h4>
    </div>
    <div class="icon">
      <i class="fa fa-check-square-o"></i>
    </div>
    <a href="<?php echo $url_unverified;?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div><!-- ./col -->

    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-lime">
        <div class="inner">
          <h3><?php echo number_format($rekap_anggota->jml,0,",",".");?></h3>
         <h4><strong>Jumlah Anggota</strong></h4>
          
        </div>
        <div class="icon">
          <i class="fa fa-user"></i>
        </div>
        <a href="<?php echo $url_inactive;?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div><!-- ./col -->

</div><!-- /.row -->

      <!-- /.row -->
<div class="row">
   
  
     <div class="col-lg-8 col-xs-6">
     <!-- DONUT CHART -->
          <!-- LINE CHART -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Pertambahan Sapi Per Bulan</h3>

              <div class="box-tools pull-right">
               <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="lineChart" style="height:250px"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
     </div>
     <!-- ./col -->
    
</div><!-- /.row -->
  