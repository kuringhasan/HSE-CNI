
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo $theme_path;?>/plugins/chart.js/Chart.min.js"></script>
	<script src="<?php echo $theme_path;?>/plugins/chart.js/utils.js"></script>

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
    <section class="col-lg-4 col-xs-12" style="">
    	<div class="box box-primary" style="border:1px solid #9CF">
        	<div class="box-body border-radius" style="">    
              <div class="col-lg-16 text-center" style="text-align:center;">
               
                 <canvas id="chart-transit"  style="min-height:auto;padding:0 0 0 0;margin:0 0 0 0;"></canvas>
                
              </div>
              <div class="col-lg-16">
                
                  <!-- small box -->
                  <?php //echo "<pre>";print_r($list_partner); echo "</pre>";?>
                 <ul class="nav nav-pills nav-stacked">
                 	<?php 
					//while($data=current($data_transit_ore)){//list_partner
					foreach($list_partner as $key2=>$value2){
						//echo "key:".$key2."<br />";
					?>
                    <li><a href="#"><?php echo $value2['partner_name'];?>
                        <span class="pull-right" style="color:<?php echo $value2['color'];?>"> 
						 <?php echo number_format($data_transit_ore[$key2]['jml_quantity'],2,",",".");?></span></a></li>
                    <?php
					 //next($data_transit_ore);
					}
					?>
                   
                  </ul>
           </div>
        </div>
       </div> 
     </section>
     <section class="col-lg-4 col-xs-12" style="">
    	<div class="box box-primary" style="border:1px solid #9CF">
        	<div class="box-body border-radius" style="">    
              <div class="col-lg-16 text-center" style="text-align:center;">
                 <canvas id="chart-crossmining"  style="min-height:auto;padding:0 0 0 0;margin:0 0 0 0;"></canvas>              </div>
              <div class="col-lg-16">
                  <!-- small box -->
                  <?php //echo "<pre>";print_r($donut_chart_cumtcrossmining); echo "</pre>";?>
                 <ul class="nav nav-pills nav-stacked">
                 	<?php 
					//while($data1=current($data_transit_crossmining)){
					foreach($list_partner as $key3=>$value3){
					?>
                    <li><a href="#"><?php echo $value3['partner_name'];?>
                        <span class="pull-right" style="color:<?php echo $value3['color'];?>"> 
						 <?php echo number_format($data_transit_crossmining[$key3]['jml_quantity'],2,",",".");?></span></a></li>
                    <?php
					// next($data_transit_crossmining);
					}
					?>
                   
                  </ul>
          </div>
        </div>
       </div> 
     </section>
     <section class="col-lg-4 col-xs-12" style="">
    	<div class="box box-primary" style="border:1px solid #9CF">
        	<div class="box-body border-radius" style="">    
              <div class="col-lg-16 text-center" style="text-align:center;">
                 <canvas id="chart-rehandling"  style="min-height:auto;padding:0 0 0 0;margin:0 0 0 0;"></canvas>
               
              </div>
              <div class="col-lg-16">
                  <!-- small box -->
                  <?php //echo "<pre>";print_r($donut_chart_cumrehandling); echo "</pre>";?>
                 <ul class="nav nav-pills nav-stacked">
                   <?php 
					//while($data1=current($donut_chart_cumrehandling)){
					foreach($list_partner as $key4=>$value4){
					?>
                    <li><a href="#"><?php echo $value4['partner_name'];?>
                        <span class="pull-right" style="color:<?php echo $value4['color'];?>"> 
						 <?php echo number_format($rehandling_commulative[$key4]['jml_quantity'],2,",",".");?></span></a></li>
                    <?php
					// next($data_transit_crossmining);
					}
					?>
                  </ul>
          </div>
        </div>
       </div> 
    </section>
</div><!-- end of row-->     
<div class="row" style="margin-top:2px;">
      <section class="col-lg-6 col-xs-12" >
     	<div class="box box-primary" style="border:1px solid #9CF">
        	<div class="box-body border-radius" style="">           
            	
                    <canvas id="barchart_cummulative" style="min-height:350px;margin-left:3px;"></canvas>
               
             </div>
          </div>
      </section>
      <section class="col-lg-6 col-xs-12" >
     	<div class="box box-primary" style="border:1px solid #9CF">
        	<div class="box-body border-radius" style="">           
                    <canvas id="barchart_cummulative_rehandling"  height="215"  style="min-height:350px;margin-left:3px;"></canvas>
             </div>
         </div>
      </section>
