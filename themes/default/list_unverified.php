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
	padding:2px 0px 2px 0px;
}
#list_data tr th{
	vertical-align:middle;
	text-align:center;
}
.error{
	border:1px solid #F99;
	background-color:#FFC;
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
                <thead>
                <tr>
                  <th rowspan="2" id="no_urut2" style="width:20px;">No</th>
                  <th rowspan="2" style="width:60px;">Tanggal Identifikasi</th>
                  <th rowspan="2" style="">Pemilik</th>
                  <th rowspan="2" style="width:90px;">Eartag</th>
                  <th rowspan="2" style="width:90px;">Tipe</th>
                  <th rowspan="2" style="width:90px;">Tanggal Lahir</th>
                  <th colspan="2" style="width:80px;"><span style="width:90px;">Identifikasi</span></th>
                  <th rowspan="2" style="width:90px;"></th>
                </tr>
                <tr>
                  <th style="width:110px;">Petugas</th>
                  <th style="width:60px;">Tanggal</th>
                  </tr>
               </thead>
                <thead>
                <tr>
                  <td style=""></td>
                  <td >
                
                  </td>
                  <td > <input type="text" data-column="0"  class="search-by-id-anggota"  style="width:100%" placeholder="ID Anggota"> </td>
                 
                   <td ><input type="text" data-column="1"  class="search-by-eartag"  style="width:100%"></td>
                   <td ></td>
                
               <td style="padding-top:2px;;padding-bottom:2px;"> 
           
               
              
                  </td> 
                  <td ></td>
                  <td ></td>
                  <td style="width:55px;padding-top:2px;;padding-bottom:2px;text-align:center;vertical-align:middle;">
                  <?php echo $TombolTambah;?>
                  </td>
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
					"scrollX": true,
					"order": [[ 1, "desc" ]],
					"ajax": {
						"url": "<?php echo $url_listdata;?>",
						"type": "POST"
					},
					"columnDefs": [
						{ "orderable": false, "targets": [3,4,8]},
						{
							  "targets": [1,3,4,6,7], // your case first column
							  "className": "text-center"
						 }
					 ],
					"columns": [
						{ "data": "No",'sortable': false,'className':'col-number' },
						{ "data": "TanggalIdentifikasi" },
						{ "data": "Pemilik" },
						{ "data": "NoEartag" },
						{ "data": "Tipe" },
						{ "data": "TanggalLahir" },
						{ "data": "Petugas" },
						{ "data": "TanggalPelayanan" },
						{ "data": "Tombol",'className':'btn-action' }
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						console.log(aData);
						$j(nRow).attr('id', 'tr_' + aData.ID); // or whatever you choose to set as the id
					},
					"initComplete": function(settings, json) {
						console.log(json);
						//$j( "#list_data_wrapper .row" ).find( "div" ).eq( 0 ).attr('class', 'col-sm-3');
						//$j( "#list_data_wrapper .row" ).find( "div" ).eq( 2 ).attr('class', 'col-sm-9');
					
					  }
				});
	   
		$j("#list_data_filter").css("display","none");
		//var i ="";  // getting column index
		//var v =""; 
		
		$j('.search-by-eartag').on( 'keyup click', function () {   // for text boxes
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
		} );
		$j('.search-by-id-anggota').on( 'keyup click', function () {   // for text boxes
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
		} );
		
		$j('.crDesa').on( 'change', function () {   // for select box
			var i =$j(this).attr('data-column');
			var v =$j(this).val();
			table.columns(i).search(v).draw();
		} );
		$j('#btn-tambah-data').on( 'click', function () {   // for text boxes
			var target = $j(this).attr('href');
			
		  	$j('#largeModal .modal-title').html("Form Input Data");
			$j('#largeModal .modal-body').load(target, function() {
				 $j('#largeModal').modal('show');
			});
			$j('#largeModal .modal-footer').html('');
			$j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button>");
		   
		} );	
		
		table.on('click', '.btn-verifikasi-data', function (e) {
			var target = $j(this).attr('href');
			
			var event_id = $j(this).attr('role');
			if(event_id==''){
				target=target+'/not';
			}
		  	$j('#largeModal .modal-title').html("Verifikasi Sapi");
			$j('#largeModal .modal-body').load(target, function() {
				 $j('#largeModal').modal('show');
			});
			$j('#largeModal .modal-footer').html('');
			 $j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_verifikasi\" onclick=\"verifikasi('"+target+"','"+event_id+"');\" ><i class=\"fa fa-check\"></i>&nbsp;Verifikasi</button>");
		   
	
			e.preventDefault();
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
		
		table.on('click', '.btn-send-whatsapp', function (e) {
			
			var tr_id = $j(this).attr('role');
			var url_send = $j(this).attr('href');
			alert(url_send);
			 var judul = $j(this).attr('title');
			
		  	$j('#largeModal .modal-title').html("Form Send Whatsapp");
			$j('#largeModal .modal-body').load(url_send, function() {
				 $j('#largeModal').modal('show');
			});
			$j('#largeModal .modal-footer').html('');
			 $j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_send\" onclick=\"kirim('"+url_send+"','"+tr_id+"');\" ><i class=\"fa fa-send\"></i>&nbsp;Kirim</button>");
		   
	
			e.preventDefault();
			/*var tr_id = $j(this).attr('role');
			
		  	 var url_send = $j(this).attr('href');
			 var judul = $j(this).attr('title');
			var nama = $j(this).attr('role');
			$j('#largeModal .modal-title').html(judul);
			$j('#largeModal .modal-body').html("Pesan :<br /><textarea cols='35' rows='2' name='pesan' id='pesan' style='width:100%;'></textarea>");//.css("text-align","center");
			$j('#largeModal .modal-footer').html('');
			$j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_kirim\" onclick=\"kirim('"+url_send+"');\" ><i class=\"fa fa-send\"></i>&nbsp;Kirim</button>");
			e.preventDefault();*/
		});
		
		
		
      
  });
  function verifikasi(url_verfikasi,id){
	  //alert(url_verfikasi+'/save');
	  $j.ajax({
			type:"POST",
			url: url_verfikasi+'/save',
			data: $j("#form_input_data").serialize(),
			success: function(data, status) {
				//alert(data);
				var obj2 = JSON.parse(data);
				//$j("#media-test").html(data);
				if (obj2.success==true){
					//loaddata($j("#form_cari").serialize());
					//$('#largeModal .modal-footer').remove();
					//$('#largeModal .modal-body').html(obj2.pesan);
					if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
						$j('#list_data').DataTable().columns().search().draw();
					}
					
					$j('#largeModal .modal-body').html("<h5>"+obj2.message+"</h5>");
					$j('#largeModal .modal-footer #tombol_batal').html("<i class=\"fa fa-times\"></i>&nbsp;Tutup");
					$j('#largeModal .modal-footer #tombol_verifikasi').remove();
					
					
				}else{
					
					alert(obj2.message);
					if( obj2.form_error !== undefined)
					{
						errorForm(obj2.form_error);
					}
				}
				
			}
		});		
  }
 
function errorForm(msj_obj){
	 if (jQuery.isEmptyObject(msj_obj)==false)
	 {
		 var errors=msj_obj;
		for (var key in errors){
			$j("#"+key).addClass("error");
			//$j("#err_"+key).html(errors[key]);
			//$j("#err_"+key).addClass("lbl_error");
			//$j("#err_"+key).show();
		}
	 }
}
function simpan(url_save){
	
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
					//$( "#list_staff tbody" ).prepend( obj2.html );
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
					if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
						$j('#list_data').DataTable().columns().search().draw();
					}
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
function comboAjax(url_cmb,parentkode,idTarget,nilai,other_parameters,idloader, indeks)
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
						if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
						
							$j('#list_data').DataTable().columns(indeks).search(nilai).draw();
						}
				   }else{
					  // $("#"+idframe).fadeOut();
					  $j("#"+idTarget).fadeIn();
					  $j("#"+idTarget).empty().append(obj.html);
				   }
				   
			   }///akhisr sukses
		   }); //akhir $.ajax	
	}
</script>
