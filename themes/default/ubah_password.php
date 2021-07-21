<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />

<div class="box box-solid">
    <div class="box-body">
	<?php 
	$pesan=array();
	if(isset($Hasil)){
		//echo "<pre>";	print_r($Hasil);echo "</pre>";
		
			if ($Hasil['success']==true){
				echo "<div class=\"lbl_sukses\" style=\"padding:5px 5px 5px 5px;margin:5px 5px 8px 5px;\">".$Hasil['message']."</div>";
			}
			if ($Hasil['success']==false and trim($Hasil['message'])<>""){
				$pesan=$Hasil['form_error'];	
				echo "<div class=\"error lbl_error\" style=\"padding:5px 5px 5px 5px;margin:5px 5px 8px 5px;\">".$Hasil['message']."</div>";
			}
	}
	//echo "<pre>";	print_r($pesan);echo "</pre>";
	if(!isset($Hasil) or $Hasil['success']==false){
	?>
	<div class="responsive-form" >
	<style>
	.responsive-form .label{
		width:200px;
	}
	</style>
	<form action="" method="post" class="form" >
		 <div class="row-form">
			<span class="label">Password Lama</span>
			<input type="password" name="password_heubeul" id="password_heubeul" size="20"  class="input <?php echo (isset($pesan['password_heubeul']) and trim($pesan['password_heubeul'])<>"")?" error":"";?>"  /><span class="lbl_error"><?php echo is_array($Hasil['pesan'])?$Hasil['pesan']['password_heubeul']:"";?></span>
		</div> 
		 <div class="row-form">
			<span class="label">Password Baru </span>
			<input type="password" name="password_anyar1" size="20"  class="input <?php echo (isset($pesan['password_anyar1']) and trim($pesan['password_anyar1'])<>"")?" error":"";?>"  /><span class="lbl_error"><?php echo is_array($Hasil['pesan'])?$Hasil['pesan']['password_anyar1']:"";?></span>
		</div> 
		 <div class="row-form">
			<span class="label">Ketik Ulang Password Baru </span>
			<input type="password" name="password_anyar2" size="20"  class="input <?php echo (isset($pesan['password_anyar2']) and trim($pesan['password_anyar2'])<>"")?" error":"";?>"  /><span class="lbl_error"><?php echo is_array($Hasil['pesan'])?$Hasil['pesan']['password_anyar2']:"";?></span>
		</div> 
		 <div class="row-form">
			<span class="label"> </span>
		  
			<button type="submit" name="simpan" id="simpan"   class="btn btn-primary btn-xs"  value="simpan"   ><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
		</div> 
	
	
	</form>
	
	</div>
    <?php }?>
</div>
</div>
	<script>
		$(function() {
	
			$("#simpan").button({
				icons: {
					primary: 'ui-icon-disk'
				}
			}).click(function(){
			});	
	   
			<?php
			if(strlen($notice)) {
			?>
				$("#notice-modal").dialog({
					height: 140,
					modal: true,
					buttons: {
						Ok: function() {
							$(this).dialog('close');
						}
					}
				});
				<?php
			}
			?>
	
		 });
	</script>