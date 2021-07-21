
<style>
	canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}
.box{
	margin-left:4px;
	margin-right:4px;
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
<div class="row">
     <section class="col-lg-6 col-xs-12" >
     	<div class="box box-primary" style="border:1px solid #9CF">
            <div class="box-header ui-sortable-handle text-center" style="cursor: move;">
              <h3 class="box-title"><?php echo $bar_chart_ritase_shipment_title;?></h3>
            </div>
        	<div class="box-body border-radius" style="">           
            	<div class="col-lg-12 text-center" style="text-align:center">
                    <canvas id="chart_ritase_shipment"  style="min-height:auto;" ></canvas>
                   
                </div>
               
            </div><!-- end of body--> 
        </div>
     </section>
      <section class="col-lg-6 col-xs-12" >
     	<div class="box box-primary" style="border:1px solid #9CF">
            <div class="box-header ui-sortable-handle text-center" style="cursor: move;">
              <h3 class="box-title"><?php echo $bar_chart_ritase_completed_title;?></h3>
            </div>
        	<div class="box-body border-radius" style="">           
            	<div class="col-lg-12 text-center" style="text-align:center">
                    <canvas id="chart_ritase_completed"  style="min-height:auto;" ></canvas>
                   
                </div>
               
            </div><!-- end of body--> 
        </div>
     </section>
    
</div> 

<div class="row">
     <section class="col-lg-6 col-xs-12" >
     	<div class="box box-primary" style="border:1px solid #9CF">
            <div class="box-header ui-sortable-handle text-center" style="cursor: move;">
              <h3 class="box-title"><?php echo $bar_chart_ids_progress_title;?></h3>
            </div>
        	<div class="box-body border-radius" style="">           
            	<div class="col-lg-12 text-center" style="text-align:center">
                    <canvas id="chart_ids_progress"  style="min-height:auto;" ></canvas>
                   
                </div>
               
            </div><!-- end of body--> 
        </div>
     </section>
      <section class="col-lg-6 col-xs-12" >
     	<div class="box box-primary" style="border:1px solid #9CF">
            <div class="box-header ui-sortable-handle text-center" style="cursor: move;">
              <h3 class="box-title"><?php echo $bar_chart_ids_completed_title;?></h3>
            </div>
        	<div class="box-body border-radius" style="">           
            	<div class="col-lg-12 text-center" style="text-align:center">
                    <canvas id="chart_ids_completed"  style="min-height:auto;" ></canvas>
                   
                </div>
               
            </div><!-- end of body--> 
        </div>
     </section>
    
</div> 


<script>

	 var ctx_test = document.getElementById('chart_ritase_shipment').getContext('2d');
	 window.myBar = new Chart(ctx_test, {
		type: 'bar',
		data: <?php echo json_encode($bar_chart_ritase_shipment);?>,
		options: {
			tooltips: {
				mode: 'index',
				intersect: true
			},
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: <?php echo $y_step_ritase;?>,
						max: <?php echo $y_max_ritase;?>
					},
					stacked: true
				}],
				xAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7
					},
					stacked: true,
					afterFit: (scale) => {
						scale.height = 25;
					}
				}]
			}
		}
	});
	
	var ctx_rc = document.getElementById('chart_ritase_completed').getContext('2d');
	 window.myBar_rc = new Chart(ctx_rc, {
		type: 'bar',
		data: <?php echo json_encode($bar_chart_ritase_completed);?>,
		options: {
			tooltips: {
				mode: 'index',
				intersect: true
			},
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: <?php echo $y_step_ritase_completed;?>,
						max: <?php echo $y_max_ritase_completed;?>
					},
					stacked: true
				}],
				xAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7
					},
					stacked: true,
					afterFit: (scale) => {
						scale.height = 25;
					}
				}]
			}
		}
	});
	
	var ctx_ids_progress = document.getElementById('chart_ids_progress').getContext('2d');
	 window.myBar_ids_progress = new Chart(ctx_ids_progress, {
		type: 'bar',
		data: <?php echo json_encode($bar_chart_ids_progress);?>,
		options: {
			tooltips: {
				mode: 'index',
				intersect: true
			},
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: <?php echo $y_step_ids_progress;?>,
						max: <?php echo $y_max_ids_progress;?>
					},
					stacked: true
				}],
				xAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7
					},
					stacked: true,
					afterFit: (scale) => {
						scale.height = 25;
					}
				}]
			}
		}
	});
	
	var ctx_ids_completed = document.getElementById('chart_ids_completed').getContext('2d');
	 window.myBar_ids_progress = new Chart(ctx_ids_completed, {
		type: 'bar',
		data: <?php echo json_encode($bar_chart_ids_completed);?>,
		options: {
			tooltips: {
				mode: 'index',
				intersect: true
			},
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: <?php echo $y_step_ids_completed;?>,
						max: <?php echo $y_max_ids_completed;?>
					},
					stacked: true
				}],
				xAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7
					},
					stacked: true,
					afterFit: (scale) => {
						scale.height = 25;
					}
				}]
			}
		}
	});
 

</script>   