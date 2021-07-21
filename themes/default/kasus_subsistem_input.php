<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $theme_path;?>js/jquery-1.12.1.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>js/accounting.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>bootstrap/bootstrap/js/typeahead.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>bootstrap/bootstrap/js/hogan-3.0.0.min.js"></script>
<script>
$(document).ready(function () {
	
	
	
 
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

</script>
<style>

</style>
<?php
 //echo "<pre>";print_r($detail); echo "</pre>";
?>
<div class="responsive-form" >
 <form id="form_input_data"   method="post" >
  	<div class="row-form" >
       <span class="label">ID</span>
        <input type="text" class="input" name="frmKode" id="frmKode" size="20" value="<?php echo $detail->SubID;?>" />
    </div>
 	  <div class="row-form">
            <span class="label">Nama Subsistem</span>
            <?php $nama=isset($_POST['frmNama'])?$_POST['frmNama']:$detail->SubNama;?>
            
         
            <input type='text' class="input" name="frmNama" id="frmNama" placeholder="Nama" size="35" value="<?php echo $nama;?>"/>
           
        </div>
     <!--<div class="row-form" >
       <span class="label">Urutan</span>
        <input type="text" class="input" name="frmUrutan" id="frmUrutan" size="1" value="<?php echo $detail->krUrutan;?>" />
    </div>-->
     
  
    <div class="row-form" >
        <span class="label">Keterangan</span>
      
        <textarea class="input" name="frmKeterangan" id="frmKeterangan" cols="35" rows="2"><?php echo $detail->SubKeterangan;?></textarea>
    </div>
   
    
</form>
</div>
