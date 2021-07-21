<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<?php //echo "<pre>";print_r($detail);echo "</pre>";?>
<div class="row">
    <div class="col-md-3">
    
      <!-- Profile Image -->
      <div class="box box-primary " style="">
        <div class="box-body box-profile">
          
          <img class="profile-user-img img-responsive img-circle" src="<?php echo $detail->url_foto;?>" alt="User profile picture">
          <h3 class="profile-username text-center" style="margin-bottom:2px"><?php echo $detail->daftarNama;?></h3>
          <p class="text-muted text-center" style="margin-top:0px;"><?php echo $detail->daftarNISN;?></p> 
          
    		<a href="#" class="btn btn-primary btn-xs" style="width:100%" ><b>Tagihan</b></a>
            <a href="#" class="btn btn-primary btn-xs" style="width:100%" ><b>Kartu Peserta</b></a>
            <a href="#" class="btn btn-primary btn-xs" style="width:100%"><b>Kelulusan</b></a>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    
      <!-- About Me Box -->
      <div class="box box-primary " style="">
        <div class="box-header with-border" >
          <h3 class="box-title">Data Diri</h3>
        </div><!-- /.box-header -->
        <div class="box-body"  >
          <strong><i class="fa fa-birthday-cake margin-r-5"></i>  Tempat Tanggal Lahir</strong>
          <p class="text-muted">
            <?php echo $detail->daftarTempatLahir;?>, <?php echo $detail->TanggalLahir;?>
          </p>
          
          <strong><i class="fa fa-mobile margin-r-5"></i> <?php echo $detail->daftarAlamatHandPhone;?></strong><br />
        <strong><i class="fa fa-envelope-o margin-r-5"></i> <?php echo $detail->daftarAlamatEmail;?></strong>
    
          <hr style="margin-bottom:0px;margin-top:5px'">
          <strong><i class="fa  fa-info-circle margin-r-5"></i>Data Khusus</strong>
          <p class="text-muted">
          	Sex : <?php echo $detail->Kelamin?><br />
            BB/TB : <?php echo $detail->daftarBeratBadan;?>kg/<?php echo $detail->daftarTinggiBadan;?>cm<br />
            Agama : <?php echo $detail->Agama;?>
          </p>
    
          
          <hr>
    
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
    <div class="col-md-9" style="">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#alamat" data-toggle="tab"><i class="fa fa-map-marker"></i>&nbsp;Alamat</a></li>
          <li><a href="#pendidikan_asal" data-toggle="tab"><i class="fa fa-mortar-board"></i>&nbsp;Pendidikan Asal</a></li>
          <li><a href="#orangtua" data-toggle="tab"><i class="fa fa-users"></i>&nbsp;Data Orangtua</a></li>
          <li><a href="#keahlian" data-toggle="tab"><i class="fa fa-pencil"></i>&nbsp;Keahlian/Keterampilan</a></li>
        </ul>
        <div class="tab-content">
          <div class="active tab-pane" id="alamat">
           <style>

			.responsive-form .label{
				font-weight:normal;
				width:160px;
				display:inline-block;
			}
			.responsive-form .nilai{
				display:inline-block;
				text-align:left;
				float:left;
			}
			.responsive-form .label-batas{
				font-weight:normal;
				width:10px;
				display:inline-block;
				text-align:center;
				float:left;
			}
			.responsive-form .row-form{
				padding-top:0px;
				margin-top:0px;
				margin-bottom:5px;
				padding-bottom:0px;
			}
			</style>
					  

            <!-- Post -->
            <div class="post">
                <div class="responsive-form">
                <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Alamat</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->daftarAlamatAsalJalan;?></span>
                    
               </div>
               <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >RT/RW</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->daftarAlamatAsalRT."/".$detail->daftarAlamatAsalRW;?></span>
                    
               </div>
               <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Kecamatan</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->daftarAlamatAsalKecamatan;?></span>
                    
               </div>
              <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Kabupaten/Kota</span><span class="label-batas">:</span><span class="nilai"></span>
                    
               </div>
               <div class="row-form">
                 <span class="label">Provinsi</span><span class="label-batas">:</span><span class="nilai">
				 <?php //echo $detail->daftarAlamatEmail;?></span>
                   
                     
               </div>
               </div>
            </div><!-- /.post -->
          </div><!-- /.tab-pane -->
          <div class="tab-pane" id="pendidikan_asal">
            <!-- The timeline -->
            <div class="responsive-form">
                <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Sekolah Asal</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->daftarSekolahAsalNama;?></span>
                    
               </div>
                <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Alamat Sekolah</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->daftarSekolahAsalAlamat;?></span>
                    
               </div>
               <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Jurusan/Program Studi</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->daftarSekolahAsalProgramStudi;?></span>
                    
               </div>
               <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Tahun Lulus</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->daftarSekolahAsalTahunLulus;?></span>
                    
               </div>
               </div>
            
          </div><!-- /.tab-pane -->

          <div class="tab-pane" id="orangtua">
           		<div class="responsive-form">
                <div class="row-form" style="">
                    <span class="label" style="display:inline-block;font-weight:bold" >Data Ayah</span>
               </div>
                <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Nama</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->daftarBapakNama;?></span>
                    
               </div>
               <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Pekerjaan</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->PekerjaanAyah;?></span>
                    
               </div>
                <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Pendidikan</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->PendidikanAyah;?></span>
                    
               </div>
               <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Alamat</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->daftarBapakAlamatJalan;?></span>
                    
               </div>
               <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Kota/Kabupaten</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->daftarBapakAlamatKabupatenKode;?></span>
                    
               </div>
               <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Telepon/HP</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->daftarBapakTeleponRumah."/".$detail->daftarBapakHandPhone;?></span>
                    
               </div>
                <div class="row-form" style="">
                    <span class="label" style="display:inline-block;font-weight:bold" >Data Ibu</span>
               </div>
                <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Nama</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->daftarIbuNama;?></span>
                    
               </div>
               <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Pekerjaan</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->PekerjaanIbu;?></span>
                    
               </div>
                <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Pendidikan</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->PendidikanIbu;?></span>
                    
               </div>
               <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Alamat</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->daftarIbuAlamatJalan;?></span>
                    
               </div>
               <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Kota/Kabupaten</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->daftarIbuAlamatKabupatenKode;?></span>
                    
               </div>
               <div class="row-form" style="">
                    <span class="label" style="display:inline-block" >Telepon/HP</span><label class="label-batas">:</label>
                    <span class="nilai"> <?php echo $detail->daftarIbuTeleponRumah."/".$detail->daftarIbuHandPhone;?></span>
                    
               </div>
               </div>
          </div><!-- /.tab-pane -->
          <div class="tab-pane" id="keahlian">
           
          </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
      </div><!-- /.nav-tabs-custom -->
    </div>
</div><!-- /.row -->