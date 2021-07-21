<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> 
 <script>
	  var $j = jQuery.noConflict();
	  $j(function () {
		$j('#data_kepemilikan_sapi').DataTable();
	  })
	</script>
<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<?php   //echo "<pre>"; print_r($detail);echo "</pre>";?>

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="<?php echo $detail->url_foto;?>" alt="User profile picture">

              <h3 class="profile-username text-center" style="margin-bottom:0px;"><?php echo $detail->NoAnggota;?></h3>

              <p class="text-muted text-center" style="margin-top:0px;font-size:1.2em;"><?php echo $detail->NamaLengkap;?></p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Jumlah sapi</b> <a class="pull-right"><?php echo count($detail->DataSapi);?></a>
                </li>
                <li class="list-group-item">
                  <b>Status Keanggotaan</b> <a class="pull-right"><?php echo $detail->status_name;?></a>
                </li>
                <li class="list-group-item">
                   <b>Anggota Sejak </b> <a class="pull-right"><?php echo trim($detail->TanggalMasuk)<>""?$detail->TanggalMasukDetail['Tanggal']:"-";?></a>
                </li>
              </ul>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Tentang Saya</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-group"></i> TPK/Kelompok</strong>

              <p class="text-muted">
               <?php echo "TPK : ".$detail->NamaTPK."<br />Kelompok : ".$detail->NamaKelompok;?>
              </p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Alamat</strong>

              <p class="text-muted"> <?php echo $detail->ALAMAT1;?></p>

              <hr>

            
              <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>

              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#kepemilikan" data-toggle="tab">Kepemilikan Sapi</a></li>
              <li><a href="#subjaringan" data-toggle="tab">Event/Pelayanan</a></li>
              <li><a href="#datadiri" data-toggle="tab">Datadiri</a></li>
              <li><a href="#tanggungan" data-toggle="tab">Tanggungan</a></li>
               <li><a href="#simpanan" data-toggle="tab">Simpanan-Simpanan</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="kepemilikan">
              	<div class="box">
                    
                    <!-- /.box-header -->
                    <div class="box-body">
                      <table id="data_kepemilikan_sapi" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th style="width:20px;">No</th>
                          <th>Eartag</th>
                          <th>Status Laktasi</th>
                          <th>Cara Perolehan</th>
                          <th>Tipe</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
						$no=1;
						while($sapi=current($detail->DataSapi)){
						?>
                        <tr>
                          <td style="text-align:center;"><?php echo $no;?></td>
                          <td ><?php echo $sapi->no_eartag;?></td>
                          <td><?php echo $sapi->StatusLaktasi;?></td>
                          <td><?php echo $sapi->CaraPerolehanNama;?></td>
                          <td><?php echo $sapi->tipe_nama;?></td>
                        </tr>
                        <?php
						$no++;
						next($detail->DataSapi);
						}
						?>
                        </tbody>
                      </table>
                    </div>
                    <!-- /.box-body -->
                  </div>
                  <!-- /.box -->
             
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="subjaringan">
               
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="datadiri">
              <style>
			  	.label{
					font-weight:normal;
				}
			  </style>
                <?php //echo "<pre>";print_r($detail);echo "</pre>";
				
				$profil=$detail->datadiri;
				?>
                <!-- responsive-form -->
                <div class="responsive-form" >
                	<div class="row-form">
                     <span class="label" >Tanda Pengenal </span>: KTP
                    </div>
                    <div class="row-form">
                     <span class="label" >NIK </span>: <?php echo $detail->NIK;?>
                    </div>
                    <div class="row-form">
                     <span class="label" >No Anggota </span>: <?php echo $detail->NoAnggota;?>
                    </div>
          			 <div class="row-form">
                     <span class="label" >Nama Lengkap </span>: <?php echo $detail->NamaLengkap;?>
                    </div>
                    
                    <div class="row-form">
                     <span class="label" >TTL</span>: <?php echo $detail->pTempatLahirLain;?> / <?php echo $detail->TanggalLahir2;?>
                    </div>
                   
                  
                    <div class="row-form">
                         <span class="label" >Jenis Kelamin </span>: <?php echo $detail->Kelamin;?>
                        
                    </div>
                  
                    <div class="row-form" >
                        <span class="label">Agama</span>: <?php echo $detail->agama;?>
                      
                    </div>
                   
                    <div class="row-form">
                         <span class="label" >E-Mail </span>: <?php echo $detail->email;?>
                    </div>
                    <div class="row-form">
                         <span class="label" >Handphone </span>: <?php echo $detail->NO_HP;?>
                    </div>
             		
                     <div class="row-form"><hr>
                       
                     </div>
                    
                 
                   <div class="row-form">
                      <span class="label">Alamat</span>: <?php echo $detail->alamat;?>
                      
                   </div>
                    <div class="row-form">
                      <span class="label">RT/RW</span>: <?php echo $detail->RT;?> / <?php echo $detail->RW ;?>
                      
                   </div>
                   
                   <div class="row-form">
                      <span class="label">Kelurahan/Desa</span>: <?php echo $detail->AlamatKelurahanNama;?>
                   </div>
                   <div class="row-form">
                      <span class="label">Kecamatan</span>: <?php echo $detail->AlamatKecamatanNama;?>
                   </div>
                    <div class="row-form">
                      <span class="label">Kab./Kota </span>: <?php echo $detail->NamaKotaAlamat;?>
                      
                   </div>
                    <div class="row-form">
                         <span class="label" >Provinsi</span>: <?php echo $detail->NamaProvinsiAlamat;?>
                        
                   </div>
                   <div class="row-form">
                      <span class="label">Kodepos</span>: <?php echo $profil->mhsAlamatAsalKodePos;?>
                   </div>
                    <div class="row-form">
                      <span class="label">Telepon</span>: <?php echo $profil->mhsAlamatAsalTeleponRumah;?>
                   </div>
                </div> <!-- end of responsive-form -->
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tanggungan">
              	 <!-- /.box-header -->
                    <div class="box-body">
                      <table id="data_tanggungan" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th style="width:20px;">No</th>
                          <th>NIK</th>
                          <th>Nama</th> 
                          <th>Sex</th>
                          <th>TTL</th>
                          <th>Hubungan Keluarga</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
						$no=1;
						while($tanggungan=current($detail->Tanggungan)){
						?>
                        <tr>
                          <td style="text-align:center;"><?php echo $no;?></td>
                          <td ><?php echo $tanggungan->NIK;?></td>
                          <td><?php echo $tanggungan->Nama;?></td>
                          <td><?php echo $tanggungan->NamaGender;?></td>
                          <td><?php echo $tanggungan->TanggalLahirDetail['Lengkap'];?></td>
                           <td><?php echo $tanggungan->NamaHubunganKeluarga;?></td>
                        </tr>
                        <?php
						$no++;
						next($detail->Tanggungan);
						}
						?>
                        </tbody>
                      </table>
                    </div>
                    <!-- /.box-body -->
               
              </div>
              <!-- /.tab-pane tanggungan-->
              
               <!-- /.tab-pane  simpanan-->
              <div class="tab-pane" id="simpanan">
              	 <!-- /.box-header -->
                    <div class="box-body">
                      <table id="data_simpanan" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th style="width:20px;">No</th>
                          <th>NIK</th>
                          <th>Nama</th> 
                          <th>Sex</th>
                          <th>TTL</th>
                          <th>Hubungan Keluarga</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
						$no=1;
						while($tanggungan=current($detail->Tanggungan)){
						?>
                        <tr>
                          <td style="text-align:center;"><?php echo $no;?></td>
                          <td ><?php echo $tanggungan->NIK;?></td>
                          <td><?php echo $tanggungan->Nama;?></td>
                          <td><?php echo $tanggungan->NamaGender;?></td>
                          <td><?php echo $tanggungan->TanggalLahirDetail['Lengkap'];?></td>
                           <td><?php echo $tanggungan->NamaHubunganKeluarga;?></td>
                        </tr>
                        <?php
						$no++;
						next($detail->Tanggungan);
						}
						?>
                        </tbody>
                      </table>
                    </div>
                    <!-- /.box-body -->
               
              </div>
              <!-- /.tab-pane simpanan-->
              
              
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

  