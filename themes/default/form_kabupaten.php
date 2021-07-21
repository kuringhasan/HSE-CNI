<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />

<!-- bootstrap datepicker -->
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/plugins/ajax-typeahead/js/bootstrap-typeahead.js"></script>
<script>
    
    function errorForm(msj_obj){
            if (jQuery.isEmptyObject(msj_obj)==false)
            {
                var errors=msj_obj;
                $m('.row-error').remove();
                for (var key in errors){
                    $m("#"+key).addClass("error");
                
                    $m("#"+key).parent().closest('div').after( " <div class=\"row-form row-error\"><span class=\"label\" ></span><span class=\"lbl_error\">"+errors[key]+"</span></div>" );
                    
                    
                }
            }
            
    }

    // function setTextField(ddl) {
    //     document.getElementById('tipe_text').value = ddl.options[ddl.selectedIndex].text;
    // }
</script>
<style>
/* .responsive-form #frmBulanLahir{
	margin-left:5px;
}
.responsive-form #frmTahunLahir{
	margin-left:5px;
}
.wajib{
	color:#F00;
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
} */
</style>
<?php
// echo "<pre>";print_r($detail);echo "</pre>";
?>

<div class="responsive-form" >
 <form id="form_input_data"   method="post" >
 	
     <div class="row-form">
        <span class="label" >Provinsi <small class="wajib">*</small></span>
       
        <select name="provinsi" id="provinsi" class="input" >
                <?php
                echo '<option value="">--Provinsi--</option>';
            
                $List=$list_provinsi;
                while($data = each($List)) {
					
                    $provinsi=isset($_POST['provinsi'])?$_POST['provinsi']:$detail->kabupatenPropinsiKode;
                    ?>
                    <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$detail->kabupatenPropinsiKode?"selected":""; ?> >
                    <?php echo $data['value'];?>
                    </option>
                <?php
                }
                ?>
        </select>
    </div>
    <div class="row-form">
        <span class="label">Kode Kabupaten<small class="wajib">*</small></span>
       
        <input type="text" class="input" name="code" id="code" placeholder="Kode"  size="35" value="<?php echo $detail->kabupatenKode;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Jenis Kabupaten<small class="wajib">*</span>
       
        <input type="text" class="input" name="jenis" id="jenis" placeholder="Jenis"  size="35" value="<?php echo $detail->kabupatenJenis;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Jenis Singkat<small class="wajib">*</span>
       
        <input type="text" class="input" name="jenis_singkat" id="jenis_singkat" placeholder="Jenis Singkat"  size="35" value="<?php echo $detail->kabupatenJenisSingkat;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Nama Kabupaten<small class="wajib">*</span>
       
        <input type="text" class="input" name="name" id="name" placeholder="Nama"  size="35" value="<?php echo $detail->kabupatenNamaSaja;?>"/>
        
    </div>
</form>
</div>
