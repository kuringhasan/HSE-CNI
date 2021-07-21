<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>
<script>

$(document).ready(function () {
 
	loaddashboard("rekap-populasi","rekap_populasi","Loading populasi ");
	loaddashboard("grafik-pelayanan","grafik_pelayanan","Loading grafik ");
});
function loaddashboard(id_element,kategori,title){
	$("#"+id_element).html('<span style="margin-left:10px;">'+title+'</span><img src="<?php echo $theme_path."/images/h-loader.gif";?>" style="border: none; margin:5px 5px 5px 5px;opacity: 0.4;filter: alpha(opacity=40); height:10px;"  class="loading_'+kategori+'"/>');
	$('#'+id_element).load("<?php echo $url_dashboard;?>/"+kategori, function() {
		$(".loading_"+kategori).fadeOut();
	});
}
function perbarui(kategori){
		$(".spinner-dashboard-"+kategori).show();
		$.ajax({
			type:"POST",
			url: "<?php echo $url_refresh;?>",
			data:"",
			success: function(data, status) {
				$(".spinner-dashboard-"+kategori).hide();
				window.location ="";
				/*var obj2 = JSON.parse(data);
				if (obj2.success==true){
					alert(obj2.message);
				}else{
					alert(obj2.message);
				}*/
				
			}
		});	
 }
</script>
<div class="row" id="rekap-populasi">

</div><!-- /.row -->

      <!-- /.row -->
<div class="row" id="grafik-pelayanan">
      
</div><!-- /.row -->

  


 <form method="post" id="form-download"  target="media-download">
 <input type="hidden" name="dw_tahun" id="dw_tahun" size="3" />
 <input type="hidden" name="dw_tpk" id="dw_tpk" size="3" />
 </form>
<iframe name="media-download" style="display:none;" ></iframe>