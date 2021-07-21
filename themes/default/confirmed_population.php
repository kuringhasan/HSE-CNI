
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
		
		});
		
		</script>