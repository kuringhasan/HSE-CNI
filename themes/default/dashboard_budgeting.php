<script src="<?php echo $theme_path;?>/plugins/chart.js/Chart.min.js"></script>
	<script src="<?php echo $theme_path;?>/plugins/chart.js/utils.js"></script>

<style>
	canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}
.box-value{
		font-size:2.8em;
		font-weight:bold;
	}
.box-partner{
		margin-bottom:4px;
	}
@media screen and (max-width: 500px) {
	.box-value{
		font-size:1.8em;
	}
	.box-partner{
		font-size:0.9em;
		height:35px;
	}
}
@media screen and (max-width: 320px) {
	.box-value{
		font-size:1.7em;
	}
	.box-partner{
		font-size:0.8em;
		height:35px;
	}
}
</style>
 <div class="box box-solid">
    <div class="box-header ui-sortable-handle" style="cursor: move;">
      <i class="fa fa-th"></i>

      <h3 class="box-title">Report Budgeting</h3>

      <div class="box-tools pull-right">
      
        <button type="button" class="btn bg-teal btn-sm btn-refresh" title="Perbarui data anggota" onclick="if (confirm('Yakin akan memperbarui data anggota')==true){perbarui() };"><i class="fa fa-refresh"></i></button>
      </div>
    </div>
    <div class="box-body border-radius-none" style="">
            
            <section class="col-lg-6 col-xs-12" style="border:1px solid #CCF">
                <div class="col-lg-7" style="">
                 <canvas id="chart-budget"  style="min-height:auto;"></canvas>
                </div>
                 <div class="col-lg-5">
                  <!-- small box -->
                 <ul class="nav nav-pills nav-stacked">
                    <li><a href="#">Expenses Budget
                      <span class="pull-right text-red"> <?php echo $data_budget['budget'];?>%</span></a></li>
                    <li><a href="#">Actual Expenses <span class="pull-right text-green"></i> <?php echo $data_budget['actual'];?>%</span></a>
                    </li>
                    <li><a href="#">Deference (%)
                      <span class="pull-right text-yellow"> <?php echo $data_budget['selisih'];?>%</span></a></li>
                  </ul>
                </div>
                <div class="col-lg-15 text-center" style="">
                    <canvas id="chart_monthly"  style="min-height:auto;"></canvas>
                </div>
             </section>
             
              <section class="col-lg-6 col-xs-12" style="border:1px solid #CCF;">
                <div class="col-lg-8" style="padding-left:0px;padding-right:0px">
                 <canvas id="chart-budget-department"  style="min-height:auto;"></canvas>
                </div>
                 <div class="col-lg-4" >
                  <!-- small box -->
                  <style>
				  .list-department li a{
					 
					  padding-top:2px;
					  margin-top:2px;
					  padding-bottom:2px;
					  margin-bottom:2px;
				  }
				  </style>
                 <ul class="nav nav-pills nav-stacked list-department">
                    <li style="padding-top:0px;margin-top:0px;"><a href="#">Dep A
                      <span class="pull-right text-red" > 12%</span></a></li>
                    <li><a href="#" >Dep B <span class="pull-right text-green"></i> 4%</span></a></li>
                     <li><a href="#">Dep C <span class="pull-right text-green"></i> 4%</span></a></li>
                      <li><a href="#">Dep D <span class="pull-right text-green"></i> 4%</span></a></li>
                    <li><a href="#">Total (%)
                      <span class="pull-right text-yellow"> 0%</span></a></li>
                  </ul>
                </div>
                <div class="col-lg-11 text-center" style="text-align:center">
                    <canvas id="chart_department"  style="min-height:auto;" ></canvas>
                </div>
             </section>
             
            
 	</div>
   
