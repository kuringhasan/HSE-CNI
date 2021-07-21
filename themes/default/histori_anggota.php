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
#list_data tr th{
	vertical-align:middle;
	text-align:center;
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
                  <th style="width:25px;" class="no_urut">No</th>
                  <th style="width:65px;">No. Anggota </th>
                  <th style="width:180px;">Nama </th> 
                  <th style="width:60px;">Gender</th>
                  <th style="width:70px;">Status</th>
                 
                  <th style="width:70px;">Masuk</th>
                  <th style="width:250px;">MCP/TPK</th>
                  <th>NIK</th>
                  <th style="width:50px;"></th>
                </tr>
                </thead>
                 <thead>
                <tr>
                  <td ></td>
                  <td >
                  <input type="text" data-column="0"  class="form-control search-input-text no-anggota" style="width:100%" id="search-by-no-anggota"></td>
                  <td  class="text-center">
                  <input type="text" data-column="1"  class="form-control search-input-text " size="8" style="width:100%">
                  </td>
                 <td >
                 
                 </td>
                 <td >
                  <select name="search-by-status" data-column="2" class="form-control search-by-status" id="search-by-status" style="width:100%">
                          <?php
                        echo '<option value="">---</option>';
                        $List=$ListStatus;
                        while($data = each($List)) {
                           ?>
                      <option value="<?php echo $data['key'];?>" ><?php echo $data['value'];?></option>
                      <?php
                      
                        }
                     ?>
              		</select>
                 </td>
                 <td ></td>
                  <td  class="text-center">
                   <select name="crTPK" data-column="7" class="form-control crTPK" id="crTPK">
                          <?php
                        echo '<option value="">-- MCP/TPK --</option>';
                        $List=$ListTPK;
                        while($data = each($List)) {
                           ?>
                      <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$_POST['crTPK']?"selected":""; ?> ><?php echo $data['value'];?></option>
                      <?php
                      
                        }
                     ?>
              		</select>
                
                  </td>
                  <td  class="text-center">&nbsp;</td>
             
                  <td >
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
					"ajax": {
						"url": "<?php echo $url_listdata;?>",
						"type": "POST"
					},
					"columnDefs": [
						{ "orderable": false, "targets": [0,4,7] },
						{"targets": [1,3,4,5,7,8],"className": "text-center"}
					 ],
					"columns": [
						{ "data": "No",'sortable': false,'className':'col-number' },
						{ "data": "NoAnggota" },
						{ "data": "Nama" },
						{ "data": "Gender" },
						{ "data": "Status" },
						{ "data": "TanggalMasuk" },
						{ "data": "TPK" },
						{ "data": "NIK" },
						{ "data": "Tombol",'className':'btn-action' }
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						$j(nRow).attr('id', 'tr_' + aData.Kode); // or whatever you choose to set as the id
					},
					"initComplete": function(settings, json) {
						//console.log(json);
						//$j('#list_data').removeClass("sorting_asc");
						$j('.no_urut').removeClass("sorting_asc");
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
		$j('.crTPK').on( 'change', function () {   // for select box
			var i =$j(this).attr('data-column');
			var v =$j(this).val();
			table.columns(i).search(v).draw();
		} );
		$j('.crSync').on( 'change', function () {   // for select box
			var i =$j(this).attr('data-column');
			var v =$j(this).val();
			table.columns(i).search(v).draw();
		} );
		
		
		table.on('click', '.btn-sync-data', function (e) {
			var target = $j(this).attr('href');
			var id_erp = $j(this).attr('role');
			
			$j('#sync_label'+id_erp).html('<img src="<?php echo $theme_path;?>images/loader.gif" style="height:16px; vertical-align:middle; border:0px;"  alt=""  id="loader_sync'+id_erp+'" />');
			sync(target,id_erp);
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
