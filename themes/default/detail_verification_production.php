
<link rel="stylesheet" href="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
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
 <!-- form start -->
            <form id="form_input_data">
          <div class="box box-primary">
           
            <!-- /.box-header -->
           
              <div class="box-body">
              <div class="responsive-form" >
              	<div class="row-form">
                    <span class="label" >Kategori </span>: <?php echo $detail->category_name;?>                
                </div>
               <div class="row-form">
                    <span class="label" >Kontraktor </span>: <?php echo $detail->contractor_name;?>                 

                </div>
               <div class="row-form">
                    <span class="label" >Tanggal </span>: <?php echo $detail->tgl;?>
                 
                </div>
               <div class="row-form">
                    <span class="label" >Shift </span>: <?php echo $detail->contractor_name;?>                 

                </div>
                 <div class="row-form">
                        <span class="label" >Verifikasi Dibuat </span>: 
                     <?php echo trim($detail->created_time)<>""?$detail->created_time:"-";?>
                      
                  </div>
                  <div class="row-form">
                        <span class="label" >Verifikasi Diedit </span>: 
                   <?php echo trim($detail->lastupdated_time)<>""?$detail->lastupdated_time:"-";?>
                      
                  </div>
                 <div class="row-form">
                        <span class="label" >Status Verifikasi </span>: 
                     <?php echo (isset($detail->matrix_state) or trim($detail->matrix_state)<>"")?$detail->matrix_state:"Belum diset";?>
                      
                  </div>
                  <div class="row-form">
                        <span class="label" >Data Diverifikasi </span>: 
                     <?php echo trim($detail->verified_time)<>""?$detail->verified_time:"-";?>
                      
                  </div>
             </div>
          </div>
           
          </div>
          <!-- /.box -->
          
           <div class="box box-primary">
            <!-- /.box-header -->
              <div class="box-body">
              <table  id="list_ritase"  class="table table-bordered table-hover dataTable" style="margin-top:4px; width:100%;display:none;" >
                    <thead>
                    <tr >
                        <th class="no_urut" style="width:4%">No</th>
                        <th style="width:10%;">Tanggal</th>
                        <th style="width:55px;">Shift</th>
                        <th style="width:120px;">PIT</th>
                        <th style="width:90px;">Dump Truck</th>
                        <th style="">Tujuan</th>
                        <th style="width:10%;">Ritase</th>
                        <th style="width:110px;">Verifikasi</th>
                     <th style="width:10%;"><?php echo $tombol_gangguan;?></th>
                    </tr>
                    </thead>
    
                </table>
                <table  id="list_ritase_rehandling"  class="table table-bordered table-hover dataTable" style="margin-top:4px; width:auto;display:none;width:100%;" >
                    <thead>
                    <tr >
                        <th class="no_urut" style="width:25px;">No</th>
                        <th style="width:80px;">Tanggal</th>
                        <th style="width:55px;">Shift</th>
                         <th style="width:150px;">Barge</th>
                        <th style="width:150px;">Dome Asal</th>
                        
                        <th style="width:90px;">Dump Truck</th>  
                        <th style="width:70px;">Ritase</th>
                        <th style="width:110px;">Verifikasi</th>
                     <th style="width:10%;"><?php echo $tombol_gangguan;?></th>
                    </tr>
                    </thead>
    
                </table>
               
                
              </div>
              
              <ul class="timeline timeline-inverse">
                  <!-- timeline time label -->
                  <?php
				  if(!empty($detail->progress_verification)){ 
					  while($data=current($detail->progress_verification)){
						  $judul="Created Draft Verification";
						  $icon="fa fa-file-text-o";
						  if($data->matrix_state=="verified"){
							  $judul="Verified ".$detail->category_name;
							  $icon="fa fa-check";
						  }
						  if($data->matrix_state<>""){
						  ?>
					 
					  <!-- timeline item -->
						  <li>
							<i class="<?php echo $icon; ?> bg-blue"></i>
							<div class="timeline-item">
							  <span class="time"><i class="fa fa-clock-o"></i> <?php echo $data->lastupdated_detail['LengkapSingkatan']." ".$data->lastupdated_detail['Jam']; ?></span>
							  <h3 class="timeline-header"><a href="#"><?php echo $data->verifier->Name; ?></a> <?php echo $judul; ?></h3>
							</div>
						  </li>
					  <!-- END timeline item -->
					 <?php
						  }// end of if($data->matrix_state<>""){
					 next($detail->progress_verification);
					  }
				}
				 ?>
                 
                </ul>
          </div>
  </form>
