<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/plugins/ajax-typeahead/js/bootstrap-typeahead.js"></script>
<script>
//var $j = jQuery.noConflict();
$(document).ready(function () {
	$('#entry_manual').on('change', function(){ // on change of state
	   if(this.checked) // if changed state is "CHECKED"
		{
			// do the magic here
			$('.manual-password').removeClass('hide').addClass('show');
			$('#new_generate_password').addClass('coret');
			
		}else{
			$('.manual-password').removeClass('show').addClass('hide');
			
			$('#new_generate_password').removeClass('coret');
		}
	});
		
 
});

    function setTextField(ddl) {
        document.getElementById('tipe_text').value = ddl.options[ddl.selectedIndex].text;
    }
function errorForm(msj_obj){
	 if (jQuery.isEmptyObject(msj_obj)==false)
	 {
		 var errors=msj_obj;
		 $j('.row-error').remove();
		for (var key in errors){
			$("#"+key).addClass("error");
			alert(key);
			if(key=='konfirmasi_ritase'){
				$("#"+key).parent().closest('div').after( " <div class=\"row-form row-error\"><span class=\"lbl_error\">"+errors[key]+"</span></div>" );
			}else{
				$("#"+key).parent().closest('div').after( " <div class=\"row-form row-error\"><span class=\"label\" ></span><span class=\"lbl_error\">"+errors[key]+"</span></div>" );
			}
			
		}
	 }
}	
</script>
<style>
.responsive-form #frmBulanLahir{
	margin-left:5px;
}
.responsive-form #frmTahunLahir{
	margin-left:5px;
}
.dropdown-menu li{
	width:350px;
	border-bottom:1px solid #999;
	background-color:#ddd;
}
.list-item{
	width:100%;
}

.hide{
	display:none;
}
.show{
	display:block;
}
.coret{
	text-decoration:line-through;
	color:#CCC;
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
        <span class="label">Password Baru</span>
       <span id="new_generate_password"><?php echo $new_password;?></span>
         <input type="hidden" name="password_generate" id="password_generate" value="<?php echo $new_password;?>"  size="4"/>
    </div>
   <div class="row-form">
         <span class="label" >Isi Manual<small class="wajib"></small></span>
        <span style="display:inline-block">
       <input type="checkbox" name="entry_manual" id="entry_manual"> 
        </span>
    </div>
    
    
    <div class="row-form manual-password hide">
        <span class="label">Password Baru</span>
        <?php 
			$nama=$detail->name;
		?>
        <span id="label_password"></span>
        <input type="password" class="input" name="password" id="password" size="12" value="<?php echo $nama;?>"/>
      
    </div>
     <div class="row-form manual-password hide">
        <span class="label">Re-type Password</span>
        <?php 
			$nama=$detail->name;
		?>
        <input type="password" class="input" name="password2" id="password2" size="12" value="<?php echo $nama;?>"/>
        
    </div>
   
   
  
</form>
</div>
