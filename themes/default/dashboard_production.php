<!-- daterange picker -->
  <link rel="stylesheet" href="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>


  <!-- date-range-picker -->
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->

<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo $theme_path;?>/plugins/chart.js/Chart.min.js"></script>
	<script src="<?php echo $theme_path;?>/plugins/chart.js/utils.js"></script>
<?php

//if($current_level->Unit=="" or $current_level->Unit=="02"){
?>
	<div class="box box-solid"><!-- start of daily_production -->
        <div class="box-header ui-sortable-handle" style="cursor: move;">
          <i class="fa fa-th"></i>
    
          <h3 class="box-title">Daily Production</h3>
    
          <div class="box-tools pull-right">
              <div class="form-group">
                  
                    <div class="input-group">
                      <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                        <span>
                          <i class="fa fa-calendar"></i> Date Range
                        </span>
                        <i class="fa fa-caret-down"></i>
                      </button>
                    </div>
               </div>
              
         
          </div>
        </div>
        <div class="box-body border-radius-none" style="" id="daily_production">
        
        </div>
       
     </div><!-- end of daily_production -->
     
     <div class="box box-solid"><!-- start of daily_shipment -->
        <div class="box-header ui-sortable-handle" style="cursor: move;">
          <i class="fa fa-th"></i>
    
          <h3 class="box-title">Daily Shipment</h3>
    
          <div class="box-tools pull-right">
              <div class="form-group">
                  
                    <div class="input-group">
                      <button type="button" class="btn btn-default pull-right" id="daterange-btn-shipment">
                        <span>
                          <i class="fa fa-calendar"></i> Date Range
                        </span>
                        <i class="fa fa-caret-down"></i>
                      </button>
                    </div>
               </div>
              
         
          </div>
        </div>
        <div class="box-body border-radius-none" style="" id="daily_shipment">
        
        </div>
       
     </div><!-- end of daily_shipment -->
     
     <div class="box box-solid"><!-- start of rekap-production -->
        <div class="box-header ui-sortable-handle" style="cursor: move;">
          <i class="fa fa-th"></i>
    
          <h3 class="box-title">Cummulative Production</h3>
    
          <div class="box-tools pull-right">
              <div class="form-group">
                  
                    <div class="input-group">
                     
                        <select class="btn btn-default pull-right" id="year_cumulative" style="width: 100%;">
                          <?php
                        $List=$list_years;
                        while($data = each($List)) {
                           ?>
                      <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$tpk?"selected":""; ?> ><?php echo $data['value'];?></option>
                      <?php
                      
                        }
                     ?>
                        </select>
                      
                     
                    </div>
               </div>
              
         
          </div>
        </div>
        <div class="box-body border-radius-none" style="" id="rekap-production">
        
        </div>
     </div><!-- end of rekap-production -->
     
     



          <!-- /.row -->
    <div class="row" id="weekly_production">
          
    </div><!-- /.row -->
    <script>
	 $(document).ready(function() {
		
		 loaddashboard('daily_production','daily_production_grafik',"Daily Report");
		 
		 $('#daterange-btn').daterangepicker(
			  {
				format: 'DD/MM/YYYY',
				ranges   : {
				  'Today'       : [moment(), moment()],
				  'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				  'This Week' : [moment().subtract(7, 'days'), moment()],
				  'Last Week' : [moment().subtract(14, 'days'),moment().subtract(7, 'days')],
				  'This Month'  : [moment().startOf('month'), moment().endOf('month')],
				  'Last Month'  : [moment().subtract(1,'month'), moment().subtract(1,'month')],
				},
				buttonClasses: ['btn', 'btn-sm'],
				startDate: moment().subtract(29, 'days'),
				endDate  : moment(),
				autoclose:true,
				
			  },function (start, end,label) {
				  
					//$('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
					if(label=="Custom Range"){
						$('#daterange-btn span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'))
					}else{
						$('#daterange-btn span').html(label);
					}
					var array = [
						{ name:"today", value:"Today" },
						{ name:"yesterday", value:"Yesterday" },
						{ name:"this_week", value:"This Week" },
						{ name:"last_week", value:"Last Week" },
						{ name:"this_month", value:"This Month" },
						{ name:"last_month", value:"Last Month" },
						{ name:"custom", value:"Custom Range" }
					];
					
					var foundValue = array.filter(obj=>obj.value===label);
					//alert('cek'+foundValue[0].name);
					load_data('production',foundValue[0].name,start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
			 });
			 
		/* end of daily shipment */
		 loaddashboard('daily_shipment','daily_shipment_grafik',"Daily Report");
		 $('#daterange-btn-shipment').daterangepicker(
			  {
				format: 'DD/MM/YYYY',
				ranges   : {
				  'Today'       : [moment(), moment()],
				  'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				  'This Week' : [moment().subtract(7, 'days'), moment()],
				  'Last Week' : [moment().subtract(14, 'days'),moment().subtract(7, 'days')],
				  'This Month'  : [moment().startOf('month'), moment().endOf('month')],
				  'Last Month'  : [moment().subtract(1,'month'), moment().subtract(1,'month')],
				},
				buttonClasses: ['btn', 'btn-sm'],
				startDate: moment().subtract(29, 'days'),
				endDate  : moment(),
				autoclose:true,
				
			  },function (start, end,label) {
				  
					//$('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
					if(label=="Custom Range"){
						$('#daterange-btn-shipment span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'))
					}else{
						$('#daterange-btn-shipment span').html(label);
					}
					var array = [
						{ name:"today", value:"Today" },
						{ name:"yesterday", value:"Yesterday" },
						{ name:"this_week", value:"This Week" },
						{ name:"last_week", value:"Last Week" },
						{ name:"this_month", value:"This Month" },
						{ name:"last_month", value:"Last Month" },
						{ name:"custom", value:"Custom Range" }
					];
					
					var foundValue = array.filter(obj=>obj.value===label);
					//alert('cek'+foundValue[0].name);
					load_data('shipment',foundValue[0].name,start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
			 });
			 
			
		  });
		/* end of daily shipment */
		 
		 var currentTime = new Date();
		 var current_year = currentTime.getFullYear();
		 var prm="year="+current_year+"";
		 loaddashboard("rekap-production","dashboard_production_grafik","Loading Production",prm);
		 $("#year_cumulative").change(function(){	
			var year=	$(this).val();	
			loaddashboard("rekap-production","dashboard_production_grafik","Loading Production","year="+year+"");
		 });
		
		//loaddashboard("grafik","grafik","Loading grafik ");
		//loaddashboard("weekly_production","weekly_production","Loading Weekly Production ");
	
		  
		
	function loaddashboard(id_element,kategori,title,param){
		
		$("#"+id_element).html('<span style="margin-left:10px;">'+title+'</span><img src="<?php echo $theme_path."/images/h-loader.gif";?>" style="border: none; margin:5px 5px 5px 5px;opacity: 0.4;filter: alpha(opacity=40); height:10px;"  class="loading_'+kategori+'"/>');
		//alert("<?php echo $url_dashboard;?>/"+kategori);
		$('#'+id_element).load("<?php echo $url_dashboard;?>/"+kategori+'?'+param, function() {
			$(".loading_"+kategori).fadeOut();
		});
	}
	function load_data(category,value,start, end){
		var prm="date_range="+value+'&start_date='+start+'&end_date='+end;
		if(category=="production"){
			loaddashboard('daily_production','daily_production_grafik',"Daily Report",prm);
		}
		if(category=="shipment"){
			loaddashboard('daily_shipment','daily_shipment_grafik',"Daily Report",prm);
		}
	};

	</script>
<?php
//}

?>
