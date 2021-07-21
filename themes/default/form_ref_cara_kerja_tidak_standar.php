<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />

<!-- bootstrap datepicker -->
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/plugins/ajax-typeahead/js/bootstrap-typeahead.js"></script>
<script>
    
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

</script>
<style>

</style>
<?php
// echo "<pre>";print_r($detail);echo "</pre>";
?>
<div id="alert" class="alert alert-danger" style="display: none;"></div>
<div class="responsive-form" >
 <form id="form_input_data"   method="post" >
    <div class="row-form">
        <span class="label">Kode<small class="wajib">*</small></span>
       
        <input required type="text" class="input" name="kode" id="kode"   size="35" value="<?php echo $detail->kode;?>"/>
        
    </div>
     <div class="row-form">
        <span class="label">Nama<small class="wajib">*</span>
       
        <input required type="text" class="input" name="nama_cara_kerja" id="nama_cara_kerja"   size="35" value="<?php echo $detail->nama_cara_kerja;?>"/>
        
    </div>
</form>
</div>
