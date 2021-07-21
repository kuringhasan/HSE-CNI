


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
              <h3 class="box-title"><?php echo $judul_expit;?></h3>
            </div>
        	<div class="box-body border-radius" style="">           
            	<div class="col-lg-8 text-center" style="text-align:center">
                    <canvas id="chart_department"  style="min-height:auto;" ></canvas>
                </div>
                <div class="col-lg-4" style="padding-left:0px;padding-right:0px">
                  <!-- small box -->
                  <style>
				  .list-department li{
					  font-size:12px;
				  }
				  .list-department li a{
					 
					  padding-top:0px;
					  margin-top:2px;
					  padding-bottom:0px;
					  margin-bottom:2px;
				  }
				  </style>
                 <ul class="nav nav-pills nav-stacked list-department" style="margin-top:10px">
                 <?php 
				// print_r($list_contractor_active);
				$total_ritase=0;
				 foreach($list_contractor_active as $key=>$value){
					 $total_ritase=$total_ritase+$value['jml_ritase'];
					 ?>
                    <li style="padding-top:0px;margin-top:0px;">
                    	<a href="#" style="color:<?php echo $value['rgb_color'];?>"><?php echo $value['alias'];?>
                      <span class="pull-right" style="color:<?php echo $value['rgb_color'];?>"> <?php echo $value['jml_ritase'];?></span></a></li>
                   
                      <?php
				 }?>
                    <li><a href="#">Total Ritase
                      <span class="pull-right"> <?php echo $total_ritase;?></span></a></li>
                  </ul>
                </div>
            </div><!-- end of body--> 
        </div>
     </section>
     <section class="col-lg-6 col-xs-12" >
     	<div class="box box-primary" style="border:1px solid #9CF">
            <div class="box-header ui-sortable-handle text-center" style="cursor: move;">
              <h3 class="box-title"><?php echo $judul_barging;?></h3>
            </div>
        	<div class="box-body border-radius" style="">           
            	<div class="col-lg-8 text-center" style="text-align:center">
                    <canvas id="chart_barging"  style="min-height:auto;" ></canvas>
                </div>
                <div class="col-lg-4" style="padding-left:0px;padding-right:0px">
                  <!-- small box -->
                  <style>
				  .list-department li{
					  font-size:12px;
					  margin-left:0px;
					  margin-right:0px;
					  padding-left:0px;
				  }
				  .list-department li a{
					 
					  padding-top:0px;
					  margin-top:2px;
					  padding-bottom:0px;
					  margin-bottom:2px;
				  }
				  </style>
                 <ul class="nav nav-pills nav-stacked list-department" style="margin-top:10px;margin-left:0px;">
                 <?php 
				//echo "<pre>";print_r($list_contractor_active);echo "</pre>";
				$total_ritase_barging=0;
				 foreach($list_contractor_active as $key=>$value){
					 $total_ritase_barging=$total_ritase_barging+$value['jml_ritase_barging'];
					 ?>
                    <li style="margin-left:0px;padding-top:0px;margin-top:0px;">
                    	<a href="#" style="color:<?php echo $value['rgb_color'];?>"><?php echo $value['alias'];?>
                      <span class="pull-right" style="color:<?php echo $value['rgb_color'];?>"> <?php echo $value['jml_ritase_barging'];?></span></a></li>
                   
                      <?php
				 }?>
                    <li><a href="#">Total Ritase
                      <span class="pull-right text-yellow"> <?php echo $total_ritase_barging;?></span></a></li>
                  </ul>
                </div>
            </div><!-- end of body--> 
        </div>
     </section>
</div> 

