<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
 <link rel="stylesheet" href="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<script>
$(document).ready(function () {
	
	$('.my-colorpicker1').colorpicker();
});
function errorForm(msj_obj){
	 if (jQuery.isEmptyObject(msj_obj)==false)
	 {
		 var errors=msj_obj;
		 $('.row-error').remove();
		for (var key in errors){
			$("#"+key).addClass("error");
		
			$("#"+key).parent().closest('div').after( " <div class=\"row-form row-error\"><span class=\"label\" ></span><span class=\"lbl_error\">"+errors[key]+"</span></div>" );
			
			
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
        <span class="label">Nama Barge</span>
        <?php 
			$nama=$detail->name;
		?>
        <input type="text" class="input" name="name" id="name" placeholder="Nama"  size="35" value="<?php echo $nama;?>"/>
        
    </div>
    
    <div class="row-form">
        <span class="label">Kapasitas</span>
        <?php 
			$capacity=number_format($detail->capacity,2,",",".");
		?>
        <input type="text" class="input" name="capacity" id="capacity"  size="8" value="<?php echo $capacity;?>"/> MT
        
    </div>
     <div class="row-form">
        <span class="label">Color (RGB)</span>
        <?php 
			$rgb_color=$detail->rgb_color;
		?>
        <input type="text" class="input my-colorpicker1 colorpicker-element" name="rgb_color" id="rgb_color"  size="20" value="<?php echo $rgb_color;?>"/> 
        
    </div>
     <div class="row-form">
                 <span class="label" >Aktif <small class="wajib"></small></span>
                <span style="display:inline-block">
                <?php
         
           	 	$aktif=$detail->is_active;//trim($detail->active)==""?0:$detail->active;
          		?>
                <label >  
                	<input type="radio"  name="is_active" value="1"  <?php echo ($aktif=="1")?"checked":"";?>  />
                          Ya</label> 
                <label  style="margin-left:10px;"> 
                	<input type="radio" name="is_active" value="0"  <?php echo ($aktif=="0")?"checked":"";?> />
                          Tidak </label>
                </span>
            </div>
  
</form>
</div>
