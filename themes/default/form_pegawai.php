
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
.error{
	border:1px solid #F99;
	background-color:#FFC;
}
.lbl_error{
	color:#F00;
	padding-left:5px;
}
</style>
<?php //echo "<pre>";print_r($profil);echo "</pre>";    ?>  
   
<form id="form_input_data"   method="post" style="display:inline-block" enctype="multipart/form-data">
        <div class="responsive-form" >
        <input type="hidden" class="input" name="current_step" id="current_step" size="2" value="<?php echo $step;?>"/>
        <input type="hidden" class="input" name="skip" id="skip" size="5" value=""/>
         <input type="hidden" class="input" name="id" id="id" size="4" value="<?php echo $profil->partner_id;?>"/>
         
        <?php
		
			if($step==1 ){
				
				
				
				$provinsi=(isset($profil->KodeProvinsi)==true and trim($profil->KodeProvinsi)<>"")?$profil->KodeProvinsi:"";
				$kota=(isset($profil->alamat_kabupaten)==true and trim($profil->alamat_kabupaten)<>"")?$profil->alamat_kabupaten:"";
				//$tpk=(isset($profil->mcp_id)==true and trim($profil->mcp_id)<>"")?$profil->mcp_id:"";
				//$kelompok=(isset($profil->KelompokID)==true and trim($profil->KelompokID)<>"")?$profil->KelompokID:"";
				
			?>
		   
			<script>
			$(document).ready(function () {
				 comboAjax('<?php echo $url_comboAjax;?>/listkota','<?php echo $provinsi;?>','kota','<?php echo $kota;?>','','loaderKota');
				 $("#provinsi").change(function(){		
					var parentkode=	$(this).val();	
					comboAjax('<?php echo $url_comboAjax;?>/listkota',parentkode,'kota','','','loaderKota');	
				}); 
				comboAjax('<?php echo $url_comboAjax;?>/listkecamatan','<?php echo $kota;?>','kecamatan','<?php echo $profil->alamat_kecamatan;?>','','loaderKecamatan');	
				$("#kota").change(function(){		
					var parentkode=	$(this).val();	
					comboAjax('<?php echo $url_comboAjax;?>/listkecamatan',parentkode,'kecamatan','','','loaderKecamatan');	
				}); 
				
				$("#kecamatan").change(function(){		
					var parentkode=	$(this).val();	
					comboAjax('<?php echo $url_comboAjax;?>/listdesa',parentkode,'desa','','','loaderDesa');	
				}); 
				$("#status_pernikahan").change(function(){		
					var nilai=	$(this).val();	
					
					if(nilai==2){
						
						$("#row-nama-pasangan").show();
					}else{
						$("#row-nama-pasangan").hide();
					}
				}); 
				comboAjax('<?php echo $url_comboAjax;?>/listdesa','<?php echo $profil->KodeKecamatan;?>','desa','<?php echo $profil->KodeDesa;?>','','loaderDesa');
				$("#desa").change(function(){		
					var parentkode=	$(this).val();	
					comboAjax('<?php echo $url_comboAjax;?>/listtps',parentkode,'tps','','','loaderTPS');	
				}); 
				
				comboAjax('<?php echo $url_comboAjax;?>/listkelompok','<?php echo $profil->mcp_id;?>','kelompok','<?php echo $profil->KelompokID;?>','','loaderTPK');
				 $("#tpk").change(function(){		
					var parentkode=	$(this).val();	
					comboAjax('<?php echo $url_comboAjax;?>/listkelompok',parentkode,'kelompok','','','loaderKelompok');	
				}); 
				comboAjax('<?php echo $url_comboAjax;?>/list_kelompokharga','<?php echo $profil->KelompokID;?>','kelompok_harga','<?php echo $profil->KelompokHargaID;?>','','loaderKelompokHarga');	
				$("#kelompok").change(function(){		
					var parentkode=	$(this).val();	
					comboAjax('<?php echo $url_comboAjax;?>/list_kelompokharga',parentkode,'kelompok_harga','','','loaderKelompokHarga');	
				}); 
				
			});
			
			</script>
				
			 <div class="row-form">
					 <span class="label" >NIK  <small class="wajib">*</small></span> 
					 <input type="text" class="input" name="nik" id="nik" size="17" value="<?php echo $profil->nik;?>"/>
					 
				</div>
				<div class="row-form">
					 <span class="label" >No KK  <small class="wajib">*</small></span> 
					 <input type="text" class="input" name="no_kk" id="no_kk" size="17" value="<?php echo $profil->no_kk;?>"/>
					 
				</div>
				<div class="row-form">
				 <span class="label" >Nama Lengkap <small class="wajib">*</small></span>  <input type="text" class="input" name="nama" id="nama" size="28" value="<?php echo $profil->name;?>"/> <small>&nbsp;Sesuai KTP, tanpa gelar</small>
				</div>
                <div class="row-form">
					 <span class="label" >Gelar </span> 
					 <input type="text" class="input" name="gelar_depan" id="gelar_depan" size="5" placeholder="Depan"  value="<?php echo $profil->gelar_depan;?>" style="margin-right:2px;"/>
                     <input type="text" class="input" name="gelar_belakang" id="gelar_belakang" size="5" placeholder="Belakang" value="<?php echo $profil->gelar_belakang;?>"/>
					 
				</div>
				<div class="row-form">
					 <span class="label" >Nama Panggilan </span> 
					 <input type="text" class="input" name="panggilan" id="panggilan" size="12" value="<?php echo $profil->alias;?>"/> 
					 
				</div>
			  
                 <div class="row-form">
				 <span class="label" >Tempat Lahir <small class="wajib">*</small></span>
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
					 <span class="label" >Jenis Kelamin <small class="wajib"></small></span>
					<span style="display:inline-block">
					<?php
			 
					$sex=isset($_POST['gender'])?$_POST['gender']:$profil->gender;
					?>
					<label >  
						<input type="radio"  name="gender" id="genderl" value="L"  <?php echo ($sex=="L")?"checked":"";?>  />
							  Laki-Laki</label> 
					<label  style="margin-left:10px;"> 
						<input type="radio" name="gender" value="P"  <?php echo ($sex=="P")?"checked":"";?> />
							  Perempuan </label>
					</span>
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
					 <span class="label" >Status Pernikahan<small class="wajib"></small></span>
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
               <?php
               $display=$profil->status_pernikahan==2?"":"display:none;";
               ?>
               <div class="row-form" style="<?php echo $display;?>" id="row-nama-pasangan">
				 <span class="label" >Nama Istri/Suami</span>  <input type="text" class="input" name="nama_pasangan" id="nama_pasangan" size="28" value="<?php echo $profil->nama_pasangan;?>"/>
				</div>
				
			  <div class="row-form">
				 <span class="label" >RT/RW</span>
				<input type="text" class="input" name="rt" id="rt" size="2" value="<?php echo $profil->alamat_rt;?>" style="margin-right:3px;width:auto"/> <span style="display:inline-block;float:left;margin-left:4px;margin-right:4px;">/</span>
				
				<input type="text" class="input" name="rw" id="rw" size="2" value="<?php echo $profil->alamat_rw;?>"/>
				</div>
				<div class="row-form">
				  <span class="label">Alamat <small class="wajib"></small></span>
				  <textarea name="alamat" cols="35" rows="2" id="alamat"  class="input"  ><?php echo $profil->alamat;?></textarea>	
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
					<input type="text" class="input" name="kodepos" id="kodepos"  placeholder="Kode Post" size="6" value="<?php echo $profil->kodepos;?>"/>
					<img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:16px; vertical-align:middle; border:0px;"  alt=""  id="loaderKota"/>
					
			   </div>
			  <div class="row-form">
			   <span class="label">Kecamatan  </span>
				 <select name="kecamatan" id="kecamatan" class="input">
				 <?php
				echo '<option value="">--kecamatan--</option>';
				
					
				?>
				</select>
			   <img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:16px; vertical-align:middle; border:0px;"  alt=""  id="loaderKecamatan"/>
			  </div>
				
			   <div class="row-form">
				  <span class="label">Kelurahan/Desa  </span>
				  <select name="desa" id="desa" class="input">
				 <?php
				echo '<option value="">--desa/kel--</option>';
				
					
				?>
				</select>
			   <img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:16px; vertical-align:middle; border:0px;"  alt=""  id="loaderDesa"/>
			   </div>
			  
				<div class="row-form">
					 <span class="label" >Handphone </span>
					<input type="text" class="input" name="hp" id="hp" size="30" value="<?php echo $profil->phone;?>"/>
				</div>
				<div class="row-form" style="margin-left:195px;width:auto;font-size:10px;font-style:italic">
					 Pisahkan dengan tanda koma(,) bila mengisi lebih dari satu. Maksimal 3 No HP.
				</div>
				<div class="row-form">
					 <span class="label" >No. Telepon </span>
					<input type="text" class="input" name="telp" id="telp" size="20" value="<?php echo $profil->telepon;?>"/>
				</div>
				<div class="row-form">
				  <span class="label">E-Mail </span>
				  <input name="email" type="text" id="email" size="30" value="<?php echo $profil->email;?>"  class="input"/>
			   </div>
			   
			 <?php
			}
			if($step==2){
				
			?>  
		   
			<style>
		   #list_education tr td{
			  padding-top:2px;
			  padding-bottom:2px;
			  font-size:11px;
			  vertical-align:middle;
		   }
		   #list_education tr th{
			 vertical-align:middle;
			 font-size:11px;
			 background-color:#CCC;
		   }
			</style>
            <div class="row-form">
                 <span class="label" >NIK <small class="wajib"></small></span> : <?php echo $profil->nik;?>
                 
            </div>
			<div class="row-form">
             <span class="label" >Nama </span> : <?php echo $profil->NamaLengkap;?>
            </div>
           
           
            
            <div class="row-form">
                 <span class="label" >Jenis Kelamin <small class="wajib"></small></span> : <?php echo $profil->Kelamin;?>
                
            </div>
            
         
   
             <div class="row-form" style="margin-top:2px;">
             <input type="checkbox" name="tidak_ada_pendidikan" id="tidak_ada_pendidikan" style="margin-left:2px;"> Tidak Ada Pendidikan
			<table  id="list_education"  class="table table-bordered table-hover dataTable" style="margin-top:4px; width:100%" >
                <thead>
                <tr >
                    <th class="no_urut" style="width:25px;">No</th>
                    <th style="width:105px;">Jenjang</th>
                    <th style="width:125px;">Universitas</th>
                    <th style="width:120px;">Jurusan</th> 
                    <th style="width:55px;">Tahun Lulus</th>
                    <th style="width:90px;">Lokasi</th>
                  <th style="width:70px;">Terakhir</th>
                 <th style="width:50px;text-align:center"><?php echo $TombolTambahEducation;?></th>
                </tr>
                </thead>
               
			</table>  
             </div>
			
		  <!-- <script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> 
			<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>-->
			<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
			<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> 	 		
			<script> 
			
			//var $m = jQuery.noConflict();
			$(document).ready(function() {
				//alert("<?php echo $url_tanggungan;?>/listdata");
				var table1=$('#list_education').DataTable({
					"processing": true,
					"serverSide": true,
					"ajax": {
						"url": "<?php echo $url_education;?>/listdata",
						"type": "POST"
					},
					"columnDefs": [
						{ "orderable": false, "targets": [0,1,2,3,4,5,6,7] },
						{"targets": [0,1,2,3,4,5,6,7],"className": "text-center"}
					 ],
					"columns": [
						{ "data": "No",'sortable': false,'className':'col-number' },
						{ "data": "Jenjang" },
						{ "data": "Institusi" },
						{ "data": "Jurusan" },
						{ "data": "Tahun" },
						{ "data": "Lokasi" },
						{ "data": "Terakhir" },
						{ "data": "Tombol",'className':'btn-action' }
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						$(nRow).attr('id', 'tr_' + aData.Kode); // or whatever you choose to set as the id
					},
					"initComplete": function(settings, json) {
						//console.log(json);
						//$j('#list_data').removeClass("sorting_asc");
						$('#list_education tr th').removeClass("sorting_asc");
						$('#list_education_info').remove();;
						$('#list_education_paginate').remove();
						$("#list_education_length").remove();
						$("#list_education_filter").remove();
						$('#list_education .no_urut').removeClass("sorting_asc");
					  }
				});
				table1.on('click', '#btn-add-education', function (e) {
					var target = $(this).attr('href');
					//alert(target);
					var url_form = target+'/form';
					//alert(target);
					$('#largeModalChild .modal-title').html("Input Data Pendidikan");
					$('#largeModalChild .modal-body').load(url_form, function() {
						 $('#largeModalChild').modal('show');
					});
					$('#largeModalChild .modal-footer').html('');
					$('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\"  id=\"tombol_batal_child\" onclick=\"tutup('largeModalChild');\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/add','form_input_education','list_education','');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
					e.preventDefault();
				   
				} );	
				table1.on('click', '.btn-edit-education', function (e) {
					var target = $(this).attr('href');
					var education_id = $(this).attr('role');
					
					$('#largeModalChild .modal-title').html("Input Data Pendidikan");
					$('#largeModalChild .modal-body').load(target+'/form?education_id='+education_id, function() {
						 $('#largeModalChild').modal('show');
					});
					$('#largeModalChild .modal-footer').html('');
					$('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\"  id=\"tombol_batal_child\" onclick=\"tutup('largeModalChild');\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/edit','form_input_education','list_education','"+education_id+"');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
					e.preventDefault();
				   
				} );	
				
				table1.on('click', '.btn-del-education', function (e) {
					var tr_id = $(this).attr('role');
					 var url_del = $(this).attr('href')+'/del';
					
					$('#largeModalChild .modal-title').html("Konfirmasi Hapus Data");
					$('#largeModalChild .modal-body').html("<h4>Yakin data akan dihapus?</h4>");//.css("text-align","center");
					$('#largeModalChild .modal-footer').html('');
					$('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" onclick=\"tutup('largeModalChild');\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_hapus\" onclick=\"hapus('"+url_del+"','"+tr_id+"','list_education');\" ><i class=\"fa fa-trash-o\"></i>&nbsp;Hapus</button>");
					e.preventDefault();
				});
				
				
			});
		
			</script>
				
			<?php
			}
		
		?>
        
        
        <script> 
		
		function simpan(url_save,form_id,table_id,child_id){
			 
			$(".input").removeClass("error");
			var serialis=$("#"+form_id).serialize();
			if(child_id!==''){
				if(table_id=="list_education"){
					serialis=serialis+'&education_id='+child_id;
				}
				if(table_id=="list_working"){
					serialis=serialis+'&working_id='+child_id;
				}
				if(table_id=="list_jabatan"){
					serialis=serialis+'&history_jabatan_id='+child_id;
				}
			}
			
			$.ajax({
				type:"POST",
				url: url_save,
				data: serialis+"&child=1",
				success: function(data, status) {
					//alert(data);
					var obj2 = JSON.parse(data);
					if (obj2.success==true){
						$('#largeModalChild .modal-body').html("<h5>"+obj2.message+"</h5>");
						$('#largeModalChild .modal-footer #tombol_batal_child').html("<i class=\"fa fa-times\"></i>&nbsp;Tutup");
						$('#largeModalChild .modal-footer #simpan_data').remove();
						if (  $.fn.DataTable.isDataTable( '#'+table_id ) ) {
							$('#'+table_id).DataTable().columns().search().draw();
						}
					}else{
						if( obj2.form_error !== undefined)
						{
							errorForm(obj2.form_error);
						}
					}
				}
			});										  
		}
		function hapus(url_hapus,id,table_id)
		{
			
			$.ajax({
					type:"POST",
					url: url_hapus,
					data: 'child_id='+id,
					success: function(data, status) {
						//alert(data);
						var obj2 = JSON.parse(data);
						if (obj2.success==true){
						
							$('#largeModalChild .modal-body').html("<h5>"+obj2.message+"</h5>");
							$('#largeModalChild .modal-footer #tombol_batal').html("<i class=\"fa fa-times\"></i>&nbsp;Tutup");
							$('#largeModalChild .modal-footer #tombol_hapus').remove();
							if (  $.fn.DataTable.isDataTable( '#'+table_id ) ) {
								$('#'+table_id).DataTable().columns().search().draw();
							}
						}else{
							$('#largeModalChild .modal-body').html("<h5>"+obj2.message+"</h5>");
							if( obj2.form_error !== undefined)
							{
								errorForm(obj2.form_error);
							}
						}
						
					}
				});							
			
		}
		function tutup(id_modal){
			
			$('#'+id_modal).modal('hide');
		}
		</script>
        <?php
		
		if($step==3){
		?> 
        
			<style>
		   #list_working tr td{
			  padding-top:2px;
			  padding-bottom:2px;
			  font-size:11px;
			  vertical-align:middle;
		   }
		   #list_working tr th{
			 vertical-align:middle;
			 font-size:11px;
			 background-color:#CCC;
		   }
			</style>
            <div class="row-form">
                 <span class="label" >NIK <small class="wajib"></small></span> : <?php echo $profil->nik;?>
                 
            </div>
			<div class="row-form">
             <span class="label" >Nama </span> : <?php echo $profil->NamaLengkap;?>
            </div>
           
           
            
            <div class="row-form">
                 <span class="label" >Jenis Kelamin <small class="wajib"></small></span> : <?php echo $profil->Kelamin;?>
                
            </div>
            
         
   
             <div class="row-form" style="margin-top:2px;">
             <input type="checkbox" name="tidak_ada_working" id="tidak_ada_working" style="margin-left:2px;"> Tidak Ada Riwayat Pekerjaan
			<table  id="list_working"  class="table table-bordered table-hover dataTable" style="margin-top:4px; width:100%" >
                <thead>
                <tr >
                    <th class="no_urut" style="width:25px;">No</th>
                    <th style="width:105px;">Institusi/Perusahaan</th>
                    <th style="width:125px;">Jabatan</th>
                    <th style="width:120px;">Bidang Garapan</th> 
                    <th style="width:55px;">Tahun</th>
                    <th style="width:90px;">Lokasi</th>
                 <th style="width:50px;text-align:center"><?php echo $TombolTambahWorking;?></th>
                </tr>
                </thead>
               
			</table>  
             </div>
			
		  <!-- <script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> 
			<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>-->
			<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
			<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> 	 		
			<script> 
			
			//var $m = jQuery.noConflict();
			$(document).ready(function() {
				//alert("<?php echo $url_tanggungan;?>/listdata");
				var table1=$('#list_working').DataTable({
					"processing": true,
					"serverSide": true,
					"ajax": {
						"url": "<?php echo $url_working;?>/listdata",
						"type": "POST"
					},
					"columnDefs": [
						{ "orderable": false, "targets": [0,1,2,3,4,5,6] },
						{"targets": [0,1,2,3,4,5,6],"className": "text-center"}
					 ],
					"columns": [
						{ "data": "No",'sortable': false,'className':'col-number' },
						{ "data": "Company" },
						{ "data": "Jabatan" },
						{ "data": "Garapan" },
						{ "data": "Tahun" },
						{ "data": "Lokasi" },
						{ "data": "Tombol",'className':'btn-action' }
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						$(nRow).attr('id', 'tr_' + aData.Kode); // or whatever you choose to set as the id
					},
					"initComplete": function(settings, json) {
						//console.log(json);
						//$j('#list_data').removeClass("sorting_asc");
						$('#list_working tr th').removeClass("sorting_asc");
						$('#list_working_info').remove();;
						$('#list_working_paginate').remove();
						$("#list_working_length").remove();
						$("#list_working_filter").remove();
						$('#list_working .no_urut').removeClass("sorting_asc");
					  }
				});
				table1.on('click', '#btn-add-working', function (e) {
					var target = $(this).attr('href');
					//alert(target);
					var url_form = target+'/form';
					
					$('#largeModalChild .modal-title').html("Input Data Pekerjaan");
					$('#largeModalChild .modal-body').load(url_form, function() {
						 $('#largeModalChild').modal('show');
					});
					$('#largeModalChild .modal-footer').html('');
					$('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\"  id=\"tombol_batal_child\" onclick=\"tutup('largeModalChild');\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/add','form_input_working','list_working','');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
					e.preventDefault();
				   
				} );	
				table1.on('click', '.btn-edit-working', function (e) {
					var target = $(this).attr('href');
					var working_id = $(this).attr('role');
					
					$('#largeModalChild .modal-title').html("Input Data Pekerjaan");
					$('#largeModalChild .modal-body').load(target+'/form?working_id='+working_id, function() {
						 $('#largeModalChild').modal('show');
					});
					$('#largeModalChild .modal-footer').html('');
					$('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\"  id=\"tombol_batal_child\" onclick=\"tutup('largeModalChild');\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/edit','form_input_working','list_working','"+working_id+"');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
					e.preventDefault();
				   
				} );	
				
				table1.on('click', '.btn-del-working', function (e) {
					var tr_id = $(this).attr('role');
					 var url_del = $(this).attr('href')+'/del';
					
					$('#largeModalChild .modal-title').html("Konfirmasi Hapus Data");
					$('#largeModalChild .modal-body').html("<h4>Yakin data akan dihapus?</h4>");//.css("text-align","center");
					$('#largeModalChild .modal-footer').html('');
					$('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" onclick=\"tutup('largeModalChild');\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_hapus\" onclick=\"hapus('"+url_del+"','"+tr_id+"','list_working');\" ><i class=\"fa fa-trash-o\"></i>&nbsp;Hapus</button>");
					e.preventDefault();
				});
				
				
			});
		
			</script>
				
		<?php
        }
		if($step==4){
		?> 
        
			<style>
		   #list_jabatan tr td{
			  padding-top:2px;
			  padding-bottom:2px;
			  font-size:11px;
			  vertical-align:middle;
		   }
		   #list_jabatan tr th{
			 vertical-align:middle;
			 font-size:11px;
			 background-color:#CCC;
		   }
			</style>
            <div class="row-form">
                 <span class="label" >NIK <small class="wajib"></small></span> : <?php echo $profil->nik;?>
                 
            </div>
			<div class="row-form">
             <span class="label" >Nama </span> : <?php echo $profil->NamaLengkap;?>
            </div>
           
           
            
            <div class="row-form">
                 <span class="label" >Jenis Kelamin <small class="wajib"></small></span> : <?php echo $profil->Kelamin;?>
                
            </div>
            
         
   
             <div class="row-form" style="margin-top:2px;">
             <input type="checkbox" name="tidak_ada_jabatan" id="tidak_ada_jabatan" style="margin-left:2px;"> Tidak Ada Riwayat Jabatan
			<table  id="list_jabatan"  class="table table-bordered table-hover dataTable" style="margin-top:4px; width:100%" >
                <thead>
                <tr >
                    <th class="no_urut" style="width:25px;">No</th>
                    <th style="width:130px;">Jabatan</th>
                    <th style="width:130px;">Bidang Garapan</th>
                    <th style="width:90px;">Mulai Berlaku</th>
                    <th style="width:90px;">Akhir Berlaku</th> 
                    <th style="width:55px;">Sedang Menjabat</th>
                    <th style="">Keterangan</th>
                 <th style="width:50px;text-align:center"><?php echo $TombolTambahJabatan;?></th>
                </tr>
                </thead>
               
			</table>  
             </div>
			
		  <!-- <script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> 
			<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>-->
			<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
			<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> 	 		
			<script> 
			
			//var $m = jQuery.noConflict();
			$(document).ready(function() {
				//alert("<?php echo $url_jabatan;?>/listdata");
				var table1=$('#list_jabatan').DataTable({
					"processing": true,
					"serverSide": true,
					"ajax": {
						"url": "<?php echo $url_jabatan;?>/listdata",
						"type": "POST"
					},
					"columnDefs": [
						{ "orderable": false, "targets": [0,1,2,3,4,5,6] },
						{"targets": [0,1,2,3,4,5,6],"className": "text-center"}
					 ],
					"columns": [
						{ "data": "No",'sortable': false,'className':'col-number' },
						{ "data": "Jabatan" },
						{ "data": "BidangGarapan" },
						{ "data": "MulaiBerlaku" },
						{ "data": "AkhirBerlaku" },
						{ "data": "SedangMenjabat" },
						{ "data": "Keterangan" },
						{ "data": "Tombol",'className':'btn-action' }
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						$(nRow).attr('id', 'tr_' + aData.Kode); // or whatever you choose to set as the id
					},
					"initComplete": function(settings, json) {
						//console.log(json);
						//$j('#list_data').removeClass("sorting_asc");
						$('#list_jabatan tr th').removeClass("sorting_asc");
						$('#list_jabatan_info').remove();;
						$('#list_jabatan_paginate').remove();
						$("#list_jabatan_length").remove();
						$("#list_jabatan_filter").remove();
						$('#list_jabatan .no_urut').removeClass("sorting_asc");
					  }
				});
				table1.on('click', '#btn-add-jabatan', function (e) {
					var target = $(this).attr('href');
					//alert(target);
					var url_form = target+'/form';
					
					$('#largeModalChild .modal-title').html("Input Data Jabatan");
					$('#largeModalChild .modal-body').load(url_form, function() {
						 $('#largeModalChild').modal('show');
					});
					$('#largeModalChild .modal-footer').html('');
					$('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\"  id=\"tombol_batal_child\" onclick=\"tutup('largeModalChild');\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/add','form_input_jabatan','list_jabatan','');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
					e.preventDefault();
				   
				} );	
				table1.on('click', '.btn-edit-jabatan', function (e) {
					var target = $(this).attr('href');
					var jabatan_id = $(this).attr('role');
					
					$('#largeModalChild .modal-title').html("Input Data Jabatan");
					$('#largeModalChild .modal-body').load(target+'/form?jabatan_id='+jabatan_id, function() {
						 $('#largeModalChild').modal('show');
					});
					$('#largeModalChild .modal-footer').html('');
					$('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\"  id=\"tombol_batal_child\" onclick=\"tutup('largeModalChild');\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/edit','form_input_jabatan','list_jabatan','"+jabatan_id+"');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
					e.preventDefault();
				   
				} );	
				
				table1.on('click', '.btn-del-jabatan', function (e) {
					var tr_id = $(this).attr('role');
					 var url_del = $(this).attr('href')+'/del';
					
					$('#largeModalChild .modal-title').html("Konfirmasi Hapus Data");
					$('#largeModalChild .modal-body').html("<h4>Yakin data akan dihapus?</h4>");//.css("text-align","center");
					$('#largeModalChild .modal-footer').html('');
					$('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" onclick=\"tutup('largeModalChild');\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_hapus\" onclick=\"hapus('"+url_del+"','"+tr_id+"','list_jabatan');\" ><i class=\"fa fa-trash-o\"></i>&nbsp;Hapus</button>");
					e.preventDefault();
				});
				
				
			});
		
			</script>
				
		<?php
        }
	
		
		if($step==5 and (trim($profil->status_pendaftaran)=="" or trim($profil->status_pendaftaran)=="draft_biodata")){
		?> 
        <?php //echo "<pre>";print_r($profil);echo "</pre>";    ?>  
         <div class="row-form">
				 <span class="label" >Nama Lengkap </span>  <?php echo $profil->NamaLengkap;?>
				</div>
               
				<div class="row-form">
					 <span class="label" >Nama Panggilan </span> <?php echo $profil->NamaPanggilan;?>
				</div>
			   <div class="row-form">
					 <span class="label" >NIK  </span> <?php echo $profil->NIK;?>
					 
				</div>
				<div class="row-form">
					 <span class="label" >No KK  </span> <?php echo $profil->NoKK;?>
					 
				</div>
				<div class="row-form">
					 <span class="label" >Jenis Kelamin </span> <?php echo $profil->JENIS_KELAMIN;?>
					
				</div>
				<div class="row-form">
					 <span class="label" >Status Pernikahan </span> <?php echo $profil->StatusPerkawinan;?>
					
			   </div>
               <div class="row-form" >
				 <span class="label" >Nama Istri/Suami</span>  <?php echo $profil->nama_pasangan;?>
				</div>
				 <div class="row-form">
				 <span class="label" >Tempat Lahir </span><?php echo $profil->tempat_lahir;?>
				</div>
				 <div class="row-form">
				 <span class="label" >Tanggal Lahir  </span><?php echo $profil->TanggalLahirDetail['Lengkap'];?>
				
				</div>
				 <div class="row-form">
				 <span class="label" >RT/RW</span><?php echo $profil->RT;?>/<?php echo $profil->RW;?>
				</div>
				<div class="row-form">
				  <span class="label">Alamat <small class="wajib"></small></span><?php echo $profil->ALAMAT1;?>
				
			   </div>
			   <div class="row-form">
					 <span class="label" >Provinsi</span> <?php echo $profil->NamaProvinsi;?>
					
			   </div>
			  <div class="row-form">
				  <span class="label">Kab./Kota  </span> <?php echo $profil->NamaKabupaten;?>
				
					
			   </div>
			  <div class="row-form">
			   <span class="label">Kecamatan  </span> <?php echo $profil->KodeKecamatan;?>
				
			  </div>
				
			   <div class="row-form">
				  <span class="label">Kelurahan/Desa  </span> <?php echo $profil->KodeDesa;?>
				 
			   </div>
			   <div class="row-form">
					 <span class="label" >Agama</span> <?php echo $profil->Agama; ?>
					
			   </div>
				<div class="row-form">
					 <span class="label" >Handphone </span> <?php echo $profil->NO_HP;?>
				</div>
				
				<div class="row-form">
					 <span class="label" >No. Telepon </span><?php echo $profil->NO_TELP;?>
					
				</div>
				<div class="row-form">
				  <span class="label">E-Mail </span><?php echo $profil->email;?>
			   </div>
			   <div class="row-form">
			  <span class="label" >MCP/TPK</span> <?php echo $profil->NamaTPK;?>
				
			 </div>
			  <div class="row-form">
			  <span class="label" >Kelompok</span> <?php echo $profil->NamaKelompok;?>
					
			 </div>
			  <div class="row-form">
			  <span class="label" >Kelompok Harga</span> <?php echo $profil->KelompokHargaNama;?>
				
			 </div>
             <div class="row-form" style="text-align:center">
			  <span class="label" >No Anggota</span> <input type="text" class="input" name="no_anggota" id="no_anggota" style="font-size:1.6em;text-align:center" size="6" value="<?php echo str_ireplace("X","",strtoupper($profil->NoAnggota));?>"/>
				
			 </div>
              <div class="row-form">
					 <span class="label" >Aktif Periode <small class="wajib"></small></span>
					<span style="display:inline-block">
					<?php
			 //print_r($pilihan_periode);
			  
					$aktif_periode=isset($_POST['periode_aktif'])?$_POST['periode_aktif']:$profil->periode_mulai_aktif;
					?>
					<label  style="font-weight:normal">  
					<input type="radio"  name="periode_aktif" id="periode_aktif0" value="<?php echo $pilihan_periode['current']['periode_id'];?>"  <?php echo ($aktif_periode==$pilihan_periode['current']['periode_id'])?"checked":"";?>  />
							  Berjalan (<?php echo $pilihan_periode['current']['label'];?>)</label> 
					<label  style="margin-left:10px;font-weight:normal"> 
				<input type="radio" name="periode_aktif" id="periode_aktif1" value="62"  <?php echo ($aktif_periode==$pilihan_periode['next']['periode_id'])?"checked":"";?> />
							  Berikutnya (<?php echo $pilihan_periode['next']['label'];?>)</label>
					</span>
			</div>
            <div class="row-form">
				  <span class="label"> </span><i>Status keanggotaan akan aktif apabila populasi telah di verifikasi dan telah melakukan setor simpanan</i>
				
			   </div>
             <div class="row-form" style="text-align:center">
			   <?php echo $TombolCetak;?>
				
			 </div>
             <?php if(trim($profil->status_pendaftaran)=="" or trim($profil->status_pendaftaran)=="draft_biodata"){?>
              <div class="row-form">
					
					 <textarea id="Pernyataan" name="Pernyataan" rows="4" cols="60" class="Pernyataan" readonly="readonly">Saya menyatakan bahwa data yang diisikan sudah terkonfirmasi dengan benar. Apabila saya mengisi data tidak sesuai dengan sebenarnya, saya bersedia menerima konsekuesi baik administrasi KPBS maupun hukum negara.</textarea><br />
						<input type="checkbox" name="konfirmasi" id="konfirmasi"> Saya telah membaca dan menyetujui pernyataan di atas.
					  
			 </div>
            <?php }?>
        <?php
		}
		
		?>
        </div>
        </form>
        
        <?php
		if($step==5){
		?> 
        
        <form id="uploadimage" action="<?php echo $url_action; ?>/save" method="post" enctype="multipart/form-data" target="media-upload">
         <div class="responsive-form" >
         <input type="hidden" class="input" name="current_step" id="current_step2" size="2" value="<?php echo $step;?>"/>
         <input type="hidden" class="input" name="nik_lama" id="nik_lama" size="15" value="<?php echo $nik_lama;?>"/>
         <input type="hidden" class="input" name="current_child" id="current_child" size="2" value=""/>
         <script>
		$(document).ready(function () {
			
			$("#uploadimage").on('submit',(function() {
				
				//e.preventDefault();
				var current_step = $('#current_step2').val();
				//$("#message").empty();
				//alert(current_step);
				//alert('<?php echo $url_action; ?>/save');
				$('#rsLoaderUpload').show();
				$.ajax({
					url: '<?php echo $url_action; ?>/save', // Url to which the request is send
					type: "POST",             // Type of request to be send, called as method
					data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
					contentType: false,       // The content type used when sending data to the server.
					cache: false,             // To unable request pages to be cached
					processData:false,        // To send DOMDocument or non processed data file it is set to false
					success: function(data)   // A function to be called if request succeeds
					{
						//alert(data);
						
						//$("#media-testing").html(data);
						$('#rsLoaderUpload').hide();
						var obj2 = JSON.parse(data);
						$("#media-testing").html(obj2.message);
						if (obj2.success==true){
						 // var progress_line = $('.f1-steps').find('.f1-progress-line');
						  //bar_progress(progress_line, 'right');
						//  $('#btn-next').hide();
						  //$('#btn-selesai').show();
						  $("#err_file_error").html(obj2.message);
						}else{
							alert(obj2.message);
							if( obj2.form_error !== undefined)
							{
								errorForm(obj2.form_error);
							}
						}
					}
				});
			}));
			$(".file_foto").change(function(e) {
				var child_id= $(this).attr('role');
				$("#current_child").val(child_id);
				//$("#message").empty(); // To remove the previous error message
				
				var file = this.files[0];
				var imagefile = file.type;
				var match= ["image/jpeg","image/png","image/jpg"];
				if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
				{
					$('#previewing'+child_id).attr('src','<?php echo $url_nofoto;?>');
					$("#message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
					return false;
				}
				else
				{
					
					var reader = new FileReader();
					reader.onload = imageIsLoaded;
					reader.readAsDataURL(this.files[0]);
					var uk=(file.size/1000);
					$('#err_file_error'+child_id).html(accounting.formatNumber(uk, 2,".",",")+'KB');
				}
			});
			function imageIsLoaded(e) {
				console.log(e);
				child_id=$("#current_child").val();
				$("#file_foto"+child_id).css("color","green");
				$('#image_preview'+child_id).css("display", "block");
				
				$('#previewing'+child_id).attr('src', e.target.result);
				//$('#previewing').attr('width', '250px');
				//$('#previewing').attr('height', '230px');
				
			};
		});
		</script>
      	<div class="row" style="margin-left:3px;">
          <div class="col-xs-5" style="border:1px solid #999;margin:2px 2px 2px 2px;padding-top:3px;">
          <div class="row-form" style="text-align:center;">
               <strong><?php echo $profil->NamaLengkap;?></strong>
            </div>
            <div class="row-form" style="text-align:center;">
            
                <div id="image_preview_parent"><img id="previewing_parent" src="<?php echo $profil->url_foto;?>" height="250"  /></div>
            </div>
             
            <div class="row-form" >
            <input type="hidden" class="input" name="pilih_nama[]"  size="2" value="<?php echo $profil->NamaLengkap;?>"/>
            <input type="hidden" class="input" name="pilih_upload[]"  size="2" value="parent"/>
            <input type="hidden" class="input" name="tanggungan_id[]"  size="4" value=""/>
            <input type="file" name="file_foto[]" id="file_foto_parent"  class="input file_foto" size="35"  role="_parent" >
           
            
           
            </div>
            <div class="row-form">
            <i>Ukuran : 30 - 500KB</i>
           
            </div>
            <div class="row-form">
            <button type="submit"  class="btn btn-primary btn-xs" value="upload" name="upload" ><i class="fa fa-upload"></i> Upload Foto</button>
            <img src="<?php echo $theme_path;?>images/loading50.gif" id="rsLoaderUpload_parent" style="display:none; width:16px;" />
            <span id="err_file_error_parent"></span>
            </div>
            
         </div>
        <?php 
		//echo "<pre>";print_r($profil->Tanggungan);echo "</pre>";
		while($child=current($profil->Tanggungan)){
		?>
          <div class="col-xs-5" style="border:1px solid #999;margin:2px 2px 2px 2px;padding-top:3px;">
           <div class="row-form"  style="text-align:center;">
                <strong><?php echo $child->Nama;?> </strong>
            </div>
            <div class="row-form" style="text-align:center;">
            
                <div id="image_preview<?php echo $child->ID;?>"><img id="previewing<?php echo $child->ID;?>" src="<?php echo $child->url_foto;?>" height="250" /></div>
            </div>
           
            <div class="row-form">
            <input type="hidden" class="input" name="pilih_nama[]"  size="2" value="<?php echo $child->Nama;?>"/>
            <input type="hidden" class="input" name="pilih_upload[]"  size="2" value="child"/>
            <input type="hidden" class="input" name="tanggungan_id[]"  size="4" value="<?php echo $child->ID;?>"/>
            <input type="file" name="file_foto[]" id="file_foto<?php echo $child->ID;?>"  class="input file_foto" size="35"  role="<?php echo $child->ID;?>" >
           
            
           
            </div>
            <div class="row-form">
            <i>Ukuran : 30 - 500KB</i>
           
            </div>
            <div class="row-form">
            <button type="submit"  class="btn btn-primary btn-xs" value="upload" name="upload" ><i class="fa fa-upload"></i> Upload Foto</button>
            <img src="<?php echo $theme_path;?>images/loading50.gif" id="rsLoaderUpload<?php echo $child->ID;?>" style="display:none; width:16px;" />
            <span id="err_file_error<?php echo $child->ID;?>"></span>
            </div>
            
         </div>
         <?php 
		 next($profil->Tanggungan);
		}
		 ?>
        
       </div>
    </div>
     <div id="media-testing"></div>
    </form>
	<?php
    }
    ?>
          
