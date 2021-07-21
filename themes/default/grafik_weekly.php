<script src="<?php echo $theme_path;?>/plugins/chart.js/Chart.min.js"></script>
	<script src="<?php echo $theme_path;?>/plugins/chart.js/utils.js"></script>

<style>
	canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}
	</style>
    
<div class="row">
  <section class="col-lg-6 connectedSortable ui-sortable">
    <div class="box box-solid">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-th"></i>

              <h3 class="box-title">Trend Weekly Production #1</h3>

              <div class="box-tools pull-right">
              
                <button type="button" class="btn bg-teal btn-sm btn-refresh" title="Perbarui data anggota" onclick="if (confirm('Yakin akan memperbarui data anggota')==true){perbarui() };"><i class="fa fa-refresh"></i></button>
              </div>
            </div>
            <div class="box-body border-radius-none" style="">
              <canvas id="chart_weekly" ></canvas>
            </div>
           
          </div>
   </section>
   <section class="col-lg-6 connectedSortable ui-sortable">
    <div class="box box-solid">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-th"></i>

              <h3 class="box-title">Trend Weekly Production #2</h3>

              <div class="box-tools pull-right">
              
                <button type="button" class="btn bg-teal btn-sm btn-refresh" title="Perbarui data anggota" onclick="if (confirm('Yakin akan memperbarui data anggota')==true){perbarui() };"><i class="fa fa-refresh"></i></button>
              </div>
            </div>
            <div class="box-body border-radius-none" style="">
              <canvas id="chart_weekly2" ></canvas>
            </div>
            <!-- /.box-body -->
           
            <!-- /.box-footer -->
          </div>
   </section>
</div>

<script>
	var data = <?php echo $weekly_data;?>;/*{
    labels: ["HJS", "LCP", "PL","BKM"],
    datasets: [
			{
				label: "Plan",
				backgroundColor: "blue",
				data: [3,7,4,5]
			},
			{
				label: "Progress",
				backgroundColor: "red",
				data: [4,3,5,6]
			}
		]
	};*/
	var ctx = document.getElementById('chart_weekly').getContext('2d');;
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: data/*{
			labels: ['Red', 'Blue', 'Yellow', 'Green'],
			datasets: [{
				label: '# of Votes',
				data: [ 3, 5, 2, 3],
				backgroundColor: [
					'rgba(255, 99, 132, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(255, 206, 86, 0.2)',
					'rgba(75, 192, 192, 0.2)'
				],
				borderColor: [
					'rgba(255, 99, 132, 1)',
					'rgba(54, 162, 235, 1)',
					'rgba(255, 206, 86, 1)',
					'rgba(75, 192, 192, 1)'
				],
				borderWidth: 1
			}]
		}*/,
		options: {
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: 5000,
						max: 60000
					}
				}]
			}
		}
	});
	
	
	var ctx2 = document.getElementById('chart_weekly2').getContext('2d');;
	var myChart = new Chart(ctx2, {
		type: 'bar',
		data: <?php echo $weekly_data2;?>,
		options: {
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: 5000,
						max: 60000
						
					}
				}],
				xAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 10
					},
					afterFit: (scale) => {
						scale.height = 35;
					}
				}]
			}
		}
	});
	

</script>