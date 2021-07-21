<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />

<script>
$(document).ready(function () {
		$("#berlaku_mulai").datepicker({
			 format: 'dd/mm/yyyy',
			 autoclose: true
		});
		$("#berlaku_sampai").datepicker({
			 format: 'dd/mm/yyyy',
			 autoclose: true
		});
});
function errorForm(msj_obj){
	 if (jQuery.isEmptyObject(msj_obj)==false)
	 {
		 var errors=msj_obj;
		for (var key in errors){
			$("#"+key).addClass("error");
			$("#err_"+key).html(errors[key]);
			$("#err_"+key).addClass("lbl_error");
			$("#err_"+key).show();
		}
	 }
}
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
//echo "<pre>";print_r($detail);echo "</pre>";
?>
<div class="responsive-form" >
 <form id="form_input_data"   method="post" >
 	
    <div class="row-form">
        <span class="label">Berlaku Mulai<small class="wajib">*</small></span>
        <input type="text" class="input" name="berlaku_mulai" id="berlaku_mulai" placeholder="Mulai"  size="8" value="<?php echo $detail->berlaku_mulai2;?>"/>
    </div>
   <div class="row-form">
        <span class="label">Berlaku Sampai<small class="wajib">*</small></span>
        <input type="text" class="input" name="berlaku_sampai" id="berlaku_sampai" placeholder="Sampai"  size="8" value="<?php echo $detail->berlaku_sampai2;?>"/>
    </div>
     <div class="row-form">
        <span class="label">Load Factor EX-PIT<small class="wajib">*</small></span>
        <input type="text" class="input" name="load_factor_expit" id="load_factor_expit"  size="4" value="<?php echo $detail->load_factor_expit;?>"/>
    </div>
      <div class="row-form">
        <span class="label">Load Factor Barging<small class="wajib">*</small></span>
        <input type="text" class="input" name="load_factor_barging" id="load_factor_barging"  size="4" value="<?php echo $detail->load_factor_barging;?>"/>
    </div>
     <div class="row-form">
                 <span class="label" >Closed <small class="wajib"></small></span>
                <select name="closed" id="closed"  class="input" style=";">
        <option value="">--closed--</option>
        <option value="0" <?php echo "0"==$detail->closed?"selected":""; ?>>Belum</option>
        <option value="1" <?php echo "1"==$detail->closed?"selected":""; ?>>Ya</option>
          </select>
            </div>
  
</form>
</div>
