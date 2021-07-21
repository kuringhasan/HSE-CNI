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

      <h3 class="box-title">Cumulative Production</h3>

      <div class="box-tools pull-right">
      
        <button type="button" class="btn bg-teal btn-sm btn-refresh" title="Perbarui data anggota" onclick="if (confirm('Yakin akan memperbarui data anggota')==true){perbarui() };"><i class="fa fa-refresh"></i></button>
      </div>
    </div>
    <div class="box-body border-radius-none" style="">
            
            <section class="col-lg-3 col-xs-6" style="">
                <div class="col-lg-15">
                  <!-- small box -->
                  <div class="small-box bg-yellow">
                    <div class="inner">
                      <span class="box-value"><?php echo number_format($recap[157]['qty'],2,",",".");?></span>
        
                      <p class="box-partner"><?php echo $recap[157]['parnter_name'];?></p>
                    </div>
                    <div class="icon">
                       <i class="fa fa-bank "></i>
                       
                    </div>
                    <a href="<?php echo $url_recap;?>?partner=157" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <div class="col-lg-15 text-center" style="">
                    <canvas id="chart157"  style="min-height:auto;"></canvas>
                </div>
             </section>
             <section class="col-lg-3 col-xs-6">
                <!-- ./col -->
                <div class="col-lg-15">
                  <!-- small box -->
                  <div class="small-box bg-green">
                    <div class="inner" style="z-index:10000;position:relative">
                      <span class="box-value"><?php echo number_format($recap[158]['qty'],2,",",".");?></span>
        
                      <p class="box-partner" ><?php echo $recap[158]['parnter_name'];?></p>
                    </div>
                    <div class="icon">
                      <img src="<?php echo $theme_path;?>images/lcp.png" style="height:80px;"  >
                    </div>
                    <a href="<?php echo $url_recap;?>?partner=158" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <!-- ./col -->
                 <div class="col-lg-15 text-center" style="margin-bottom:5px;">
                    <canvas id="chart158" ></canvas>
                </div>
             </section>
             <section class="col-lg-3 col-xs-6">
                <div class="col-lg-15">
                  <!-- small box -->
                  <div class="small-box bg-aqua">
                    <div class="inner" style="z-index:10000;position:relative">
                      <span class="box-value"><?php echo number_format($recap[159]['qty'],2,",",".");?></span>
        
                      <p class="box-partner"><?php echo $recap[159]['parnter_name'];?></p>
                    </div>
                    <div class="icon">
                      <img src="<?php echo $theme_path;?>images/pl.png" style="height:80px;"  >
                    </div>
                    <a href="<?php echo $url_recap;?>?partner=159" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <!-- ./col -->
                 <div class="col-lg-15 text-center" style="margin-bottom:5px;">
                    <canvas id="chart159" style=""></canvas>
                </div>
             </section>
             <section class="col-lg-3 col-xs-6">
                <div class="col-lg-15">
                  <!-- small box -->
                  <div class="small-box bg-red">
                    <div class="inner">
                      <span class="box-value"><?php echo number_format($recap[160]['qty'],2,",",".");?></span>
        
                      <p class="box-partner"><?php echo $recap[160]['parnter_name'];?></p>
                    </div>
                    <div class="icon">
                      <i class="fa fa-bank "></i>
                    </div>
                    <a href="<?php echo $url_recap;?>?partner=160" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <!-- ./col -->
                 <div class="col-lg-15 text-center" style="">
                    <canvas id="chart160" ></canvas>
                </div>
             </section>
 	</div>
</div>
<script>
	
	var ctx = document.getElementById('chart157').getContext('2d');;
	var myChart = new Chart(ctx, {
		type: 'line',
		data: <?php echo json_encode($bar_chart[157]);?>,
		options: {
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: 30000,
						max: 150000
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
	
	var ctx1 = document.getElementById('chart158').getContext('2d');;
	var myChart1 = new Chart(ctx1, {
		type: 'line',
		data: <?php echo json_encode($bar_chart[158]);?>/*{
			labels: ['Jan', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul','Agu','Sep', 'Okt', 'Nop', 'Des'],
			datasets: [{
				label: '# of Votes',
				data: [12, 19, 3, 5, 2, 3,23,23,14,21,22,12],
				backgroundColor: [
					'rgba(255, 99, 132, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(255, 206, 86, 0.2)',
					'rgba(75, 192, 192, 0.2)',
					'rgba(153, 102, 255, 0.2)',
					'rgba(255, 159, 64, 0.2)'
				],
				borderColor: [
					'rgba(255, 99, 132, 1)',
					'rgba(54, 162, 235, 1)',
					'rgba(255, 206, 86, 1)',
					'rgba(75, 192, 192, 1)',
					'rgba(153, 102, 255, 1)',
					'rgba(255, 159, 64, 1)'
				],
				borderWidth: 1
			}]
		}*/
		,options: {
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: 30000,
						max: 150000
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
	
	var ctx2 = document.getElementById('chart159').getContext('2d');;
	var myChart2 = new Chart(ctx2, {
		type: 'line',
		data: <?php echo json_encode($bar_chart[159]);?>,
		options: {
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: 30000,
						max: 150000
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
	var ctx3 = document.getElementById('chart160').getContext('2d');;
	var myChart3 = new Chart(ctx3, {
		type: 'line',
		data: <?php echo json_encode($bar_chart[160]);?>,
		options: {
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: 30000,
						max: 150000
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