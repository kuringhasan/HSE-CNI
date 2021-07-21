<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $theme_path;?>js/accounting.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/plugins/ajax-typeahead/js/bootstrap-typeahead.js"></script>
<script>
$j(document).ready(function () {
	
	
	$j("#tpk").change(function(){		
		var parentkode=	$(this).val();	
		
		comboAjax('<?php echo $url_comboAjax;?>/listkelompok',parentkode,'kelompok','','','loaderKelompok');	
	}); 
	
 
});

function errorForm(msj_obj){
	 if (jQuery.isEmptyObject(msj_obj)==false)
	 {
		 var errors=msj_obj;
		for (var key in errors){
			$("#"+key).addClass("error");
			$("#"+key).parent().closest('div').after( " <div class=\"row-form row-error\"><span class=\"label\" ></span><span class=\"lbl_error\">"+errors[key]+"</span></div>" );
			
		}
	 }
}
</script>
<style>

.label{
	font-weight:normal;
}
label{
	font-weight:normal;
}
.bulan{
	margin-left:5px;
}
.tahun{
	margin-left:5px;
}
@media screen and (max-width: 500px) {
	.bulan{
		margin-left:0px;
	}
	.tahun{
		margin-left:0px;
	}
}
@media screen and (max-width: 320px) {
	
	.bulan{
		margin-left:0px;
	}
	.tahun{
		margin-left:0px;
	}
}
</style>
<?php
//echo "<pre>";print_r($detail);"</pre>";
?>
<div class="responsive-form" >
 <form id="form_input_data"   method="post" >
 
    
 	<div class="row-form">
        <span class="label" >Key<small class="wajib"></small></span> 
       
       <input type="text" class="input" name="frm_key" id="frm_key" value="<?php echo $detail->settingKey;?>" size="20" />
    </div> 
     <div class="row-form">
        <span class="label">Kategori</span>
        <?php $frm_kategori=isset($_POST['frm_kategori'])?$_POST['frm_kategori']:$detail->settingKategori;?>
            <select name="frm_kategori" id="frm_kategori" class="input">
             <option value="">--kategori--</option>
              <option value="keswan"  <?php echo $frm_kategori=="keswan"?"selected":""; ?> >Keswan</option>
              <option value="sync_odoo"  <?php echo $frm_kategori=="sync_odoo"?"selected":""; ?> >Sync Odoo</option>
            </select>
    </div>
     <div class="row-form">
        <span class="label">Nilai/Value</span>
        <span style="display:inline-block" id="html-privileges"><?php echo $detail->settingHtmlValue;?></span>
        
    </div>
   
  
   
</form>
</div>
