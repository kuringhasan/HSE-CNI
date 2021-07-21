 <script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>
<?php
if($current_level->Unit=="" or $current_level->Unit=="05"){
?>
<div class="row" id="rekap-finance">

</div><!-- /.row -->
<script>
//var $j = jQuery.noConflict();
$(document).ready(function () {
// alert('cek');
	loaddashboard("rekap-finance","monthly_budgeting","Loading Finance ");

});
</script>

<?php
}
if($current_level->Unit=="" or $current_level->Unit=="02"){
?>
	 <div class="row" id="daily_production">
          
    </div><!-- /.row -->
    
    <div class="row" id="rekap-production">
    
    </div><!-- /.row -->


    <div class="row" id="grafik">
    
    </div><!-- /.row -->
    
          <!-- /.row -->
    <div class="row" id="weekly_production">
          
    </div><!-- /.row -->
    <script>
	//var $j = jQuery.noConflict();
	$(document).ready(function () {
		loaddashboard("daily_production","daily_production","Loading Daily Production ");
		loaddashboard("rekap-production","recap_production","Loading Production ");
		loaddashboard("grafik","grafik","Loading grafik ");
		loaddashboard("weekly_production","weekly_production","Loading Weekly Production ");
	
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