<link rel="stylesheet" href="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"> 


<style>
.btn-action{
	text-align:center;
}
.col-number{
	text-align:center;
}
#list_data tr th{
	vertical-align:middle;
	text-align:center;
}
.table tr th, table tr td{
	font-size:12px;
}
</style>
 <div class="row">
        <div class="col-xs-12">
         
          <div class="box">
            <div class="box-header text-center">
              <h3 class="box-title" id="rekap-title-tpk" >
            
              </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
           
             
              <table id="list_data" class="table table-bordered table-hover dataTable"  style="width:100%">
                <thead>
                <tr>
                  <th id="row_no2" style="width:25px;">No</th>
                  <th>TPK</th>
                  <th>Anggota</th>
                  <th>Tanggal</th>
                  <th>Nama Barang</th>
                  <th>Package</th>
                  <th>Qty</th>
                  <th >Harga Satuan (Rp.)</th>
                  <th >Jumlah (Rp.)</th>
                  </tr>
                </thead>
               <tfoot>
                <tr>
                  <th></th>
                  <th>&nbsp;</th>
                  <th>Total </th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
                  <th id="total_pedet_btn"></th>
                  <th id="total_pedet_jtn"></th>
                 </tr>
                
                <tr id="global_jumlah">
                  <th></th>
                  <th>&nbsp;</th>
                  <th>Total2 </th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
                  <th id="total_pedet_btn"></th>
                  <th id="total_pedet_jtn"></th>
                 </tr>
                </tfoot>
              </table>
              
              <button type="button" class="btn btn-primary btn-xs btn-refresh-last"><i class="fa fa-refresh"></i> Bulan <?php echo $bulan_lalu;?></button>
              <button type="button" class="btn btn-primary btn-xs btn-refresh-current"><i class="fa fa-refresh"></i> Bulan Berjalan</button>
          <button type="button" class="btn btn-primary btn-xs btn-export-excel"><i class="fa fa-file-excel-o"></i> Excel</button>
            <!-- <button type="button" class="btn btn-primary btn-xs btn-export-pdf"><i class="fa fa-file-pdf-o"></i> PDF</button> -->
             <img src="<?php echo $theme_path."/images/loader.gif";?>" style="display:none; height:8px;"  class="spinner_loading"/>
         </div>
            <!-- /.box-body -->
            
            
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

<div class="row">
    <div class="col-xs-12">
     
      <div class="box">
        <div class="box-header text-center">
          <h3 class="box-title" id="rekap-title" >
        
          </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
       
         
         <table id="list_rekap" class="table table-bordered table-hover dataTable"  style="width:100%">
            <thead>
                <tr>
                  <th rowspan="2" id="row_no2" style="width:25px;">No</th>
                  <th rowspan="2">Bulan</th>
                  <th rowspan="2">Nama Kelompok</th>
                  <th rowspan="2">Nama Barang</th>
                  <th rowspan="2">Harga Satuan</th>
                  <th colspan="3" >Jumlah</th>
                  <th rowspan="2" >Petugas</th>
                  <th rowspan="2" >Last Update</th>
                  <th rowspan="2" >Dibuat</th>
                  <th rowspan="2" style="width:40px;" >Locked</th>
                  </tr>
                <tr>
                  <th style="width:65px;">Package</th>
                  <th style="width:65px;">Qty</th>
                  <th style="width:65px;"> Harga</th>
                  </tr>
                </thead>
               <tfoot>
                <tr>
                  <th></th>
                  <th>&nbsp;</th>
                  <th>Total </th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
                   <th id="total_package1"></th>
                   <th id="total_qty1"></th>
                   <th id="total_harga1"></th>
                  <th ></th>
                  <th ></th>
                  <th ></th>
                  <th ></th>
                 </tr>
                
                <tr id="global_jumlah">
                  <th></th>
                  <th>&nbsp;</th>
                  <th>Total2 </th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
                   <th id="total_package2"></th>
                   <th id="total_qty2"></th>
                   <th id="total_harga2"></th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
                   <th>&nbsp;</th>
                  <th>&nbsp;</th>
                 </tr>
                </tfoot>
              </table>
              
              <button type="button" class="btn btn-primary btn-xs btn-refresh-last"><i class="fa fa-refresh"></i> Bulan <?php echo $bulan_lalu;?></button>
              <button type="button" class="btn btn-primary btn-xs btn-refresh-current"><i class="fa fa-refresh"></i> Bulan Berjalan</button>
          <button type="button" class="btn btn-primary btn-xs btn-export-excel"><i class="fa fa-file-excel-o"></i> Excel</button>
       <!-- <button type="button" class="btn btn-primary btn-xs btn-export-pdf"><i class="fa fa-file-pdf-o"></i> PDF</button> -->
             <img src="<?php echo $theme_path."/images/loader.gif";?>" style="display:none; height:8px;"  class="spinner_loading"/>
             <form method="post" id="form-download"  target="media-download">
             <input type="hidden" name="dw_tpk" id="dw_tpk" size="3" />
             </form>
             <div id='progress_wrapper'  style="float:left">
                    <div id="progressor" ></div>
            </div>
             <iframe name="media-download" style="display:none;"></iframe>
      
     </div>
        <!-- /.box-body -->
        
        
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Input Data</h4>
        </div>
        <div class="modal-body">
           
        </div>
      <div class="modal-footer">
    
       
      </div>
    </div>
  </div>