</div>
<div class="box box-solid">
    <div class="box-header ui-sortable-handle" style="cursor: move;">
      <i class="fa fa-th"></i>

      <h3 class="box-title">Report Payable</h3>

      <div class="box-tools pull-right">
      
        <button type="button" class="btn bg-teal btn-sm btn-refresh" title="Perbarui data anggota" onclick="if (confirm('Yakin akan memperbarui data anggota')==true){perbarui() };"><i class="fa fa-refresh"></i></button>
      </div>
    </div>
    <div class="box-body border-radius-none" style="">
            
            <section class="col-lg-6 col-xs-12" style="">
                <div class="col-lg-7">
                 <canvas id="chart-budget"  style="min-height:auto;"></canvas>
                </div>
                 <div class="col-lg-5">
                  <!-- small box -->
                 <ul class="nav nav-pills nav-stacked">
                    <li><a href="#">Expenses Budget
                      <span class="pull-right text-red"> 12%</span></a></li>
                    <li><a href="#">Actual Expenses <span class="pull-right text-green"></i> 4%</span></a>
                    </li>
                    <li><a href="#">Deference (%)
                      <span class="pull-right text-yellow"> 0%</span></a></li>
                  </ul>
                </div>
                <div class="col-lg-15 text-center" style="">
                    <canvas id="chart_monthly"  style="min-height:auto;"></canvas>
                </div>
             </section>
             
              <section class="col-lg-6 col-xs-12" style="">
                <div class="col-lg-8" style="padding-left:0px;padding-right:0px">
                 <canvas id="chart-budget-department"  style="min-height:auto;"></canvas>
                </div>
                 <div class="col-lg-4" >
                  <!-- small box -->
                  <style>
				  .list-department li a{
					 
					  padding-top:2px;
					  margin-top:2px;
					  padding-bottom:2px;
					  margin-bottom:2px;
				  }
				  </style>
                 <ul class="nav nav-pills nav-stacked list-department">
                    <li style="padding-top:0px;margin-top:0px;"><a href="#">Dep A
                      <span class="pull-right text-red" > 12%</span></a></li>
                    <li><a href="#" >Dep B <span class="pull-right text-green"></i> 4%</span></a></li>
                     <li><a href="#">Dep C <span class="pull-right text-green"></i> 4%</span></a></li>
                      <li><a href="#">Dep D <span class="pull-right text-green"></i> 4%</span></a></li>
                    <li><a href="#">Total (%)
                      <span class="pull-right text-yellow"> 0%</span></a></li>
                  </ul>
                </div>
                <div class="col-lg-11 text-center" style="text-align:center">
                    <canvas id="chart_department"  style="min-height:auto;" ></canvas>
                </div>
             </section>
             
            
 	</div>
   
</div>

<script>
	var ddata= {
		datasets: [{
			data: [<?php echo $data_budget['budget'];?>,<?php echo $data_budget['actual'];?>],
			backgroundColor: [
					'rgba(255, 99, 132, 0.8)',
					'rgba(54, 162, 235, 0.7)'
				],
		}],
	
		// These labels appear in the legend and in the tooltips when hovering different arcs
		labels: [
			'Expenses'
		]
	};
	var ctx1 = document.getElementById('chart-budget').getContext('2d');;
	var myDoughnutChart = new Chart(ctx1, {
		type: 'doughnut',
		data: ddata
	});
	var ctx = document.getElementById('chart_monthly').getContext('2d');;
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: <?php echo json_encode($bar_chart);?>,
		options: {
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: 20000,
						max: 300000
					}
				}],
				xAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7
					},
					afterFit: (scale) => {
						scale.height = 30;
					}
				}]
			}
		}
	});
	
	var ddata2= {
		datasets: [{
			data: [25,25,30,20],
			backgroundColor: [
					'rgba(255, 125, 132, 0.8)',
					'rgba(125, 162, 235, 0.7)',
					'rgba(75, 162, 125, 0.7)',
					'rgba(25, 162, 255, 0.7)'
				],
			
		}],
	
		// These labels appear in the legend and in the tooltips when hovering different arcs
		labels: [
			'Dep. A',
			'Dep. B',
			'Dep. C',
			'Dep. D',
		]
	};
	var ctx3 = document.getElementById('chart-budget-department').getContext('2d');;
	var myDoughnutChart = new Chart(ctx3, {
		type: 'doughnut',
		data: ddata2
	});
	
	var ctx = document.getElementById('chart_department').getContext('2d');;
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: <?php echo json_encode($bar_chart_department);?>,
		options: {
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: 20000,
						max: 100000
					}
				}],
				xAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7
					},
					afterFit: (scale) => {
						scale.height = 30;
					}
				}]
			}
		}
	});

</script>   