</div><!-- end of row-->    
<div class="box box-solid">
    <div class="box-header ui-sortable-handle" style="cursor: move;">
      <i class="fa fa-th"></i>

      <h3 class="box-title">Trend Monthly Production</h3>

      <div class="box-tools pull-right">
      
        <button type="button" class="btn bg-teal btn-sm btn-refresh" title="Perbarui data anggota" onclick="if (confirm('Yakin akan memperbarui data anggota')==true){perbarui() };"><i class="fa fa-refresh"></i></button>
      </div>
    </div>
    <div class="box-body border-radius-none" style="">
        <div class="row">    
            <section class="col-lg-6 col-xs-12" style="">
                 
               
                <div class="col-lg-15 text-center" style="">
                    <canvas id="barchart_monthly_stockpiling"  style="min-height:auto;"></canvas>
                </div>
              
             </section>
             <section class="col-lg-6 col-xs-12" style="">
                 
                <div class="col-lg-15 text-center" style="">
                    <canvas id="barchart_monthly_crossmining"  style="min-height:auto;"></canvas>
                </div>
               
             </section>
         </div>    
          <div class="row">    
            <section class="col-lg-6 col-xs-12" style="">
                 
                <div class="col-lg-15 text-center" style="">
                    <canvas id="barchart_monthly_rehandling"  style="min-height:auto;"></canvas>
                </div>
               
             </section>
        
            <section class="col-lg-6 col-xs-12" style="">
                 
                <div class="col-lg-15 text-center" style="">
                    <canvas id="barchart_monthly_shipment"  style="min-height:auto;"></canvas>
                </div>
               
             </section>
         </div>     
         
          <div class="row">    
            <section class="col-lg-12 col-xs-12" style="">
                 
                <div class="col-lg-15 text-center" style="">
                    <canvas id="barchart_barge_shipment_monthly1"  style="min-height:auto;"></canvas>
                </div>
               
             </section>
        
         </div>
         <?php
		 if($total_data_barge_shipment_monthly2>0){
		 ?>    
          <div class="row">    
            <section class="col-lg-12 col-xs-12" style="">
                 
                <div class="col-lg-15 text-center" style="">
                    <canvas id="barchart_barge_shipment_monthly2"  style="min-height:auto;"></canvas>
                </div>
               
             </section>
        
         </div>   
          <?php
		 }
		 ?>            
        <!-- <div class="row">    
            <section class="col-lg-6 col-xs-12" style="">
                 
                <div class="row">
                <div class="col-lg-15 text-center" style="">
                    <canvas id="chart_monthly3"  style="min-height:auto;"></canvas>
                </div>
               </div>
               <div class="row text-center">
                  <h4>Shipment Ore By Contractor</h4>
               </div>
             </section>
             <section class="col-lg-6 col-xs-12" style="">
                 
                <div class="row">
                <div class="col-lg-15 text-center" style="">
                    <canvas id="chart_monthly4"  style="min-height:auto;"></canvas>
                </div>
               </div>
               <div class="row text-center">
                  <h4>Contractor C Production By Report Category</h4>
               </div>
             </section>
         </div>    -->
             
             
            
 	</div>
   
</div>

