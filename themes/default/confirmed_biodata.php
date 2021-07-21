
<!-- <link rel="stylesheet" href="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"> --> 

<style>

.responsive-form .label{
	min-width:195px;
	width:auto;
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
.row-form{
	
	
}
.text-center{
	text-align:center;
}
</style>
<?php //echo "<pre>";print_r($profil);echo "</pre>";    ?>  

<form id="form_input_data"   method="post" style="display:inline-block" enctype="multipart/form-data">
        <div class="responsive-form" >
        <input type="hidden" class="input" name="current_step" id="current_step" size="2" value="<?php echo $step;?>"/>
        <input type="hidden" class="input" name="skip" id="skip" size="5" value=""/>
         <input type="hidden" class="input" name="id" id="id" size="4" value="<?php echo $profil->ID_ANGGOTA;?>"/>
        
      
       
         <style>
       #list_populasi tr td{
		  padding-top:2px;
		  padding-bottom:2px;
		  font-size:11px;
		  vertical-align:middle;
	   }
	   #list_populasi tr th{
		 vertical-align:middle;
		 font-size:11px;
		 background-color:#CCC;
	   }
        </style>
		<div class="row-form">
             <span class="label" >Nama </span> : <?php echo $profil->NAMA;?>
            </div>
           
           <div class="row-form">
                 <span class="label" >NIK <small class="wajib"></small></span> : <?php echo $profil->NIK;?>
                 
            </div>
            
            <div class="row-form">
                 <span class="label" >Jenis Kelamin <small class="wajib"></small></span> : <?php echo $profil->Kelamin;?>
                
            </div>
           
         <div class="row-form">
         <span class="label" >Petugas Survey</span> : <?php echo $profil->NamaPetugasSurvey;?>
          </div>
          <div class="row-form">
          <span class="label" >MCP/TPK</span> : <?php echo $profil->NamaTPK;?>
         </div>
          <div class="row-form">
          <span class="label" >Kelompok</span>: <?php echo $profil->NamaKelompok;?>
         </div>
          <div class="row-form">
          <span class="label" >Kelompok Harga</span>: <?php echo $profil->KelompokHargaNama;?>
         </div>
         <div class="row-form">
              <span class="label">Alamat kandang</span> : <?php echo $profil->ALAMAT2;?>
           </div>
           <div class="row-form">
              <span class="label">Koordinat Kandang</span> : <?php echo $profil->koordinat_kandang;?>
           </div>
        <div class="row-form">
					 <span class="label" >Status Pernikahan</span>
					<select name="status_pernikahan" class="input"  id="status_pernikahan" >
						
						 <?php
							echo '<option value="">--pilih--</option>';
							$List=$list_marital;
							$status_marital=isset($_POST['status_pernikahan'])?$_POST['status_pernikahan']:$profil->status_pernikahan;
							while($data = each($List)) {
							   ?>
						<option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$status_marital?"selected":""; ?> >
						  <?php echo $data['value'];?></option>
						<?php
							}
						 ?>
				  </select>
			   </div>
				 <div class="row-form">
				 <span class="label" >Tempat Lahir </span>
				<input type="text" class="input" name="tempat_lahir" id="tempat_lahir" size="30" value="<?php echo $profil->tempat_lahir;?>"/>
				</div>
				 <div class="row-form">
				 <span class="label" >Tanggal Lahir  <small class="wajib">*</small></span>
				 <span style="display:inline-block">
			 
				<input name="tahun_lahir" type="text" id="tahun_lahir" size="3" value="<?php echo $profil->TanggalLahirDetail['Tahun'];?>" placeholder="Tahun"  class="input" style="margin-right:5px;width:auto"/>
			
			  
			<select name="bulan_lahir" id="bulan_lahir"  class="input" style="margin-right:5px;width:auto">
			  <?php
					echo '<option value="">--Bulan--</option>';
					$List=$list_bulan;
					while($data = each($List)) {
						$bulan_lahir=isset($_POST['bulan_lahir'])?$_POST['bulan_lahir']:$profil->TanggalLahirDetail['BulanAngka'];
					   ?>
			  <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$bulan_lahir?"selected":""; ?> > <?php echo $data['value'];?></option>
			  <?php
				   }
					?>
			  </select>
			&nbsp;&nbsp;
			<select name="tanggal_lahir" id="tanggal_lahir"  class="input" style="margin-right:5px;width:auto">  
					<?php
					echo '<option value="">--</option>';
					$List=$list_tanggal;
					while($data = each($List)) {
						$tanggal_lahir=isset($_POST['tanggal_lahir'])?$_POST['tanggal_lahir']:$profil->TanggalLahirDetail['Tgl'];
					   ?>
						<option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$tanggal_lahir?"selected":""; ?> >
					 <?php echo $data['value'];?></option>
					 <?php
				   }
					?>
			  </select> 
			  </span>
				</div>
				 <div class="row-form">
				 <span class="label" >RT/RW</span>
				<input type="text" class="input" name="rt" id="rt" size="2" value="<?php echo $profil->RT;?>" style="margin-right:3px;width:auto"/> <span style="display:inline-block;float:left;margin-left:4px;margin-right:4px;">/</span>
				
				<input type="text" class="input" name="rw" id="rw" size="2" value="<?php echo $profil->RW;?>"/>
				</div>
				<div class="row-form">
				  <span class="label">Alamat</span>
				  <textarea name="alamat" cols="35" rows="2" id="alamat"  class="input"  ><?php echo $profil->ALAMAT1;?></textarea>	
			   </div>
			   <div class="row-form">
					 <span class="label" >Provinsi</span>
					<select name="provinsi" class="input"  id="provinsi" >
						
						 <?php
							echo '<option value="">--- Provinsi ---</option>';
							$List=$list_provinsi;
							while($data = each($List)) {
							   ?>
						<option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$provinsi?"selected":""; ?> >
						  <?php echo $data['value'];?></option>
						<?php
							}
						 ?>
				  </select>
			   </div>
			  <div class="row-form">
				  <span class="label">Kab./Kota  <small class="wajib">*</small></span>
					
					<select name="kota" id="kota"  class="input"  style="margin-right:5px;width:auto">
					 <?php
							echo '<option value="">--- Kota/Kab.---</option>';
							$List=$ListKota;
							while($data = each($List)) {
							   ?>
						<option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$kota?"selected":""; ?> >
						  <?php echo $data['value'];?></option>
						<?php
							}
						 ?>
					</select>
					<input type="text" class="input" name="kodepos" id="kodepos"  placeholder="Kode Post" size="6" value="<?php echo $profil->pKodepos;?>"/>
					<img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:16px; vertical-align:middle; border:0px;"  alt=""  id="loaderKota"/>
					
			   </div>
			  <div class="row-form">
			   <span class="label">Kecamatan  <small class="wajib">*</small></span>
				 <select name="kecamatan" id="kecamatan" class="input">
				 <?php
				echo '<option value="">--kecamatan--</option>';
				
					
				?>
				</select>
			   <img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:16px; vertical-align:middle; border:0px;"  alt=""  id="loaderKecamatan"/>
			  </div>
				
			   <div class="row-form">
				  <span class="label">Kelurahan/Desa  <small class="wajib">*</small></span>
				  <select name="desa" id="desa" class="input">
				 <?php
				echo '<option value="">--desa/kel--</option>';
				
					
				?>
				</select>
			   <img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:16px; vertical-align:middle; border:0px;"  alt=""  id="loaderDesa"/>
			   </div>
			   <div class="row-form">
					 <span class="label" >Agama</span>
					<select name="agama" class="input"  id="agama" >
						<?php $agama=isset($_POST['agama'])?$_POST['agama']:$profil->agama;?>
						 <?php
							echo '<option value="">--- Agama ---</option>';
							$List=$list_agama;
							while($data = each($List)) {
							   ?>
						<option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$agama?"selected":""; ?> >
						  <?php echo $data['value'];?></option>
						<?php
							}
						 ?>
				  </select>
			   </div>
				<div class="row-form">
					 <span class="label" >Handphone </span>
					<input type="text" class="input" name="hp" id="hp" size="30" value="<?php echo $profil->NO_HP;?>"/>
				</div>
				<div class="row-form" style="margin-left:195px;width:auto;font-size:10px;font-style:italic">
					 Pisahkan dengan tanda koma(,) bila mengisi lebih dari satu. Maksimal 3 No HP.
				</div>
				<div class="row-form">
					 <span class="label" >No. Telepon </span>
					<input type="text" class="input" name="telp" id="telp" size="20" value="<?php echo $profil->NO_TELP;?>"/>
				</div>
				<div class="row-form">
				  <span class="label">E-Mail </span>
				  <input name="email" type="text" id="email" size="30" value="<?php echo $profil->email;?>"  class="input"/>
			   </div>
			   <div class="row-form">
			  <span class="label" >MCP/TPK</span>
					<select name="tpk" class="input tpk" id="tpk">
						  <?php
						  $mcp=$profil->mcp_id;
						echo '<option value="">-- pilih --</option>';
						$List=$ListTPK;
						while($data = each($List)) {
						   ?>
					  <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$mcp?"selected":""; ?> ><?php echo $data['value'];?></option>
					  <?php
					  
						}
					 ?>
					</select>
			 </div>
			  <div class="row-form">
			  <span class="label" >Kelompok</span>
					<select name="kelompok" class="input kelompok" id="kelompok">
						  <?php
						echo '<option value="">-- pilih --</option>';
					  
					 ?>
					</select>
					 <img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:16px; vertical-align:middle; border:0px;"  alt=""  id="loaderKelompok"/>
			 </div>
			  <div class="row-form">
			  <span class="label" >Kelompok Harga</span>
					<select name="kelompok_harga" class="input kelompok_harga" id="kelompok_harga">
						  <?php
						echo '<option value="">-- pilih --</option>';
					   
					 ?>
					</select>  <img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:16px; vertical-align:middle; border:0px;"  alt=""  id="loaderKelompokHarga"/>
			 </div>
    		 <div class="row-form" style="margin:4px 8px 4px 8px;">
    		<table  id="list_populasi"  class="table table-bordered table-hover dataTable" style="margin-top:4px; width:auto" >
                <thead>
                <tr >
                    <th class="no_urut" style="width:30px;">No</th>
                    <th style="width:105px;">No Eartag</th>
                    <th style="width:125px;">Nama</th>
                    <th style="width:110px;">Tipe</th> 
                    <th style="width:60px;">Gender</th>
                    <th style="width:70px;">Verifikasi</th>
                </tr>
                </thead>
       
        </table>  
        </div>
         <div class="row-form" style="margin:4px 8px 4px 8px;">
    		<table  id="list_tanggungan"  class="table table-bordered table-hover dataTable" style="margin-top:4px; width:100%" >
			<thead>
			<tr >
				<th class="no_urut" style="width:25px;">No</th>
				<th style="width:105px;">NIK</th>
				<th style="width:125px;">Nama</th>
				<th style="width:70px;">Tanggal Lahir</th> 
				<th style="width:60px;">Gender</th>
			  <th style="width:70px;">Hubungan Keluarga</th>
			 <th style="width:60px;"><?php echo $TombolTambahTanggungan;?></th>
			</tr>
			</thead>
		   
			</table>  
        </div>
    </form>
	   <!-- <script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> 
		<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>-->
        <script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> 	 		
		<script> 
		
		//var $m = jQuery.noConflict();
		$(document).ready(function() {
			
			//alert("<?php echo $url_populasi;?>/listdata");
			var table=$('#list_populasi').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax": {
					"url": "<?php echo $url_populasi;?>/listdata",
					"type": "POST"
				},
				"columnDefs": [
					{ "orderable": false, "targets": [1,2,3,4,5] },
					{"targets": [0,2,3,4,5],"className": "text-center"}
				 ],
				"columns": [
					{ "data": "No",'sortable': false,'className':'col-number' },
					{ "data": "NoEartag" },
					{ "data": "Nama" },
					{ "data": "Tipe" },
					{ "data": "GenderNama" },
					{ "data": "Verifikasi" }
				],
				'fnCreatedRow': function (nRow, aData, iDataIndex) {
					$(nRow).attr('id', 'tr_' + aData.Kode); // or whatever you choose to set as the id
				},
				"initComplete": function(settings, json) {
					//console.log(json);
					//$j('#list_data').removeClass("sorting_asc");
					$('#list_populasi tr th').removeClass("sorting_asc");
					$('#list_populasi_info').remove();;
					$('#list_populasi_paginate').remove();
					$("#list_populasi_length").remove();
					$("#list_populasi_filter").remove();
					$('#list_populasi .no_urut').removeClass("sorting_asc");
				  }
			});
			var table1=$('#list_tanggungan').DataTable({
					"processing": true,
					"serverSide": true,
					"ajax": {
						"url": "<?php echo $url_tanggungan;?>/listdata",
						"type": "POST"
					},
					"columnDefs": [
						{ "orderable": false, "targets": [0,1,2,3,4,5,6] },
						{"targets": [0,1,2,3,4,5,6],"className": "text-center"}
					 ],
					"columns": [
						{ "data": "No",'sortable': false,'className':'col-number' },
						{ "data": "NIK" },
						{ "data": "Nama" },
						{ "data": "TanggalLahir" },
						{ "data": "Gender" },
						{ "data": "HubunganKeluarga" },
						{ "data": "Tombol",'className':'btn-action' }
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						$(nRow).attr('id', 'tr_' + aData.Kode); // or whatever you choose to set as the id
					},
					"initComplete": function(settings, json) {
						//console.log(json);
						//$j('#list_data').removeClass("sorting_asc");
						$('#list_tanggungan tr th').removeClass("sorting_asc");
						$('#list_tanggungan_info').remove();;
						$('#list_tanggungan_paginate').remove();
						$("#list_tanggungan_length").remove();
						$("#list_tanggungan_filter").remove();
						$('#list_tanggungan .no_urut').removeClass("sorting_asc");
					  }
				});
		
		});
		
		</script>