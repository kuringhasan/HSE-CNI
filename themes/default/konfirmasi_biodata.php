<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $theme_path;?>js/jquery-1.12.1.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>js/accounting.js"></script>

<style>

.responsive-form .label{
	min-width:195px;
	
}
.label_typeahead{
	width:300px;
	height:auto;
	white-space:pre-line;
	background-color:#06F
	margin:0px 0px 0px 0px;
}
.wajib{
	color:#F00;
}
.catatan{
	display:inline-block;
	font-size:0.7em;
}
</style>
<?php  //echo "<pre>"; print_r($profil);echo "</pre>"; ?>
 <div class="box box-solid">
        <div class="box-body"  style="font-size:1.2em;font-weight:bold;text-align:center;line-height:1em;text-transform:uppercase;">
           <span>Biodata</span>
            
        </div>
    </div>
 <div class="box box-solid">
    <div class="box-body">
        <div class="responsive-form" >
      
        	<div class="row-form">
             <span class="label" >Tanda Pengenal </span>: KTP
            </div>
        	<div class="row-form">
             <span class="label" >No. Identitas </span>: <?php echo $profil->mhsRegNomorIdentitas;?>
            </div>
            <div class="row-form">
            	<span class="label">Provinsi Lahir</span>: <?php echo $profil->NamaPropinsiLahir;?>
				
             </div>
              <div class="row-form">
              <span class="label">Kab./Kota Lahir </span>: <?php echo $profil->NamaKabupatenLahir;?>
			
              </div>
            <div class="row-form">
             <span class="label" >Tempat Lahir</span>: <?php echo $profil->mhsTempatLahir;?>
            </div>
            <div class="row-form">
             <span class="label" >Tanggal Lahir </span>: <?php echo $profil->mhsTanggalLahir;?>
           
            </div>
          
            <div class="row-form">
                 <span class="label" >Jenis Kelamin </span>: <?php echo $profil->Kelamin;?>
                
            </div>
            <div class="row-form" >
                <span class="label">Golongan Darah</span>: <?php echo $profil->mhsGolonganDarah;?>
               
            </div>
            <div class="row-form" >
                <span class="label">Agama</span>: <?php echo $profil->Agama;?>
              
            </div>
              <div class="row-form">
                 <span class="label" >Kewarganegaraan</span>: <?php echo $profil->mhsKewarganegaraan;?>
            	
            </div>
            <div class="row-form">
                 <span class="label" >E-Mail </span>: <?php echo $profil->mhsAlamatEmail;?>
            </div>
            <div class="row-form">
                 <span class="label" >Handphone </span>: <?php echo $profil->mhsAlamatHandPhone;?>
            </div>
     
        	 <div class="row-form">
                 <span class="label" style="width:auto" ><strong>Alamat Rumah/Asal</strong></span>
             </div>
        	 <div class="row-form">
                 <span class="label" >Provinsi</span>: <?php echo $profil->NamaPropinsiAsal;?>
            	
           </div>
          <div class="row-form">
              <span class="label">Kab./Kota </span>: <?php echo $profil->NamaKabupatenAsal;?>
              
           </div>
           <div class="row-form">
              <span class="label">Alamat</span>: <?php echo $profil->mhsAlamatAsalJalan;?>
              
           </div>
            <div class="row-form">
              <span class="label">RT/RW</span>: <?php echo $profil->mhsAlamatAsalRT;?> / <?php echo $profil->mhsAlamatAsalRW;?>
              
           </div>
           <div class="row-form">
              <span class="label">Kelurahan/Desa</span>: <?php echo $profil->mhsAlamatAsalKelurahan;?>
           </div>
           <div class="row-form">
              <span class="label">Kecamatan</span>: <?php echo $profil->mhsAlamatAsalKecamatan;?>
           </div>
           <div class="row-form">
              <span class="label">Kodepos</span>: <?php echo $profil->mhsAlamatAsalKodePos;?>
           </div>
            <div class="row-form">
              <span class="label">Telepon</span>: <?php echo $profil->mhsAlamatAsalTeleponRumah;?>
           </div>
           <div class="row-form">
                 <span class="label" style="width:auto" ><strong>Alamat Di Bandung</strong></span>
               
             </div>
        	 <div class="row-form">
                 <span class="label" >Provinsi</span>: <?php echo $profil->NamaPropinsiBandung;?>
           </div>
          <div class="row-form">
              <span class="label">Kab./Kota </span>: <?php echo $profil->NamaKabupatenBandung;?>
                
           </div>
           <div class="row-form">
              <span class="label">Alamat</span>: <?php echo $profil->mhsAlamatBandungJalan;?>
          
           </div>
            <div class="row-form">
              <span class="label">RT/RW</span>: <?php echo $profil->mhsAlamatBandungRT;?> / <?php echo $profil->mhsAlamatBandungRW;?>
             
           </div>
           <div class="row-form">
              <span class="label">Kelurahan/Desa</span>: <?php echo $profil->mhsAlamatBandungKelurahan;?>
           </div>
           <div class="row-form">
              <span class="label">Kecamatan</span>: <?php echo $profil->mhsAlamatBandungKecamatan;?>
           </div>
           <div class="row-form">
              <span class="label">Kodepos</span>: <?php echo $profil->mhsAlamatBandungKodePos;?>
           </div>
           <div class="row-form">
              <span class="label">Telepon</span>: <?php echo $profil->mhsAlamatBandungTeleponRumah;?>
           </div>
       		 <div class="row-form">
                 <span class="label" style="width:auto" ><strong>Sekolah Asal</strong></span>
             </div>
        	 <div class="row-form">
                 <span class="label" >Jenjang Pendidikan </span>: <?php echo $profil->NamaJenjangSekolahAsal;?>
            
           </div>
        	<div class="row-form">
              <span class="label">Sekolah Asal</span>: <?php echo $profil->mhsSekolahAsalNama;?>
            </div>
            <div class="row-form">
              <span class="label">Alamat</span>: <?php echo $profil->mhsSekolahAsalAlamat;?>	
           </div>
            <div class="row-form">
                 <span class="label" >Provinsi</span>: <?php echo $profil->NamaPropinsiAsalSekolah;?>	
            	
           </div>
          <div class="row-form">
              <span class="label">Kab./Kota </span>: <?php echo $profil->NamaKabupatenAsalSekolah;?>	
           </div>
           <div class="row-form">
              <span class="label">Jurusan/Prodi </span>: <?php echo $profil->mhsSekolahAsalProgramStudi;?>
           </div>
           <div class="row-form">
              <span class="label">Tahun Lulus </span>: <?php echo $profil->mhsSekolahAsalTahunLulus;?>
           </div>  
       
        	 <div class="row-form">
                 <span class="label" style="width:auto" ><strong>Data Ayah</strong></span>
             </div>
             <div class="row-form">
                  <span class="label">Nama Ayah </span>: <?php echo $profil->mhsBapakNama;?>
             </div>
             <div class="row-form">
                 <span class="label" >Pekerjaan </span>: <?php echo $profil->PekerjaanAyah;?>
            	
             </div>
             <div class="row-form">
                 <span class="label" >Pendidikan </span>: <?php echo $profil->PendidikanAyah;?>
            	
             </div>
          
        	 <div class="row-form">
                 <span class="label" >Provinsi</span>: <?php echo $profil->NamaPropinsiAlamatAyah;?>
            	
           </div>
          <div class="row-form">
              <span class="label">Kab./Kota </span>: <?php echo $profil->NamaKabupatenAlamatAyah;?>
               
           </div>
           <div class="row-form">
              <span class="label">Alamat</span>: <?php echo $profil->mhsBapakAlamatJalan;?>
           </div>
            <div class="row-form">
              <span class="label">RT/RW</span>: <?php echo $profil->mhsBapakAlamatRT;?> <span style="display:inline-block;margin-left:4px;margin-right:4px;">/</span><?php echo $profil->mhsBapakAlamatRW;?>
            
        
           </div>
           <div class="row-form">
              <span class="label">Kelurahan/Desa</span>: <?php echo $profil->mhsBapakAlamatKelurahan;?>
           </div>
           <div class="row-form">
              <span class="label">Kecamatan</span>: <?php echo $profil->mhsBapakAlamatKecamatan;?>
           </div>
           <div class="row-form">
              <span class="label">Kodepos</span>: <?php echo $profil->mhsBapakAlamatKodePos;?>
           </div>
            <div class="row-form">
              <span class="label">Telepon</span>: <?php echo $profil->mhsBapakTeleponRumah;?>
           </div>
            <div class="row-form">
              <span class="label">Handphone </span>: <?php echo $profil->mhsBapakHandPhone;?>
           </div>
           <div class="row-form">
                 <span class="label" style="width:auto" ><strong>Data Ibu</strong></span>
             
          </div>
          <div class="row-form">
              <span class="label">Nama Ibu </span>: <?php echo $profil->mhsIbuNama;?>
           </div>
           <div class="row-form">
                 <span class="label" >Pekerjaan </span>: <?php echo $profil->PekerjaanIbu;?>
            	
             </div>
             <div class="row-form">
                 <span class="label" >Pendidikan </span>: <?php echo $profil->PendidikanIbu;?>
            	
             </div>
          
         <div class="row-form">
             <span class="label" >Provinsi</span>: <?php echo $profil->NamaPropinsiAlamatIbu;?>
            
       </div>
          <div class="row-form">
              <span class="label">Kab./Kota </span>: <?php echo $profil->NamaKabupatenAlamatIbu;?>
               
              
           </div>
           <div class="row-form">
              <span class="label">Alamat</span>: <?php echo $profil->mhsIbuAlamatJalan;?>
           </div>
            <div class="row-form">
              <span class="label">RT/RW</span>: <?php echo $profil->mhsIbuAlamatRT;?>
              <span style="display:inline-block;margin-left:4px;margin-right:4px;">/</span><?php echo $profil->mhsIbuAlamatRW;?>
             
        
           </div>
           <div class="row-form">
              <span class="label">Kelurahan/Desa</span>: <?php echo $profil->mhsIbuAlamatKelurahan;?>
           </div>
           <div class="row-form">
              <span class="label">Kecamatan</span>: <?php echo $profil->mhsIbuAlamatKecamatan;?>
           </div>
           <div class="row-form">
              <span class="label">Kodepos</span>: <?php echo $profil->mhsIbuAlamatKodePos;?>
           </div>
           <div class="row-form">
              <span class="label">Telepon</span>: <?php echo $profil->mhsIbuTeleponRumah;?>
           </div>
           <div class="row-form">
              <span class="label">Handphone</span>: <?php echo $profil->mhsIbuHandPhone;?>
           </div>
           
        </div>
   </div>
 </div>
   