<div class="row">
     <section class="col-lg-6 col-xs-12" >
     	<div class="box box-primary" style="border:1px solid #9CF">
            <div class="box-header ui-sortable-handle text-center" style="cursor: move;">
              <h3 class="box-title"><?php echo $judul_weight_expit;?></h3>
            </div>
        	<div class="box-body border-radius" style="">           
            	<div class="col-lg-8 text-center" style="text-align:center">
                    <canvas id="chart_weight_expit"  style="min-height:auto;" ></canvas>
                </div>
                <div class="col-lg-4"  style="padding-left:0px;padding-right:0px">
                  <!-- small box -->
                  <style>
				  .list-department li{
					  font-size:12px;
					  margin-left:0px;
					  margin-right:0px;
				  }
				  .list-department li a{
					 
					  padding-top:0px;
					  margin-top:2px;
					  padding-bottom:0px;
					  margin-bottom:2px;
				  }
				  </style>
                 <ul class="nav nav-pills nav-stacked list-department" style="margin-top:10px;">
                 <?php 
				 //print_r($list_contractor_active);
				$total_weight=0;
				 foreach($list_contractor_active as $key=>$value){
					 $total_weight=$total_weight+$value['jml_quantity'];
					 ?>
                    <li style="padding-top:0px;margin-top:0px;">
                    	<a href="#" style="color:<?php echo $value['rgb_color'];?>"><?php echo $value['alias'];?>
                      <span class="pull-right" style="mcolor:<?php echo $value['rgb_color'];?>" > <?php echo number_format($value['jml_quantity'],2,",",".");?></span></a></li>
                   
                      <?php
				 }?>
                    <li><a href="#">Total
                      <span class="pull-right"> <?php echo number_format($total_weight,2,",",".");?></span></a></li>
                  </ul>
                </div>
            </div><!-- end of body--> 
        </div>
     </section>
     <section class="col-lg-6 col-xs-12" >
     	<div class="box box-primary" style="border:1px solid #9CF">
            <div class="box-header ui-sortable-handle text-center" style="cursor: move;">
              <h3 class="box-title"><?php echo $judul_weight_barging;?></h3>
            </div>
        	<div class="box-body border-radius" style="">           
            	<div class="col-lg-8 text-center" style="text-align:center">
                    <canvas id="chart_weight_barging"  style="min-height:auto;" ></canvas>
                </div>
                <div class="col-lg-4"  style="margin-left:0px;padding-right:0px">
                  <!-- small box -->
                  <style>
				  .list-department li{
					  font-size:12px;
					   margin-left:-10px;
					  margin-right:0px;
					  
					  
				  }
				  .list-department li a{
					 
					  padding-top:0px;
					  margin-top:2px;
					  padding-bottom:0px;
					  margin-bottom:2px;
				  }
				  </style>
                 <ul class="nav nav-pills nav-stacked list-department" style="margin-top:10px;">
                 <?php 
				//echo "<pre>";print_r($list_contractor_active);echo "</pre>";
				$total_weight_barging=0;
				 foreach($list_contractor_active as $key=>$value){
					 $total_weight_barging=$total_weight_barging+$value['jml_quantity_barging'];
					 ?>
                    <li style="padding-left:-10px;padding-top:0px;margin-top:0px;color:<?php echo $value['color'];?>">
                    	<a href="#" style="margin-left:0px;color:<?php echo $value['rgb_color'];?>"><?php echo $value['alias'];?>
                      <span class="pull-right" style="color:<?php echo $value['rgb_color'];?>"> <?php echo number_format($value['jml_quantity_barging'],2,",",".");?></span></a></li>
                   
                      <?php
				 }?>
                    <li><a href="#">Total 
                      <span class="pull-right"> <?php echo number_format($total_weight_barging,2,",",".");?></span></a></li>
                  </ul>
                </div>
            </div><!-- end of body--> 
        </div>
     </section>
</div> 

<script>

 
 var ctx = document.getElementById('chart_department').getContext('2d');;
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: <?php echo json_encode($bar_chart_expit);?>,
		options: {
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: <?php echo $y_step;?>,
						max: <?php echo $y_max;?>
					}
				}],
				xAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7
					},
					afterFit: (scale) => {
						scale.height = 25;
					}
				}]
			}
		}
	});
var ctx2 = document.getElementById('chart_weight_expit').getContext('2d');;
	var myChart2 = new Chart(ctx2, {
		type: 'bar',
		data: <?php echo json_encode($bar_chart_weight_expit);?>,
		options: {
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: <?php echo $y_step_weight;?>,
						max: <?php echo $y_max_weight;?>
					}
				}],
				xAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7
					},
					afterFit: (scale) => {
						scale.height = 25;
					}
				}]
			}
		}
	});

var ctx3 = document.getElementById('chart_barging').getContext('2d');;
	var myChart3 = new Chart(ctx3, {
		type: 'bar',
		data: <?php echo json_encode($bar_chart_barging);?>,
		options: {
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: <?php echo $y_step;?>,
						max: <?php echo $y_max;?>
					}
				}],
				xAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7
					},
					afterFit: (scale) => {
						scale.height = 25;
					}
				}]
			}
		}
	});
var ctx4 = document.getElementById('chart_weight_barging').getContext('2d');;
	var myChart4 = new Chart(ctx4, {
		type: 'bar',
		data: <?php echo json_encode($bar_chart_weight_barging);?>,
		options: {
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7,
						stepSize: <?php echo $y_step_weight;?>,
						max: <?php echo $y_max_weight;?>
					}
				}],
				xAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 7
					},
					afterFit: (scale) => {
						scale.height = 25;
					}
				}]
			}
		}
	});

</script>   