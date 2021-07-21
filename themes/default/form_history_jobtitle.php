<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />

<!-- bootstrap datepicker -->
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/plugins/ajax-typeahead/js/bootstrap-typeahead.js"></script>
<script>
//var $m = jQuery.noConflict();
$(document).ready(function () {
	//alert('cek');
	    $("#tanggal_mulai").datepicker({
			 format: 'dd/mm/yyyy',
			 autoclose: true
		});
		$("#tanggal_akhir").datepicker({
			 format: 'dd/mm/yyyy',
			 autoclose: true
		});
		
		$("#form_input_jabatan").on('keydown.autocomplete', '#jabatan', function () {
		//$j(".search-by-anggota").on("keydown.autocomplete", function(e) {
				//alert("<?php echo $url_jsonData."";?>/job_title");								
			
				$(this).typeahead({
					ajax: { 
						url: "<?php echo $url_jsonData."";?>/job_title",
						timeout: 500,
						displayField: "Lengkap",
						valueField :'ID',
						triggerLength: 1,
						method: "POST",
						loadingClass: "loading-circle",
						preDispatch: function (query) {
						//	alert(query);
							//showLoadingMask(true);
							return {
								search: query
							}
						},
						preProcess: function (data) {
							//showLoadingMask(false);
							//alert(data.Lengkap);
							console.log(data);
							/*if (data.success === false) {
								// Hide the list, there was some error
								return false;
							}*/
							// We good!
							//return data.mylist;
							return data;
						}
					},
				   onSelect: function(item) {
						//alert(item.value);
						//var i =$j(this).attr('data-column');  // getting column index
						var v =item.value;  // getting search input value
						//table.columns(0).search(v).draw();
						$("#jabatan_id").val(item.value);
					}
				});
		});
		
});

    function setTextField(ddl) {
        document.getElementById('tipe_text').value = ddl.options[ddl.selectedIndex].text;
    }
</script>
<style>
.responsive-form #frmBulanLahir{
	margin-left:5px;
}
.responsive-form #frmTahunLahir{
	margin-left:5px;
}
@media screen and (max-width: 500px) {
	.responsive-form #frmBulanLahir{
		margin-left:0px;
	}
	.responsive-form #frmTahunLahir{
		margin-left:0px;
	}
}
@media screen and (max-width: 320px) {
	.responsive-form #frmBulanLahir{
		margin-left:0px;
	}
	.responsive-form #frmTahunLahir{
		margin-left:0px;
	}
}
</style>
<?php
//echo "<pre>";print_r($detail);"</pre>";
?>
<div class="responsive-form" >
 <form id="form_input_jabatan"   method="post" >
 	
    <div class="row-form">
        <span class="label">Jabatan <small class="wajib">*</small></span>
       
        <input type="text" class="input" name="jabatan" id="jabatan" placeholder="Jabatan"  size="35" value="<?php echo $detail->name;?>"/>
        <input type="text" class="hidden" name="jabatan_id" id="jabatan_id"  size="2" value="<?php echo $detail->job_title_id;?>"/>
    </div>
      <div class="row-form">
             <span class="label" >Bidang Garapan <small class="wajib">*</small></span>
            <input type="text" class="input" name="bidang_garapan" id="bidang_garapan" size="30" value="<?php echo $detail->bidang_garapan;?>"/>
     </div>
    <div class="row-form">
             <span class="label" >Mulai Berlaku </span>
            <input type="text" class="input" name="tanggal_mulai" id="tanggal_mulai" size="10" value="<?php echo $detail->tanggal_mulai;?>"/>
      </div>
     <div class="row-form">
             <span class="label" >Akhir Berlaku</span>
            <input type="text" class="input" name="tanggal_akhir" id="tanggal_akhir" size="10" value="<?php echo $detail->tanggal_akhir;?>"/>
     </div>
   
    <div class="row-form">
         <span class="label" >Sedang Menjabat <small class="wajib"></small></span>
        <span style="display:inline-block">
       
        <input type="checkbox"  name="current_job_title" id="current_job_title"    <?php echo $detail->current_job_title=="1"?"checked=\"checked\"":""; ?>/>
           
        </span>
    </div>
    <div class="row-form">
             <span class="label" >Keterangan </span>
             <textarea id="keterangan" name="keterangan" rows="2" cols="35" class="Pernyataan" ><?php echo $detail->keterangan;?></textarea>
     </div>
   
   
</form>
</div>
