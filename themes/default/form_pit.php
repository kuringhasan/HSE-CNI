<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />

<script>

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
        <span class="label">Nama PIT</span>
        <?php 
			$nama=$detail->name;
		?>
        <input type="text" class="input" name="name" id="name" placeholder="Nama PIT"  size="35" value="<?php echo $nama;?>"/>
        
    </div>
  
     <div class="row-form">
        <span class="label" >Kontraktor</span>
       
        <select name="frm_contractor" data-column="1" class="input" id="frm_contractor" >
                          <?php
                        echo '<option value="">-- Kontraktor --</option>';
                        $List=$list_contractor;
                        while($data = each($List)) {
                           ?>
                      <option value="<?php echo $data['key'];?>" ><?php echo $data['value'];?></option>
                      <?php
                      
                        }
                     ?>
              		</select>
    </div>
      <div class="row-form">
        <span class="label">Kordinat</span>
        <?php $koordinat=isset($_POST['frm_kordinat'])?$_POST['frm_kordinat']:$detail->koordinat;?>
        <textarea name="frm_kordinat" id="frm_kordinat" class="input" cols="35" rows="2" ><?php echo $koordinat;?></textarea>
    </div>
      <div class="row-form">
        <span class="label">Kordinat Area</span>
        <?php $area=isset($_POST['frm_area'])?$_POST['frm_area']:$detail->area;?>
        <textarea name="frm_area" id="frm_area" class="input" cols="35" rows="4" ><?php echo $area;?></textarea>
    </div>
     <div class="row-form">
                 <span class="label" >Status <small class="wajib"></small></span>
                <select name="status" id="status"  class="input" style=";">
        <option value="">--status--</option>
        <option value="prospect">Prospect</option>
        <option value="active">Aktif</option>
        <option value="mined_out">Mined Out</option>
          </select>
            </div>
  
</form>
</div>
