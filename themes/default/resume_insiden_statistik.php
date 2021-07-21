 <script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>

<?php

if($current_level->Unit=="" or $current_level->Unit=="02"){
?>
		<div class="row" >
			<div class="col-xs-12">
				<div id="yearly_hse"></div>
			</div>
		</div><!-- /.row -->

		<script>
		//var $j = jQuery.noConflict();
		$(document).ready(function () {
			//loaddashboard("rekap-production","recap_production","Loading Production ");
			//loaddashboard("grafik","grafik","Loading grafik ");
			loaddashboard("yearly_hse","yearly_hse","Loading Yearly HSE ");
		
		});
		</script>
			
<?php
}

?>

<script>

function loaddashboard(id_element,kategori,title){
	$("#"+id_element).html('<span style="margin-left:10px;">'+title+'</span><img src="<?php echo $theme_path."/images/h-loader.gif";?>" style="border: none; margin:5px 5px 5px 5px;opacity: 0.4;filter: alpha(opacity=40); height:10px;"  class="loading_'+kategori+'"/>');
	//alert("<?php echo $url_dashboard;?>/"+kategori);
	$('#'+id_element).load("<?php echo $url_dashboard;?>/"+kategori, function() {
		$(".loading_"+kategori).fadeOut();
	});
}

</script>