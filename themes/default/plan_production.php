<link rel="stylesheet" href="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"> 


<style>
.btn-action{
	text-align:center;
}
.col-number{
	text-align:center;
}
.text-center{
	text-align:center;
}

.table tr th, table tr td{
	font-size:12px;
	
}
.form-control{
	font-size:12px;
	
}
#list_data tr th{
	vertical-align:middle;
	text-align:center;
}
</style>
<style class="cp-pen-styles">
.pagination{
	margin:0 0 0 0;
}

@media screen and (max-width: 767px) {
   #list_data_wrapper .row .col-sm-3 {
	  float:left;
  }
   #list_data_wrapper .row .col-sm-9 div{
	  float:left;
  }
	.td-content{
		text-align:left;
	}
   #header-data {
    display: none;
  }
  #header-search  tr {
    display: block;
    position: relative;
    padding: 1.2em 0;

  }
  #header-search  tr:first-of-type {
    border-top: 1px solid #ccc;
  }

  #header-search  th {
    display: table-row;
	
	
  }
  #header-search th:before {
    content: attr(data-label);
    display: table-cell;
    font-weight: bold;
    padding: 0.2em 0.6em 0.8em 0;
    text-align: right;
  }
  #header-search th:before .form-control {
   	width:100%;
  }
  #header-search th:last-child:after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    border-bottom: 1px solid #ccc;
  }
  
  
  
  #list_data tr {
    display: block;
    position: relative;
    padding: 1.2em 0;
  }
  #list_data tr:first-of-type {
    border-top: 1px solid #ccc;
  }

  #list_data td {
    display: table-row;
  }
 
  
  #list_data td:before {
    content: attr(data-label);
    display: table-cell;
    font-weight: bold;
    padding: 0.2em 0.6em 0.2em 0;
    text-align: right;
  }
  #list_data td:last-child:after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    border-bottom: 1px solid #ccc;
  }
  
  .modal-dialog {
	  position: absolute;
	  top: 10px;
	  z-index: 10040;
	  overflow: auto;
	  overflow-y: auto;
	  border:1px solid #9CF;
	}
	

}



@media screen and (min-width: 320px) {
 


  #list_data td, #list_data th {
    padding: 0.4em 0.6em;
    vertical-align: top;
   /* border: 1px solid #ccc;*/
  }

  #list_data th {
    background: #e1e1e1;
    font-weight: bold;
  }
}
</style>
 <div class="row">
        <div class="col-xs-12">
         
          <div class="box">
            <div class="box-header">
              <!-- <h3 class="box-title">Data Kota & Kabupaten
            
              </h3>-->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
           
             
              <table id="list_data" class="table table-bordered table-hover dataTable"  style="width:100%">
                <thead id="header-data">
                <tr>
                  <th style="width:25px;" class="no_urut">No</th>
                  <th style="width:40px;">Tahun </th>
                  <th style="width:180px;">Kontraktor</th> 
                  <th style="width:70px;">Target</th>
                  <th style="width:150px;">Dibuat</th>
                  <th  style="width:100px;">Lastupdate</th>
                  <th style="width:70px;"></th>
                </tr>
                </thead>
                 <thead id="header-search">
                <tr>
                  <th ></th>
                  <th >
                  <input type="text" data-column="0"  class="form-control search-by-noanggota" style="width:100%" id="search-by-no-anggota" placeholder="ID"></th>
                  <th style="" class="text-center">
                  <input type="text" data-column="1"  class="form-control search-input-text " size="8" style="width:100%" placeholder="Nama">
                  </th>
                 <th style="">
                   
                 </th>
                 <th style="" class="text-center">
                   
                 </th>
                  <th style="" class="text-center">&nbsp;</th>
             
                  <th style="text-align:center;">
                  <?php echo $TombolTambah;?>
                  </th>
                </tr>
                </thead>
               
               <!-- <tfoot>
                <tr>
                  <th>No</th>
                 <th>Kode</th>
                  <th>Nama Kota</th>
                  <th>Provinsi</th>
                   <th></th>
                </tr>
                </tfoot>-->
              </table>
        
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

