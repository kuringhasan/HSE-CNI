
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
<?php
//echo "<pre>";print_r($detail);echo "</pre>";?>
 <!-- form start -->
           
          <div class="box box-primary">
           
            <!-- /.box-header -->
           
              <div class="box-body">
              <div class="responsive-form" >
              	<div class="row-form">
                    <span class="label" >Load Factor EXPIT </span> : <?php echo $detail->load_factor_expit;?> MT
                   

                </div>
               <div class="row-form">
                    <span class="label" >Load Factor Barging  </span> : <?php echo $detail->load_factor_barging;?>
                  
                   
                </div>
               <div class="row-form">
                    <span class="label" >Berlaku Dari </span> : <?php echo $detail->berlaku_mulai;?>
                 
                  
                </div>
               <div class="row-form">
                    <span class="label" >Berlaku Sampai </span> : <?php echo $detail->berlaku_sampai;?>
                </div>
                 <div class="row-form">
                    <span class="label" >Closed </span> : <?php echo $detail->closed;?>
                </div>
             </div>
          </div>
              <!-- /.box-body -->

              <div class="box-footer">
              
                
              </div>
          
          </div>
          <!-- /.box -->
  <div class="box box-primary">
   
    <!-- /.box-header -->
   
      <div class="box-body"> 
       <form id="form_input_data">
               <div class="responsive-form" >
              	 <div class="row-form"><span class="label" >Kontraktor</span>
                <select name="kontraktor" id="kontraktor"  class="input" style=";">
                  <?php
                        echo '<option value="">--Semua--</option>';
                        $List=$list_contractor;
                        while($data = each($List)) {
                            $kontraktor=isset($_POST['kontraktor'])?$_POST['kontraktor']:$detail->partner_id;
                           ?>
                  <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$detail->contractor_id?"selected":""; ?> > <?php echo $data['value'];?></option>
                  <?php
                       }
                        ?>
                  </select>
                  
                   <input type="text" class="input" name="load_factor_id" id="load_factor_id" size="3" value="<?php echo $detail->id;?>"/> 
            </div>
                <div class="row-form">
                   <button type="button" id="btn-recompute-data" class="btn btn-primary" title="Recompute adalah mengkonversi ritase ke dalam metrik tons"><i class="fa fa-fw fa-refresh"></i> Recompute Weight ..</button>
                    <img src="<?php echo $theme_path;?>images/loading50.gif" id="loader_recompute" style="display:none; width:16px;" />
                </div>
              </div>
          
          
 		</form>
           <div class="nav-tabs-custom" >
            <ul class="nav nav-tabs" >
              <li class="active" ><a href="#expit_report" data-toggle="tab">Stockpiling</a></li>
              <li><a href="#barging_report" data-toggle="tab">Barging</a></li>
            </ul>
            <div class="tab-content" >
            	 <div class="active tab-pane" id="expit_report" >
                 	  <table  id="list_expit_report"  class="table table-bordered table-hover dataTable" style="margin-top:4px; width:100%;" >
                    <thead>
                    <tr >
                        <th class="no_urut" style="width:4%">No</th>
                        <th style="width:10%;">Tanggal</th>
                        <th style="width:55px;">Shift</th>
                        <th style="width:120px;">PIT</th>
                        <th style="">Tujuan</th>
                        <th style="width:10%;">Total Ritase</th>
                        <th style="width:110px;">Load Factor</th>
                        <th style="width:110px;">Total Quantity</th>
                        <th style="width:110px;">Kontraktor</th>
                     <th style="width:10%;"><?php echo $tombol_gangguan;?></th>
                    </tr>
                    </thead>
    
                </table>
           </div><!-- end of plan_daily-->
                 <div class="tab-pane" id="barging_report">
                 	<table  id="list_ritase_rehandling"  class="table table-bordered table-hover dataTable" style="margin-top:4px; width:auto;width:100%;" >
                    <thead>
                    <tr >
                        <th class="no_urut" style="width:25px;">No</th>
                        <th style="width:80px;">Tanggal</th>
                        <th style="width:55px;">Shift</th>
                         <th style="width:150px;">Barge</th>
                        <th style="width:150px;">Dome Asal</th>
                        
                        <th style="width:90px;">Dump Truck</th>  
                        <th style="width:70px;">Ritase</th>
                        <th style="width:110px;">Load Factor</th>
                        <th style="width:110px;">Quantity</th>
                     <th style="width:10%;"><?php echo $tombol_gangguan;?></th>
                    </tr>
                    </thead>
    
                </table>
                 </div><!-- end of plan_weekly-->
               
                                  
            </div><!-- end of tab-content-->
          </div><!-- end of nav-tabs-custom-->
      </div>
  <!-- /.box-body -->
</div>    

    
<div id="media-test"> aa</div>
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
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>
<!-- DataTables -->

<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> 


 <script>

var $m = jQuery.noConflict();

