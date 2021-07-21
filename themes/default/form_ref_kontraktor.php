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

<div class="responsive-form" >
 <form id="form_input_data"   method="post" >
    <div class="row-form">
        <span class="label">NIK<small class="wajib">*</small></span>
       
        <input type="text" class="input" name="nik" id="nik"   size="35" value="<?php echo $detail->nik;?>"/>
        
    </div>
     <div class="row-form">
        <span class="label">Nama<small class="wajib">*</span>
       
        <input type="text" class="input" name="name" id="name"   size="35" value="<?php echo $detail->name;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">is company?</span>
       
        <input type="checkbox" name="is_company" id="is_company"   size="35" <?php if($detail->is_company==1){echo "checked";}  ?>/>
        
    </div>
    <div class="row-form">
        <span class="label">is company?</span>
       
        <input type="checkbox" name="is_contractor" id="is_contractor"   size="35" <?php if($detail->is_contractor==1){echo "checked";}  ?>/>
        
    </div>
    <div class="row-form">
        <span class="label">Code</span>
       
        <input type="text" class="input" name="code" id="code"   size="35" value="<?php echo $detail->code;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">No KK</span>
       
        <input type="text" class="input" name="no_kk" id="no_kk"   size="35" value="<?php echo $detail->no_kk;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Alias</span>
       
        <input type="text" class="input" name="alias" id="alias"   size="35" value="<?php echo $detail->alias;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Gelar Depan</span>
       
        <input type="text" class="input" name="gelar_depan" id="gelar_depan"   size="35" value="<?php echo $detail->gelar_depan;?>"/>
        
    </div>
        <div class="row-form">
        <span class="label">Gelar Belakang</span>
       
        <input type="text" class="input" name="gelar_belakang" id="gelar_belakang"   size="35" value="<?php echo $detail->gelar_belakang;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Tempat Lahir</span>
       
        <input type="text" class="input" name="tempat_lahir" id="tempat_lahir"   size="35" value="<?php echo $detail->tempat_lahir;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Tempat Lahir Lain</span>
       
        <input type="text" class="input" name="tempat_lahir_lain" id="tempat_lahir_lain"   size="35" value="<?php echo $detail->tempat_lahir_lain;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Tanggal Lahir</span>
       
        <input type="text" class="input" name="tanggal_lahir" id="tanggal_lahir"   size="35" value="<?php echo $detail->tanggal_lahir;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Agama</span>
       
        <input type="text" class="input" name="agama" id="agama"   size="35" value="<?php echo $detail->agama;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Gender</span>
       
        <input type="text" class="input" name="gender" id="gender"   size="35" value="<?php echo $detail->gender;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Kewarganegaraan</span>
       
        <input type="text" class="input" name="kewarganegaraan" id="kewarganegaraan"   size="35" value="<?php echo $detail->kewarganegaraan;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Golongan Darah</span>
       
        <input type="text" class="input" name="golongan_darah" id="golongan_darah"   size="35" value="<?php echo $detail->golongan_darah;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Jenis Tnd Pengenal</span>
       
        <input type="text" class="input" name="pJenisTandaPengenal" id="pJenisTandaPengenal"   size="35" value="<?php echo $detail->pJenisTandaPengenal;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Alamat</span>
       
        <input type="text" class="input" name="alamat" id="alamat"   size="35" value="<?php echo $detail->alamat;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Alamat RT</span>
       
        <input type="text" class="input" name="alamat_rt" id="alamat_rt"   size="35" value="<?php echo $detail->alamat_rt;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Alamat RW</span>
       
        <input type="text" class="input" name="alamat_rw" id="alamat_rw"   size="35" value="<?php echo $detail->alamat_rw;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Phone</span>
       
        <input type="text" class="input" name="phone" id="phone"   size="35" value="<?php echo $detail->phone;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Alamat Kecamatan</span>
       
        <input type="text" class="input" name="alamat_kecamatan" id="alamat_kecamatan"   size="35" value="<?php echo $detail->alamat_kecamatan;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Alamat Desa</span>
       
        <input type="text" class="input" name="alamat_desa" id="naalamat_desame"   size="35" value="<?php echo $detail->alamat_desa;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Alamat Kabupaten</span>
       
        <input type="text" class="input" name="alamat_kabupaten" id="alamat_kabupaten"   size="35" value="<?php echo $detail->alamat_kabupaten;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Email</span>
       
        <input type="text" class="input" name="email" id="email"   size="35" value="<?php echo $detail->email;?>"/>
        
    </div>
    <!-- <div class="row-form">
        <span class="label">File Foto</span>
       
        <input type="file" class="input" name="file_foto" id="file_foto"   size="35" value="<?php echo $detail->file_foto;?>"/>
        
    </div> -->
    <div class="row-form">
        <span class="label">Kode Pos</span>
       
        <input type="text" class="input" name="kodepos" id="kodepos"   size="35" value="<?php echo $detail->kodepos;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Telepon</span>
       
        <input type="text" class="input" name="telepon" id="telepon"   size="35" value="<?php echo $detail->telepon;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">NPWP</span>
       
        <input type="text" class="input" name="npwp" id="npwp"   size="35" value="<?php echo $detail->npwp;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Step</span>
       
        <input type="text" class="input" name="step" id="step"   size="35" value="<?php echo $detail->step;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Reg Step</span>
       
        <input type="text" class="input" name="reg_step" id="reg_step"   size="35" value="<?php echo $detail->reg_step;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Reg Date</span>
       
        <input type="text" class="input" name="reg_data" id="reg_data"   size="35" value="<?php echo $detail->reg_data;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Status Pernikahan</span>
       
        <input type="text" class="input" name="status_pernikahan" id="status_pernikahan"   size="35" value="<?php echo $detail->status_pernikahan;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Nama Pasangan</span>
       
        <input type="text" class="input" name="nama_pasangan" id="nama_pasangan"   size="35" value="<?php echo $detail->nama_pasangan;?>"/>
        
    </div>
    <div class="row-form">
        <span class="label">Active?</span>
       
        <Select class="input" name="active">
            <option value="">--Pilih--</option>
            <option value="active">active</option>
            <option value="archived">archived</option>
        </Select>
        
    </div>
</form>
</div>