</div><!-- end of modal-->

    
      
      <div id="media-test"></div>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>
<!-- DataTables -->

<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo $theme_path;?>js/accounting.js"></script>
<script>
 $(document).ready(function() {
	  
});
var $j = jQuery.noConflict();
  $j(document).ready(function() {
	  
    // $j('#list_data').removeClass("sorting_asc");
	var table=$j('#list_data').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax": {
						"url": "<?php echo $url_listdata;?>/periode",
						"type": "POST"
					},
					"columnDefs": [
						{ "orderable": false, "targets": [0,2,3,4,5] },
						{"targets": [0,2,3,4,5],"className": "text-center"}
					 ],
					"columns": [
						{ "data": "No",'sortable': false},
						{ "data": "tpk" },
						{ "data": "anggota" },
						{ "data": "tanggal" },
						{ "data": "brg_name" },
						{ "data": "jml_package" },
						{ "data": "jml_qty" },
						{ "data": "harga_satuan" },
						{ "data": "jml_harga" }
					
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						$j(nRow).attr('id', 'tr_' + aData.Kode); // or whatever you choose to set as the id
					},
					"initComplete": function(settings, json) {
						console.log(json);
						
						$j('#row_no').removeClass("sorting_asc");
						var element = document.getElementById('list_data_filter');
						var child = document.createElement('div');
						child.style.float = "right";
						child.innerHTML = '<select name="cr_tpk" data-column="0" class="form-control input-xm cr_tpk" id="cr_tpk1" onChange="pilih(this.value,0,\'cr_tpk\');" title="Pilih TPK">\n'+
						 '<option value="">-- TPK ---</option>\n'+
                        <?php
                         $tpk		=isset($_POST['cr_tpk'])?$_POST['cr_tpk']:"";
							$List=$ListTPK;
							while($data = each($List)) {
								
							   ?>
						  '<option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$tpk?"selected":""; ?> ><?php echo $data['value'];?></option>\n'+
						  <?php
						  
							}
						 ?>
					  '</select><input type="text" name="cr_tanggal" class="form-control input-xm cr_tanggal" id="cr_tanggal" onkeyup="pilih(this.value,1,\'cr_tanggal\');" placeholder="tanggal" size="10" /><select name="cr_bulan" data-column="5" class="form-control input-xm cr_bulan" id="cr_bulan1" onChange="pilih(this.value,5,\'cr_bulan\');" title="Pilih Bulan">\n'+
						 '<option value="">-- bulan ---</option>\n'+
							<?php
							 $bulan		=isset($_POST['cr_bulan'])?$_POST['cr_bulan']:"";
							$List=$list_bulan;
							while($data = each($List)) {
								
							   ?>
						  '<option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$bulan?"selected":""; ?> ><?php echo $data['value'];?></option>\n'+
						  <?php
						  
							}
						 ?>
				  '</select>\n'+
			  '<input type="text" name="cr_tahun" class="form-control input-xm cr_tahun" id="cr_tahun1" onkeyup="pilih(this.value,6,\'cr_tahun\');" placeholder="tahun" size="2">\n';
						
						var elementParent = element.parentNode;
						elementParent.insertBefore(child, element.nextSibling);
						 $("#cr_tanggal").datepicker({
							 format: 'dd/mm/yyyy',
							 autoclose: true
						 }).on("change", function() {
							pilih(this.value,1,'cr_tanggal')
							
						  });
						
					  }/*,
					  "drawCallback": function ( settings ) {
						  //console.log(settings);
				 		$j("#rekap-title-tpk").html(settings.json.title);
							
						},
					  "footerCallback": function ( row, data, start, end, display ) {
						  console.log(row);
						  var api = this.api(), data;
 
							// Remove the formatting to get integer data for summation
							var intVal = function ( i ) {
								return typeof i === 'string' ?
									i.replace(/[\$,]/g, '')*1 :
									typeof i === 'number' ?
										i : 0;
							};
				 			
							for (col_num = 2; col_num <= 10; col_num++) { 
								// Total over all pages
								total = api
									.column( col_num,{ page: 'all'} )
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
					 
								// Total over this page
								pageTotal = api
									.column( col_num, { page: 'current'} )
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
					 
								// Update footer
								var new_total=accounting.formatNumber(total, 0,".",",");
								$j( api.column( col_num ).footer() ).html(pageTotal+'/'+new_total);
								//$j("#global_jumlah").html(new_total);
								//$j( api.column( col_num ).footer(1) ).html(pageTotal);
							}
					  }*/
		});
	  
		$j("#list_data_filter").css("display","none");
		//var i ="";  // getting column index
		//var v =""; 
		$j('.search-by-name').on( 'keyup click', function () {   // for text boxes
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
		} );	
		var table2=$j('#list_rekap').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax": {
						"url": "<?php echo $url_listdata;?>/bulanan",
						"type": "POST"
					},
					"columnDefs": [
						{ "orderable": false, "targets": [0,2,3,4,5,6,7,11] },
						{"targets": [0,2,3,4,5,6,7,11],"className": "text-center"}
					 ],
					"columns": [
						{ "data": "No",'sortable': false},
						{ "data": "bulan" },
						{ "data": "kelompok_name" },
						{ "data": "brg_name" },
						{ "data": "harga_satuan" },
						{ "data": "jml_package" },
						{ "data": "jml_qty" },
						{ "data": "jml_harga" },
						{ "data": "petugas" },
						{ "data": "last_update" },
						{ "data": "created" },
						{ "data": "locked" }
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						$j(nRow).attr('id', 'tr_' + aData.Kode); // or whatever you choose to set as the id
					},
					"initComplete": function(settings, json) {
						console.log(json);
						
						$j('#row_no').removeClass("sorting_asc");
						var element = document.getElementById('list_rekap_filter');
						var child = document.createElement('div');
						child.style.float = "right";
						child.innerHTML = '<select name="cr_tpk" data-column="0" class="form-control input-xm cr_tpk" id="cr_tpk" onChange="pilih(this.value,0,\'cr_tpk\');" title="Pilih TPK">\n'+
						 '<option value="">-- TPK ---</option>\n'+
                        <?php
                         $tpk		=isset($_POST['cr_tpk'])?$_POST['cr_tpk']:"";
							$List=$ListTPK;
							while($data = each($List)) {
								
							   ?>
						  '<option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$tpk?"selected":""; ?> ><?php echo $data['value'];?></option>\n'+
						  <?php
						  
							}
						 ?>
					  '</select><select name="cr_bulan" data-column="5" class="form-control input-xm cr_bulan" id="cr_bulan" onChange="pilih(this.value,5,\'cr_bulan\');" title="Pilih Bulan">\n'+
						 '<option value="">-- bulan ---</option>\n'+
							<?php
							 $bulan		=isset($_POST['cr_bulan'])?$_POST['cr_bulan']:"";
							$List=$list_bulan;
							while($data = each($List)) {
								
							   ?>
						  '<option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$bulan?"selected":""; ?> ><?php echo $data['value'];?></option>\n'+
						  <?php
						  
							}
						 ?>
				  '</select>\n'+
			  '<input type="text" name="cr_tahun" class="form-control input-xm cr_tahun" id="cr_tahun" onkeyup="pilih(this.value,6,\'cr_tahun\');" placeholder="tahun" size="2">\n';
						
						var elementParent = element.parentNode;
						elementParent.insertBefore(child, element.nextSibling);
						
					  }/*,
					  "drawCallback": function ( settings ) {
						  //console.log(settings);
				 		$j("#rekap-title-tpk").html(settings.json.title);
							
						},
					  "footerCallback": function ( row, data, start, end, display ) {
						  console.log(row);
						  var api = this.api(), data;
 
							// Remove the formatting to get integer data for summation
							var intVal = function ( i ) {
								return typeof i === 'string' ?
									i.replace(/[\$,]/g, '')*1 :
									typeof i === 'number' ?
										i : 0;
							};
				 			
							for (col_num = 2; col_num <= 10; col_num++) { 
								// Total over all pages
								total = api
									.column( col_num,{ page: 'all'} )
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
					 
								// Total over this page
								pageTotal = api
									.column( col_num, { page: 'current'} )
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );
					 
								// Update footer
								var new_total=accounting.formatNumber(total, 0,".",",");
								$j( api.column( col_num ).footer() ).html(pageTotal+'/'+new_total);
								//$j("#global_jumlah").html(new_total);
								//$j( api.column( col_num ).footer(1) ).html(pageTotal);
							}
					  }*/
		});
		
		$j("#list_rekap_filter").css("display","none");
		$j("#list_rekap_length").css("display","none");
		$j("#list_rekap_info").css("display","none");
		//$j("#list_rekap_paginate").css("display","none");
		$j('.btn-export-excel').on( 'click', function () {   // for text boxes
		
			     $j(".spinner_loading").show();
				$j("#form-download").attr('action','<?php echo $url_export;?>');
				$j("#form-download").submit();
				$j(".spinner_loading").hide();
			
		} );
		$j('.btn-refresh-last').on( 'click', function () {   // for text boxes
			generate(false);
			
		} );
		$j('.btn-refresh-current').on( 'click', function () {   // for text boxes
			generate(true);
			
		} );
		$j('.btn-export-pdf').on( 'click', function () {   // for text boxes
			alert('Belum berfungsi');
			/*if($j('#dw_tahun').val()!==""){;
				$j("#form-download").attr('action','<?php echo $url_export;?>');
				$j("#form-download").submit();
			}else{
				alert('Tahun harus diisi');
			}*/
		} );		
      
  });
  