<script>
 
	var ddata= {
		datasets: [{
			data: <?php echo json_encode($donut_chart_cumtransit['datasets']['data']);?>,
			backgroundColor: <?php echo json_encode($donut_chart_cumtransit['datasets']['backgroundColor']);?>,
		}],
	
		// These labels appear in the legend and in the tooltips when hovering different arcs
		labels: <?php echo json_encode($donut_chart_cumtransit['labels']);?>
	};
	var ctx1 = document.getElementById('chart-transit').getContext('2d');;
	var myDoughnutChart = new Chart(ctx1, {
		type: 'doughnut',//donut_chart
		data: ddata,
		options: {
		    title: {
				display: true,
				text: 'Stockpiling (PIT to ETO/EFO)'
		    }
		}
	});
	
	var ddata_cross= {
		datasets: [{
			data: <?php echo json_encode($donut_chart_cumtcrossmining['datasets']['data']);?>,
			backgroundColor: <?php echo json_encode($donut_chart_cumtcrossmining['datasets']['backgroundColor']);?>,
		}],
	
		// These labels appear in the legend and in the tooltips when hovering different arcs
		labels: <?php echo json_encode($donut_chart_cumtcrossmining['labels']);?>
	};
	var ctx_cross = document.getElementById('chart-crossmining').getContext('2d');;
	var myDoughnutChartCross = new Chart(ctx_cross, {
		type: 'doughnut',//donut_chart
		data: ddata_cross,
		options: {
		    title: {
				display: true,
				text: 'Cross Mining (PIT to Barge)'
		    }
		}
	});
	
	var ddata_rehandling= {
		datasets: [{
			data: <?php echo json_encode($donut_chart_cumrehandling['datasets']['data']);?>,
			backgroundColor: <?php echo json_encode($donut_chart_cumrehandling['datasets']['backgroundColor']);?>,
		}],
	
		// These labels appear in the legend and in the tooltips when hovering different arcs
		labels: <?php echo json_encode($donut_chart_cumrehandling['labels']);?>
	};
	var ctx_rehandling = document.getElementById('chart-rehandling').getContext('2d');;
	var myDoughnutChartRehandling = new Chart(ctx_rehandling, {
		type: 'doughnut',//donut_chart
		data: ddata_rehandling,
		options: {
		    title: {
				display: true,
				text: 'Rehandling (ETO/EFO to Barge)'
		    }
		}
	});
	
	var ctxbar_cum = document.getElementById('barchart_cummulative').getContext('2d');;
	var myChart_cum = new Chart(ctxbar_cum, {
		type: 'bar',
		data: <?php echo $bar_chart_pit;?>,
		options: {
			legend: { display: true },
			title: {
				display: true,
				text: 'Expit Ore/Cross Mining By PIT (Tons)'
			},
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: 10000,
						max: 120000
						
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
	
	
	
	var ctxbar_cum_rehandling = document.getElementById('barchart_cummulative_rehandling').getContext('2d');
	var myChart_cum_rehandling = new Chart(ctxbar_cum_rehandling, {
		type: 'bar',
		data: <?php echo $bar_chart_rehandling;?>,
		options: {
			legend: { display: false },
			
			title: {
				display: true,
				text: 'Rehandling (Tons)'
			},
			responsive: true,
			scales: {
				yAxes: [{
					display: true,
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: 15000,
						max: 210000
						
					}
				}],
				xAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 10
					}
				}]
			}
		}
	});
	
	/* Bar Chart Monthly Stockpiling */
	 var ctxbar_monthly_stockpiling = document.getElementById('barchart_monthly_stockpiling').getContext('2d');
	 var myChartMS = new Chart(ctxbar_monthly_stockpiling, {
		type: 'bar',
		data: <?php echo $barchart_monthly_stockpling;?>,
		options: {
			legend: { display: true },
			title: {
				display: true,
				text: 'Monthly Stockpiling (PIT to ETO/EFO) - Tons'
			},
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: 8000,
						max: 100000
						
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
	
	/* Bar Chart Monthly Crossmining */
	 var ctxbar_monthly_crossmining = document.getElementById('barchart_monthly_crossmining').getContext('2d');
	 var myChartMCM = new Chart(ctxbar_monthly_crossmining, {
		type: 'bar',
		data: <?php echo $barchart_monthly_crossmining;?>,
		options: {
			legend: { display: true },
			title: {
				display: true,
				text: 'Monthly PIT to Barge (Cross Mining) - Tons'
			},
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: 8000,
						max: 100000
						
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
	
	/* Bar Chart Monthly Rehandling */
	 var ctxbar_monthly_rehandling = document.getElementById('barchart_monthly_rehandling').getContext('2d');
	 var myChartMRHD = new Chart(ctxbar_monthly_rehandling, {
		type: 'bar',
		data: <?php echo $barchart_monthly_rehandling;?>,
		options: {
			legend: { display: true },
			title: {
				display: true,
				text: 'Monthly Rehandling - Tons'
			},
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: 8000,
						max: 100000
						
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
	
	/* Bar Chart Monthly Shipment */
	 var ctxbar_monthly_shipment = document.getElementById('barchart_monthly_shipment').getContext('2d');
	 var myChartSH = new Chart(ctxbar_monthly_shipment, {
		type: 'bar',
		data: <?php echo $barchart_monthly_shipment;?>,
		options: {
			legend: { display: true },
			title: {
				display: true,
				text: 'Monthly Shipment - Tons'
			},
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: 10000000,
						max: 200000000
						
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
	
	
	/* Bar Chart Monthly Barge Shipment */
	 var ctxbar_barge_shipment_monthly1 = document.getElementById('barchart_barge_shipment_monthly1').getContext('2d');
	 var myChartBSM1 = new Chart(ctxbar_barge_shipment_monthly1, {
		type: 'bar',
		data: <?php echo $barchart_barge_shipment_monthly1;?>,
		options: {
			legend: { display: true },
			title: {
				display: true,
				text: 'Barge Monthly Shipment (Jan-Jun) - Kilo Tons'
			},
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: 8000,
						max: 40000
						
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
	<?php
	if($total_data_barge_shipment_monthly2>0){
	?>  
	/* Bar Chart Monthly Barge Shipment */
	 var ctxbar_barge_shipment_monthly2 = document.getElementById('barchart_barge_shipment_monthly2').getContext('2d');
	 var myChartBSM2 = new Chart(ctxbar_barge_shipment_monthly2, {
		type: 'bar',
		data: <?php echo $barchart_barge_shipment_monthly2;?>,
		options: {
			legend: { display: false },
			title: {
				display: true,
				text: 'Barge Monthly Shipment (Jul-Dec) - Kilo Tons'
			},
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: 8000,
						max: 40000
						
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
	<?php
	}
	?>
	

</script>   