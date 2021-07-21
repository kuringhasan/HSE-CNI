<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
 <link rel="stylesheet" href="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>

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
        <span class="label">Nama Barge</span>: <?php echo $detail->name;?>
       
    </div>
    
    <div class="row-form">
        <span class="label">Kapasitas</span>: <?php echo number_format($detail->capacity,2,",",".");?>
        
    </div>
     <div class="row-form">
        <span class="label">Color (RGB)</span>: <?php echo "<span  style=\"color:".$detail->rgb_color.";\">".$detail->rgb_color."</span>";?>
        
    </div>
     <div class="row-form">
                 <span class="label" >Aktif </span>: <?php echo $detail->is_active=="1"?"Ya":"Tidak";?>
               
            </div>
  
</form>
</div>