function generate(action){
	alert('<?php echo $url_generate;?>/bulanan/'+action);
		$j("#spinner_loading").fadeIn();
		$j.ajax({
			type:"POST",
			url: '<?php echo $url_generate;?>/bulanan/'+action,
			success: function(data, status) {
				$j("#spinner_loading").fadeOut();
				if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
					$j('#list_data').DataTable().columns().search().draw();
				}
				if (  $j.fn.DataTable.isDataTable( '#list_rekap' ) ) {
					$j('#list_rekap').DataTable().columns().search().draw();
				}
					
				
				
			}
		});										  
}
function pilih(nilai,index_data,el){
	
		$j('#dw_tpk').val(nilai);
		if(el=='cr_tpk'){
			$j('#list_data').DataTable().columns(0).search(nilai).draw();
			$j('#list_rekap').DataTable().columns(0).search(nilai).draw();
		}
		if(el=='cr_tahun'){
			
			if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
				$j('#list_data').DataTable().columns(index_data).search(nilai).draw();
			}
			if (  $j.fn.DataTable.isDataTable( '#list_rekap' ) ) {
				$j('#list_rekap').DataTable().columns(index_data).search(nilai).draw();
			}
		}
		if(el=='cr_bulan'){
				$j('#list_data').DataTable().columns(index_data).search(nilai).draw();
				$j('#list_rekap').DataTable().columns(index_data).search(nilai).draw();
		}
		if(el=='cr_tanggal'){
			
			
				$j('#list_data').DataTable().columns(index_data).search(nilai).draw();
				
		}
		
		
	
 }
