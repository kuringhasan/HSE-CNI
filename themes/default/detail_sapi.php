<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> 
 <script>
	  var $j = jQuery.noConflict();
	  $j(function () {
		$j('#list_medical').DataTable();
		$j('#list_pelayanan').DataTable();
		$j("#list_pelayanan_filter").css("display","none");
		
	  })
	</script>
<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<?php   //echo "<pre>"; print_r($detail);echo "</pre>";?>

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="<?php echo $detail->url_foto;?>" alt="User profile picture"  width="65%" height="35%">

              <h3 class="profile-username text-center" style="margin-bottom:0px;"><?php echo $detail->koloni_name;?></h3>

              <p class="text-muted text-center" style="margin-top:0px;font-size:1.2em;"><?php echo $detail->no_eartag;?></p>

              <ul class="list-group list-group-unbordered">
                 <li class="list-group-item">
                  <b>Identifikasi</b> <a class="pull-right"><?php echo $detail->TanggalIdentifikasi['LengkapSingkatan'];?></a>
                </li>
                <li class="list-group-item">
                  <b>Laktasi Ke</b> <a class="pull-right"><?php echo $detail->laktasi_ke;?></a>
                </li>
                 <li class="list-group-item">
                  <b>Tipe</b> <a class="pull-right"><?php echo $detail->type;?></a>
                </li>
                 <li class="list-group-item">
                  <b>St. Reproduksi</b> <a class="pull-right"><?php echo $detail->StatusReproduksi;?></a>
                </li>
                <li class="list-group-item">
                  <b>Aktif</b> <a class="pull-right"><?php  
				  $status=$detail->StatusAktif;
				  if($detail->is_active==0 and $detail->is_need_verification==0){
					   $status=$detail->StatusAktif." (".$detail->StatusVerifikasi.")";
				  }
				  echo  $status;?></a>
                </li>
               
                <li class="list-group-item">
                  <b>Afkir</b> <a class="pull-right"><?php echo $detail->afkir==1?"Ya":"";?></a>
                </li>
              </ul>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Lokasi</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-group"></i> TPK/Kelompok</strong>

              <p class="text-muted">
               <?php echo "TPK : ".$detail->tpk_name."<br />Kelompok : ".$detail->kelompok_name;?>
              </p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Alamat</strong>

              <p class="text-muted"> <?php echo $detail->ALAMAT1;?></p>

              <hr>

            
             <!-- <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>

              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>-->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#pelayanan" data-toggle="tab">Event/Pelayanan</a></li> 
              <li ><a href="#pengobatan" data-toggle="tab">Medical Record</a></li>
              <li><a href="#datadiri" data-toggle="tab">Identitas Sapi</a></li>
               <li><a href="#kepemilikan" data-toggle="tab">Riwayat Kepemilikan</a></li>
            </ul>
            <div class="tab-content">
              <!-- /.tab-pane -->
              <div class="active tab-pane" id="pelayanan">
               <?php //echo "<pre>";print_r($riwayat_pelayanan);echo "</pre>";?>
               <table id="list_pelayanan" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th style="width:20px;">No</th>
                          <th width="55">Tanggal</th>
                          <th>Jenis Pelayanan</th>
                          <th>Keterangan</th>
                          <th>Petugas</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
						$no=1;
						while($event=current($riwayat_pelayanan)){
						?>
                        <tr>
                          <td style="text-align:center;"><?php echo $no;?></td>
                          <td ><?php echo $event->TanggalPelayanan;?></td>
                          <td><?php echo $event->jenis_pelayanan_nama;?></td>
                          <td><?php //echo $event->TanggalPelayanan;?></td>
                          <td><?php echo $event->PetugasNamaLengkap;?></td>
                        </tr>
                        <?php
						$no++;
						next($riwayat_pelayanan);
						}
						?>
                        </tbody>
                      </table>
              </div>
              <!-- /.tab-pane pengobatan-->
              <div class="tab-pane" id="pengobatan">
              	<div class="box">
                    <!-- /.box-header -->
                    <div class="box-body">
                      <table id="list_medical" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th style="width:20px;">No</th>
                          <th>Eartag</th>
                          <th>Nama</th>
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
                          <td ><?php echo $sapi->NoEartag;?></td>
                          <td><?php //echo $sapi->NoEartag;?></td>
                          <td><?php //echo $sapi->CaraPerolehanNama;?></td>
                          <td><?php //echo $sapi->CaraPerolehanNama;?></td>
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
              
           
              <div class="tab-pane" id="datadiri">
              <style>
			  	.label{
					font-weight:normal;
				}
			  </style>
                <?php echo "<pre>";print_r($detail);echo "</pre>";
				
				$profil=$detail->datadiri;
				?>
                <!-- responsive-form -->
                <div class="responsive-form"  style="width:100%;">
                	
                    <div class="row-form">
                     <span class="label" >ID Sapi </span>: <?php echo $detail->no_eartag;?>
                    </div>
                    <div class="row-form">
                     <span class="label" >Barcode </span>: <?php echo $detail->barcode;?>
                    </div>
                    <div class="row-form">
                     <span class="label" >Nama </span>: <?php echo $detail->koloni_name;?>
                    </div>
          			 <div class="row-form">
                     <span class="label" >Posisi Eartag</span>: <?php echo $detail->posisi_eartag;?>
                    </div>
                    
                    <div class="row-form">
                     <span class="label" >Tanggal Lahir</span>: <?php echo $detail->TanggalLahir['Lengkap']==""?"-":$detail->TanggalLahir['Lengkap'];?> 
                    </div>
                   
                  
                    <div class="row-form">
                         <span class="label" >Jenis Kelamin </span>: <?php echo $detail->Sex;?>
                        
                    </div>
                    
                    <div class="row-form">
                         <span class="label" >Tipe Sapi </span>: <?php echo $detail->NamaTipeSapi;?>
                        
                    </div>
                     
                    <div class="row-form">
                         <span class="label" >Status Laktasi </span>: <?php echo $detail->StatusLaktasi;?>
                        
                    </div>
                     <div class="row-form">
                         <span class="label" >Status Reproduksi </span>: <?php echo $detail->StatusReproduksi;?>
                        
                    </div>
                    
                    <div class="row-form">
                         <span class="label" >Pemilik </span>: <?php echo $detail->pemilik;?>
                        
                    </div>
                    <div class="row-form">
                         <span class="label" >TPK </span>: <?php echo $detail->tpk_name."/".$detail->kelompok_name;?>
                        
                    </div>
                    <div class="row-form">
                         <span class="label" >Dipelihara </span>: <?php echo  str_ireplace("<br />"," ",$detail->LamaDiperlihara);?>
                        
                    </div>
                  
                  
             		
                   
                </div> <!-- end of responsive-form -->
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="kepemilikan">
               </div><!-- /.tab-pane -->
              
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

  