<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />

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
        <span class="label">Nama Obat</span>
        <?php 
			$nama=$detail->name;
		?>
        <input type="text" class="input" name="name" id="name" placeholder="Nama Obat"  size="35" value="<?php echo $nama;?>"/>
        
    </div>
  
     <div class="row-form">
        <span class="label" >Kategori</span>
       
        <select name="category" id="category" class="input" >
             <?php
            echo '<option value="">--Kategori--</option>';
            
                $List=$list_category;
                while($data = each($List)) {
					
                    $category=isset($_POST['category'])?$_POST['category']:$detail->category_code;
                   ?>
                    <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$category?"selected":""; ?> >
                 <?php echo $data['value'];?></option>
                 <?php
                }
            ?>
            </select>
    </div>
     <div class="row-form">
                 <span class="label" >Digunakan <small class="wajib"></small></span>
                <span style="display:inline-block">
                <?php
         
           	 	$aktif=$detail->active;//trim($detail->active)==""?0:$detail->active;
          		?>
                <label >  
                	<input type="radio"  name="aktif" value="1"  <?php echo ($aktif=="1")?"checked":"";?>  />
                          Ya</label> 
                <label  style="margin-left:10px;"> 
                	<input type="radio" name="aktif" value="0"  <?php echo ($aktif=="0")?"checked":"";?> />
                          Tidak </label>
                </span>
            </div>
  
</form>
</div>