$m(document).ready(function () {
	$m.fn.dataTable.ext.errMode = 'none';
	//
	load_datatable("stockpiling",<?php echo $load_factor_id;?>);
	
	$m('#btn-recompute-data').on( 'click', function () {   // for text boxes
		
		//alert('<?php echo $url_recompute;?>/save');
		$m('#loader_recompute').show();
		$m.ajax({
			type:"POST",
			url: '<?php echo $url_recompute;?>/save',
			data: $m("#form_input_data").serialize(),
			success: function(data, status) {
				alert(data);
				$m('#loader_recompute').show();
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
					if (  $m.fn.DataTable.isDataTable( '#list_ritase_rehandling' ) ) {
						$m('#list_ritase_rehandling').DataTable().columns().search().draw();
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
	
	});
	function load_datatable(category,loaf_factor_id){
		//alert("<?php echo $url_ritase;?>/"+category+'/'+loaf_factor_id);
		
		//if(category_id!='' && tanggal!='' && shift_id!='' && contractor_id!=''){
			var table1=$m('#list_expit_report').DataTable({
						"processing": true,
						"destroy": true,
						"serverSide": true,
						"ajax": {
							"url": "<?php echo $url_ritase;?>/"+category+'/'+loaf_factor_id,
							"type": "POST",
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
							{ "data": "tujuan" },
							{ "data": "total_ritase" },
							{ "data": "load_factor" },
							{ "data": "total_quantity" },
							{ "data": "contractor" },
							{ "data": "Aksi",'className':'btn-action' }
						],
						'fnCreatedRow': function (nRow, aData, iDataIndex) {
							$m(nRow).attr('id', 'tr_' + aData.ID); // or whatever you choose to set as the id
							$m(nRow).attr('class', 'details-control');
							console.log(aData.Detail)
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
			table1.on('click', '.details-control', function (e) {
				//alert('cek');
				var tr = $m(this).closest('tr');
				var row = table1.row( tr );
	
	
				if ( row.child.isShown() ) {
					// This row is already open - close it
					row.child.hide();
					tr.removeClass('shown');
				} else {
					// Open this row
					row.child( format(row.data()) ).show();
					//row.child("selamat").show();
					tr.addClass('shown');
	
				}
			} );
			/*$m('#list_ritase_rehandling').DataTable({
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
						},
						"initComplete": function(settings, json) {
							console.log(json);
							$m('#list_ritase_rehandling').removeClass("sorting_asc");
							$m('#list_ritase_rehandling tr th').removeClass("sorting_asc");
							$m('#list_ritase_rehandling_info').remove();;
							$m('#list_ritase_rehandling_paginate').remove();
							$m("#list_ritase_rehandling_length").remove();
							$m("#list_ritase_rehandling_filter").remove();
						  }
				});*/
		//}
	}
	
	
});
function format ( data ) {
	console.log(data);
		const { ID } = data;

	    html="";
		if (data.Detail.length>0)
		{
		 	var list_data=data.Detail;
			var no=1;

			

			var html="<div style=\"margin-left:10px;width:100%;\" class=\"media-child\">Ritase :<br /><table  border=\"1\" class=\"list-child table table-bordered dataTable\" style=\"width:auto;border-top:1px;text-align:left;\" ><tr style=\"border-top:1px;\"><th style=\"width:25px;text-align:center;\">No</th><th style=\"width:100px;text-align:left;\">Drum Truck</th><th style=\"width:60px;text-align:center;\">Ritase</th><th style=\"width:60px;text-align:center;\">Qty (Ton)</th><th style=\"width:60px;text-align:center;\">Tujuan</th><th style=\"width:60px;text-align:center;\">Dome</th></tr>";
			for (var key in list_data){
				
				html=html+'<tr><td style=\"text-align:center;\">'+no+'</td><td style=\"text-align:left;\">'+list_data[key].truck_nomor+'</td><td style=\"text-align:center;\">'+list_data[key].ritase+'</td><td style=\"text-align:center;\">'+list_data[key].quantity+'</td><td style=\"text-align:center;\">'+list_data[key].tujuan_pengangkutan+'</td><td style=\"text-align:center;\">'+list_data[key].dome_name+'</td></tr>';
				no++;
			}
			html=html+'</table><div>';
		}else{
			html="<div style=\"width:100%;text-align:center;\" class=\"media-no-child\">Tidak ada data rincian</div>";
		}
		return html;
	}
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
	 $m('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" onclick=\"tutup('largeModalChild');\" ><i class=\"fa fa-times\"></i>&nbsp;Tutup</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_simpan_ritase\" onclick=\"simpan('"+url+"/save','form_input_ritase');\" ><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
	
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
					if (  $m.fn.DataTable.isDataTable( '#list_ritase_rehandling' ) ) {
						$m('#list_ritase_rehandling').DataTable().columns().search().draw();
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