<div id="media-test"></div>
<div class="modal fade" id="largeModalChild" tabindex="-1" role="dialog" aria-labelledby="largeModalChild" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            
            <h4 class="modal-title" id="myModalLabel">Input Data</h4>
        </div>
        <div class="modal-body">
          <!--  <iframe id="media-create-report" style="width:100%;height:300px"></iframe>-->
        </div>
      <div class="modal-footer">


      </div>
    </div>
  </div>
</div><!-- end of modal-->
<!-- <script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>
DataTables -->
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

 <script>

var $m = jQuery.noConflict();

$m(document).ready(function () {
	$m.fn.dataTable.ext.errMode = 'none';
	
	<?php if($detail->category_id <>"" and $detail->tgl <>"" and  $detail->shift<>"" and  $detail->contractor_id<>""){;?>
		load_datatable(<?php echo $detail->category_id;?>,'<?php echo $detail->tgl;?>',<?php echo $detail->shift;?>,<?php echo $detail->contractor_id;?>);
	<?php
	}
	?>
	
	
	function load_datatable(category_id,tanggal,shift_id,contractor_id){
		//alert("<?php echo $url_list_ritase;?>/"+category_id);
		if(category_id==1){
			$m("#list_ritase").show();
			$m("#list_ritase_rehandling").hide();
		}
		if(category_id==2){
			$m("#list_ritase_rehandling").show();
			$m("#list_ritase").hide();
			
		}
		//if(category_id!='' && tanggal!='' && shift_id!='' && contractor_id!=''){
			$m('#list_ritase').DataTable({
						"processing": true,
						"destroy": true,
						"serverSide": true,
						"ajax": {
							"url": "<?php echo $url_list_ritase;?>/"+category_id,
							"type": "POST",
							"data": {
								"contractor_id": contractor_id,
								"tanggal":tanggal,
								"shift_id": shift_id
							}						
						},
						"columnDefs": [
							{ "orderable": false, "targets": [0,1,2,3,4,5,6,7,8] },
							{"targets": [0,1,2,3,4,6,7,8],"className": "text-center"},
						 ],
						"columns": [
							{ "data": "No" },
							{ "data": "Tanggal" },
							{ "data": "shift" },
							{ "data": "pit" },
							{ "data": "dump_truck" },
							{ "data": "tujuan" },
							{ "data": "ritase" },
							{ "data": "verifikasi" },
							{ "data": "Aksi",'className':'btn-action' }
						],
						'fnCreatedRow': function (nRow, aData, iDataIndex) {
							$m(nRow).attr('id', 'tr_' + aData.Kode); // or whatever you choose to set as the id
						},
						"initComplete": function(settings, json) {
							console.log(json);
							$m('#list_ritase').removeClass("sorting_asc");
							$m('#list_ritase tr th').removeClass("sorting_asc");
							$m('#list_ritase_info').remove();;
							$m('#list_ritase_paginate').remove();
							$m("#list_ritase_length").remove();
							$m("#list_ritase_filter").remove();
						  }
				});
			$m('#list_ritase_rehandling').DataTable({
						"processing": true,
						"destroy": true,
						"serverSide": true,
						"ajax": {
							"url": "<?php echo $url_list_ritase;?>/"+category_id,
							"type": "POST",
							"data": {
								"contractor_id": contractor_id,
								"tanggal":tanggal,
								"shift_id": shift_id
							}						
						},
						"columnDefs": [
							{ "orderable": false, "targets": [0,1,2,3,4,5,6,7,8] },
							{"targets": [0,1,2,3,4,6,7,8],"className": "text-center"},
						 ],
						"columns": [
							{ "data": "No" },
							{ "data": "Tanggal" },
							{ "data": "shift" },
							{ "data": "barge_name" },
							{ "data": "dome_asal" },							
							{ "data": "dump_truck" },
							{ "data": "ritase" },
							{ "data": "verifikasi" },
							{ "data": "Aksi",'className':'btn-action' }
						],
						'fnCreatedRow': function (nRow, aData, iDataIndex) {
							$m(nRow).attr('id', 'tr_' + aData.Kode); // or whatever you choose to set as the id
							$m('#list_ritase_rehandling').removeClass("sorting_asc");
							$m('#list_ritase_rehandling tr th').removeClass("sorting_asc");
							$m('#list_ritase_rehandling_info').remove();;
							$m('#list_ritase_rehandling_paginate').remove();
							$m("#list_ritase_rehandling_length").remove();
							$m("#list_ritase_rehandling_filter").remove();
						},
						"initComplete": function(settings, json) {
							//console.log(json);
							
						  }
				});
		//}
	}
	
	
});
function tutup(id_modal){
	$m('#'+id_modal).modal('hide');
}
function detail_ritase(url,category_id){
	
	var url=url+'/'+category_id;
	
	$m('#largeModalChild .modal-title').html("Detail Data Ritase");
	
	$m('#largeModalChild .modal-body').load(url, function() {
		 $m('#largeModalChild').modal('show');
	});
	$m('#largeModalChild .modal-footer').html('');
	$m('#largeModalChild .modal-footer').html('');
	 $m('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" onclick=\"tutup('largeModalChild');\" ><i class=\"fa fa-times\"></i>&nbsp;Tutup</button>");
	
}
function form_verifikasi(url,category_id){
	
	var url=url+'/'+category_id;
	
	$m('#largeModalChild .modal-title').html("Verifikasi Data Ritase");
	
	$m('#largeModalChild .modal-body').load(url, function() {
		 $m('#largeModalChild').modal('show');
	});
	$m('#largeModalChild .modal-footer').html('');
	$m('#largeModalChild .modal-footer').html('');
	 $m('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" onclick=\"tutup('largeModalChild');\" ><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_simpan_ritase\" onclick=\"simpan('"+url+"/save','form_verifikasi_ritase');\" ><i class=\"fa fa-check\"></i>&nbsp;Verifikasi</button>");
	
}
function update(url,category_id){
	
	var url=url+'/'+category_id;
	$m('#largeModalChild .modal-title').html("Edit Data Ritase");
	
	$m('#largeModalChild .modal-body').load(url, function() {
		 $m('#largeModalChild').modal('show');
	});
	$m('#largeModalChild .modal-footer').html('');
	$m('#largeModalChild .modal-footer').html('');
	 $m('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" onclick=\"tutup('largeModalChild');\" ><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_simpan_ritase\" onclick=\"simpan('"+url+"/save','form_input_ritase');\" ><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
	
}
function simpan(url_save,form_entri){
	//var form_serialis=$j("#"+).serialize()
	$m(".input").removeClass("error");
		$m.ajax({
			type:"POST",
			url: url_save,
			data: $m("#"+form_entri).serialize(),
			success: function(data, status) {
				//alert(data);
				$m("#media-test").html(data);
				var obj2 = JSON.parse(data);
				if (obj2.success==true){
					//table.columns([0,1,2]).search($("#crProvinsi").val()).draw();
					//loaddata($j("#form_cari").serialize());
					//$( "#list_kota tbody" ).prepend( obj2.html );
					$m('#largeModalChild .modal-body').html("<h5>"+obj2.message+"</h5>");
					//$j('#largeModalChild .modal-footer #tombol_batal').html("<i class=\"fa fa-times\"></i>&nbsp;Tutup");
					$m('#largeModalChild .modal-footer #tombol_simpan_ritase').remove();
					if (  $m.fn.DataTable.isDataTable( '#list_ritase' ) ) {
						$m('#list_ritase').DataTable().columns().search().draw();
					}
				}else{
					alert(obj2.message);
					if( obj2.form_error !== undefined)
					{
						errorForm(obj2.form_error);
					}
				}
				
				// $('#largeModal').modal('hide');
				
				//$('#remoteModal').removeData('bs.modal');
				//$('#remoteModal .modal-content').html(data);
			}
		});	
}
function comboAjax(url_cmb,parentkode,idTarget,nilai,other_parameters,idloader, idframe)
	{
		//alert(url_cmb+'?nilai='+nilai+'&parentkode='+parentkode+'&'+other_parameters);
		$m("#"+idTarget).hide();
		$m("#"+idloader).show();
		$m.ajax({
			   type:'POST',
			   dataType:'html',
			   url:url_cmb,
			   data:'nilai='+nilai+'&parentkode='+parentkode+'&'+other_parameters,
			   success:function(msg){
				  //alert(msg);
				  var obj=JSON.parse(msg);
				   $m("#"+idloader).hide();
				   if (obj.kosong==false)
				   {	
					  $m("#"+idTarget).fadeIn();
					  $m("#"+idTarget).empty().append(obj.html);
				   }else{
					  // $("#"+idframe).fadeOut();
					  $m("#"+idTarget).fadeIn();
					  $m("#"+idTarget).empty().append(obj.html);
				   }
				   
			   }///akhisr sukses
		   }); //akhir $.ajax	
	}
</script>