<script> 
var $j = jQuery.noConflict();
  $j(document).ready(function() {
	
    // $j('#list_kota').removeClass("sorting_asc");
	var table=$j('#list_data').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax": {
						"url": "<?php echo $url_listdata;?>",
						"type": "POST"
					},
					"columnDefs": [
						{"orderable": false, "targets": [0,1,4] },
						{"targets": [1,2,4,5],"className": "text-center"}
					
					 ],
					"columns": [
						{ "data": "No",'sortable': false,'className':'col-number td-content' },
						{ "data": "contractor_name",'className':'td-content' },
						{ "data": "tahun",'className':'td-content' },
						{ "data": "target_production",'className':'td-content' },
						{ "data": "created_time",'className':'td-content' },
						{ "data": "lastupdated",'className':'td-content' },
						{ "data": "Tombol",'className':'td-content btn-action' }
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						$j(nRow).attr('id', 'tr_' + aData.id); // or whatever you choose to set as the id
						//$j( nRow ).find('td:eq(0)').attr('data-label',"No");
						
					},
					"initComplete": function(settings, json) {
						console.log(json);
						//$j('#list_data').removeClass("sorting_asc");
						$j('.no_urut').removeClass("sorting_asc");
						$j( "#list_data_wrapper .row" ).find( "div" ).eq( 0 ).attr('class', 'col-sm-3');
						$j( "#list_data_wrapper .row" ).find( "div" ).eq( 2 ).attr('class', 'col-sm-9');
						//$j( "#list_person_wrapper .row" ).find( "div" ).eq( 2 ).css('border', '1px solid');
						
					  }
				});
	   
		$j("#list_data_filter").css("display","none");
		//var i ="";  // getting column index
		//var v =""; 
		$j('#search-by-no-anggota').on( 'keyup click', function () {   // for text boxes
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
			$j('.no_urut').removeClass("sorting_asc");
		} );	
		$j('.search-input-text').on( 'keyup click', function () {   // for text boxes
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
			$j('.no_urut').removeClass("sorting_asc");
		} );	
		$j('#search-by-status').on( 'keyup click', function () {   // for text boxes
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
			//$j('.no_urut').removeClass("sorting_asc");
		} );	
		table2.on('click', '.btn-edit-data', function (e) {
					var target = $j(this).attr('href'); // no use ???
					var id = $j(this).attr('role');

					$j('#largeModal .modal-title').html("Laporan Shipment");

					const url = `<?php echo $url_form_step;?>?shipment_id=${id}`;
					showForm(url, '#largeModal');

					// $j('#largeModal .modal-body').load(, function() {
					// 	// $j('#largeModal').modal('show');
					// });
					$j('#largeModal .modal-footer').html('');
					 $j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Tutup</button>");


					//e.preventDefault();
				});

				table2.on('click', '.btn-detail-data', function (e) {
				//$j('.btn-detail-data').on( 'click', function () {   // for text boxes
					var target = $j(this).attr('href');
					$j('#largeModal .modal-title').html("Detail Shipment");
					$j('#largeModal .modal-body').load(target, function() {
						 $j('#largeModal').modal('show');
					});
					$j('#largeModal .modal-footer').html('');
					$j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Tutup</button>");

				} );
		
		table.on('click', '.btn-sync-data', function (e) {
			var target = $j(this).attr('href');
			var id_erp = $j(this).attr('role');
			
			$j('#sync_label'+id_erp).html('<img src="<?php echo $theme_path;?>images/loader.gif" style="height:16px; vertical-align:middle; border:0px;"  alt=""  id="loader_sync'+id_erp+'" />');
			sync(target,id_erp);
		});
		//$('.btn-cetak-ktm').click(function(e) {
		table.on('click', '.btn-cetak-ktm', function (e) {								  
		   var url_capture = $(this).attr('href');//$(this).attr('href');
		   var with_webcam=confirm('Apakah akan menggunakan camera live untuk foto KTA?');
		   if(with_webcam==true){
			   url_capture=url_capture+'/1';
		   }
			 var judul = $(this).attr('title');
			
			$('#largeModal .modal-title').html(judul);
			$('#largeModal .modal-body').load(url_capture, function() {
				 $('#largeModal').modal('show');
			});
			$('#largeModal .modal-footer').html('');
			$('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Tutup</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"print_ktm\" onclick=\"cetak('<?php echo $url_print;?>/print');\" ><i class=\"fa fa-print\"></i>&nbsp;&nbsp;Print</button>");
		});
		table.on('click', '.btn-del-data', function (e) {
			var tr_id = $j(this).attr('role');
			
		  	 var url_del = $j(this).attr('href');
			 var judul = $j(this).attr('title');
			var nama = $j(this).attr('role');
			$j('#largeModal .modal-title').html("Konfirmasi "+judul);
			$j('#largeModal .modal-body').html("<h4>Yakin data <strong>"+nama+"</strong> akan dihapus?</h4>");//.css("text-align","center");
			$j('#largeModal .modal-footer').html('');
			$j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_hapus\" onclick=\"hapus('"+url_del+"','"+tr_id+"');\" ><i class=\"fa fa-trash-o\"></i>&nbsp;Hapus</button>");
			e.preventDefault();
		});
		
		
      
  });
  function perbarui(){
	
		$j("#spinner_perbarui").show();
		$j.ajax({
			type:"POST",
			url: "<?php echo $url_refresh;?>",
			data:"",
			success: function(data, status) {
				$j("#spinner_perbarui").hide();
				var obj2 = JSON.parse(data);
				$j("#media-test").html(obj2.html_data);
				
					if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
						$j('#list_data').DataTable().columns().search().draw();
					}
				
			}
		});	
 }
 function pilih(nilai,index_data,el){
	if(el=='crTPK'){
	 comboAjax('<?php echo $url_comboAjax;?>/listkelompok',nilai,'crKelompok','','','loaderCariKelompok');
	}
	
	if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
		//alert('cek');
		$j('#list_data').DataTable().columns(index_data).search(nilai).draw();
	}
//	return true;
	//alert(index_data+' '+nilai);
	//$j.fn.dataTable.columns(index_data).search(nilai).draw();
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
function sync(url_sync,id_erp){

		$j(".input").removeClass("error");
		$j.ajax({
			type:"POST",
			url: url_sync,
			data:'id_erp='+id_erp,
			success: function(data, status) {
				//$j("#media-test").html(data);
				var obj2 = JSON.parse(data);
				if (obj2.success==true){
					$j('#sync_label'+id_erp).html('Sudah');
					$j('#odoo_id'+id_erp).html(obj2.data.odoo_id);
				}else{
					$j('#sync_label'+id_erp).html('Gagal');
				}
				
			}
		});										  
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
					$( "#list_kota tbody" ).prepend( obj2.html );
					$j('#largeModal .modal-body').html("<h5>"+obj2.message+"</h5>");
					$j('#largeModal .modal-footer #tombol_batal').html("<i class=\"fa fa-times\"></i>&nbsp;Tutup");
					$j('#largeModal .modal-footer #simpan_data').remove();
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
