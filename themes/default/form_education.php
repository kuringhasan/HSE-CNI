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
 <form id="form_input_education"   method="post" >
 	 <div class="row-form">
                 <span class="label" >Jenjang <small class="wajib">*</small></span>
            	<select name="jenjang" class="input"  id="jenjang" >
                	
					 <?php
					 	$jenjang=$detail->jenjang;
                        echo '<option value="">--- Jenjang ---</option>';
                        $List=$list_jenjang;
                        while($data = each($List)) {
                           ?>
                    <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$jenjang?"selected":""; ?> >
                      <?php echo $data['value'];?></option>
                    <?php
                        }
                     ?>
              </select>
    </div>
    <div class="row-form">
        <span class="label">Universitas <small class="wajib">*</small></span>
       
        <input type="text" class="input" name="institusi" id="institusi" placeholder="Institusi"  size="35" value="<?php echo $detail->institusi;?>"/>
    </div>
    <div class="row-form">
             <span class="label" >Jurusan/Prodi </span>
            <input type="text" class="input" name="prodi" id="prodi" size="35" value="<?php echo $detail->jurusan;?>"/>
            </div>
     <div class="row-form">
             <span class="label" >Lokasi Institusi</span>
            <input type="text" class="input" name="lokasi" id="lokasi" size="30" value="<?php echo $detail->location;?>"/>
     </div>
    <div class="row-form">
             <span class="label" >Gelar </span>
            <input type="text" class="input" name="gelar" id="gelar" size="10" value="<?php echo $detail->gelar;?>"/>
     </div>
     <div class="row-form">
             <span class="label" >Posisi Gelar </span>
            <select name="posisi_gelar" class="input"  id="posisi_gelar" >
                	
					<option value="">-Posisi Gelar-</option>
                    <option value="depan"  <?php echo "depan"==$detail->posisi_gelar?"selected":""; ?> >Depan</option>
                    <option value="belakang"  <?php echo "belakang"==$detail->posisi_gelar?"selected":""; ?> >Belakang</option>
                    
              </select>
     </div>
    <div class="row-form">
         <span class="label" >Pendidikan Terakhir <small class="wajib"></small></span>
        <span style="display:inline-block">
       
        <input type="checkbox"  name="last_education" id="last_education"    <?php echo $detail->last_education=="1"?"checked=\"checked\"":""; ?>/>
           
        </span>
    </div>
    <div class="row-form">
             <span class="label" >Tahun Lulus </span>
            <input type="text" class="input" name="tahun" id="tahun" size="4" value="<?php echo $detail->graduation_year;?>"/>
     </div>
   
   
</form>
</div>
