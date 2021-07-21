<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />

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
 <form id="form_input_working"   method="post" >
 	 
    <div class="row-form">
        <span class="label">Institusi/Perusahaan <small class="wajib">*</small></span>
       
        <input type="text" class="input" name="company" id="company" placeholder="Company"  size="35" value="<?php echo $detail->company;?>"/>
    </div>
    <div class="row-form">
             <span class="label" >Location <small class="wajib">*</small></span>
            <input type="text" class="input" name="location" id="location" size="35" value="<?php echo $detail->location;?>"/>
     </div>
     <div class="row-form">
             <span class="label" >Bidang Garapan <small class="wajib">*</small></span>
            <input type="text" class="input" name="bidang_garapan" id="bidang_garapan" size="30" value="<?php echo $detail->bidang_garapan;?>"/>
     </div>
    <div class="row-form">
             <span class="label" >jabatan </span>
            <input type="text" class="input" name="jabatan" id="jabatan" size="35" value="<?php echo $detail->jabatan;?>"/>
            </div>
     
     <div class="row-form">
				 <span class="label" >Tahun </span>
				<input type="text" class="input" name="start_year" id="start_year" size="3" value="<?php echo $detail->start_year;?>" style="margin-right:3px;width:auto" placeholder="Start"/> <span style="display:inline-block;float:left;margin-left:4px;margin-right:4px;">-</span>
				
				<input type="text" class="input" name="end_year" id="end_year" size="3" value="<?php echo $detail->end_year;?>" placeholder="End"/>
	</div>
    
   
   
</form>
</div>