function errorForm(msj_obj){
	 if (jQuery.isEmptyObject(msj_obj)==false)
	 {
		 var errors=msj_obj;
		for (var key in errors){
			$j("#"+key).addClass("error");
			$j("#err_"+key).html(errors[key]);
			$j("#err_"+key).addClass("lbl_error");
			$j("#err_"+key).show();
		}
	 }
}
function simpan(url_save){
	//alert(url_save);
		$j(".input").removeClass("error");
		$j.ajax({
			type:"POST",
			url: url_save,
			data: $j("#form_input_data").serialize(),
			success: function(data, status) {
			
				//$j("#media-test").html(data);
				var obj2 = JSON.parse(data);
				if (obj2.success==true){
					//table.columns([0,1,2]).search($("#crProvinsi").val()).draw();
					//loaddata($j("#form_cari").serialize());
					$( "#list_data tbody" ).prepend( obj2.html );
					$j('#largeModal .modal-body').html("<h5>"+obj2.message+"</h5>");
					$j('#largeModal .modal-footer #tombol_batal').html("<i class=\"fa fa-times\"></i>&nbsp;Tutup");
					$j('#largeModal .modal-footer #simpan_data').remove();
					if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
						$j('#list_data').DataTable().columns().search().draw();
					}
				}else{
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
function hapus(url_hapus,id)
{
	$j.ajax({
			type:"POST",
			url: url_hapus,
			data: 'nama='+id,
			success: function(data, status) {
				var obj2 = JSON.parse(data);
				if (obj2.success==true){
					//loaddata($j("#form_cari").serialize());
					//$('#largeModal .modal-footer').remove();
					//$('#largeModal .modal-body').html(obj2.pesan);
					$j('#tr_'+id).remove();
					$j('#largeModal .modal-body').html("<h5>"+obj2.message+"</h5>");
					$j('#largeModal .modal-footer #tombol_batal').html("<i class=\"fa fa-times\"></i>&nbsp;Tutup");
					$j('#largeModal .modal-footer #tombol_hapus').remove();
					if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
						$j('#list_data').DataTable().columns().search().draw();
					}
					
					
				}else{
					$j('#largeModal .modal-body').html("<h5>"+obj2.message+"</h5>");
					if( obj2.form_error !== undefined)
					{
						errorForm(obj2.form_error);
					}
				}
				
			}
		});							
	
}
function comboAjax(url_cmb,parentkode,idTarget,nilai,other_parameters,idloader, idframe)
	{
		//alert(url_cmb+'?nilai='+nilai+'&parentkode='+parentkode+'&'+other_parameters);
		$j("#"+idTarget).hide();
		$j("#"+idloader).show();
		$j.ajax({
			   type:'POST',
			   dataType:'html',
			   url:url_cmb,
			   data:'nilai='+nilai+'&parentkode='+parentkode+'&'+other_parameters,
			   success:function(msg){
				  //alert(msg);
				  var obj=JSON.parse(msg);
				   $j("#"+idloader).hide();
				   if (obj.kosong==false)
				   {	
					  $j("#"+idTarget).fadeIn();
					  $j("#"+idTarget).empty().append(obj.html);
				   }else{
					  // $("#"+idframe).fadeOut();
					  $j("#"+idTarget).fadeIn();
					  $j("#"+idTarget).empty().append(obj.html);
				   }
				   
			   }///akhisr sukses
		   }); //akhir $.ajax	
	}
</script>
