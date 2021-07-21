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
.text-right{
	text-align:right;
}
.table tr th, table tr td{
	font-size:12px;
	padding:2px 0px 2px 0px;
}
#list_data tr th{
	vertical-align:middle;
	text-align:center;
}
#largeModal .modal-dialog{
	width: 50%;
}
small{
	font-size:9px;
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
                  <th id="row_no" style="width:25px;">No</th>
                  <th style="width:250px;">Page Name</th>
                  <th style="width:250px;">Level User</th>
                  <th style="">Privileges</th>
                  <th style="width:50px;"></th>
                </tr>
                </thead>
                <thead>
                <tr>
                  <td style="padding-top:2px;;padding-bottom:2px;"></td>
                  <td ><input type="text" data-column="3"  class="form-control search-by-page"  style="width:100%;font-size:12px;"></td>
                  <td >
                
                 <select name="search-by-metode" data-column="0" class="form-control  search-by-level" id="search-by-metode" style="width:100%">
                          <?php
                        echo '<option value="">-Level-</option>';
                        $List=$list_level;
                        while($data = each($List)) {
                           ?>
                      <option value="<?php echo $data['key'];?>" ><?php echo $data['value'];?></option>
                      <?php
                      
                        }
                     ?>
              		</select>
                  </td>
                  <td > </td>
                  <td style="padding-top:2px;;padding-bottom:2px;text-align:center;vertical-align:middle;">
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
      <div class="modal-footer" style="padding-bottom:7px; padding-top:7px;">
    
       
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
	  
	
	var table=$j('#list_data').DataTable( {
					"processing": true,
					"serverSide": true,
					"order": [[ 3, "desc" ]],
					"ajax": {
						"url": "<?php echo $url_listdata;?>",
						"type": "POST"
					},
					"columnDefs": [
						{ "orderable": false, "targets": [4]},
						{
							  "targets": [0,4], // your case first column
							  "className": "text-center"
						 }
					 ],
					"columns": [
						{ "data": "No",'sortable': false,'className':'col-number' },
						{ "data": "Page" },
						{ "data": "Level" },
						{ "data": "Privileges" },
						{ "data": "Tombol",'className':'btn-action' }
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						//console.log(aData);
						$j(nRow).attr('id', 'tr_' + aData.ID); // or whatever you choose to set as the id
					},
					"initComplete": function(settings, json) {
						//console.log(json);
						$j('#row_no').removeClass("sorting_asc");
						//$j( "#list_data_wrapper .row" ).find( "div" ).eq( 0 ).attr('class', 'col-sm-3');
						//$j( "#list_data_wrapper .row" ).find( "div" ).eq( 2 ).attr('class', 'col-sm-9');
					
					  }
				});
	   
		$j("#list_data_filter").css("display","none");
		
		$j('.search-by-page').on( 'keyup click focusout focus', function () {   // for text boxes
		
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
		} );
		$j('.search-by-level').on( 'keyup click', function () {   // for text boxes
		
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
		} );
	
		$j('#btn-tambah-data').on( 'click', function () {   // for text boxes
			var target = $j(this).attr('href');
			var tr_id = $j(this).attr('role');
		  	$j('#largeModal .modal-title').html("Form Input Data");
			$j('#largeModal .modal-body').load(target, function() {
				 $j('#largeModal').modal('show');
			});
			$j('#largeModal .modal-footer').html('');
			$j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_simpan\" onclick=\"simpan('"+target+"','add');\" ><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
		   
		} );	
		
		table.on('click', '.btn-detail-data', function (e) {
			var target = $j(this).attr('href');
			
		  	$j('#largeModal .modal-title').html("Detail Mutasi");
			$j('#largeModal .modal-body').load(target, function() {
				 $j('#largeModal').modal('show');
			});
			$j('#largeModal .modal-footer').html('');
			 $j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_simpan\" onclick=\"simpan('"+target+"','"+tr_id+"');\" ><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
		   
	
			e.preventDefault();
		});
		table.on('click', '.btn-edit-data', function (e) {
			var target = $j(this).attr('href');
			var tr_id = $j(this).attr('role');
		  	$j('#largeModal .modal-title').html("Form Edit Data");
			$j('#largeModal .modal-body').load(target, function() {
				 $j('#largeModal').modal('show');
			});
			$j('#largeModal .modal-footer').html('');
			$j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_simpan\" onclick=\"simpan('"+target+"','edit');\" ><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
		   
		   
	
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

function simpan(url_save,act){
		//alert(url_save+'/save');
		$j(".input").removeClass("error");
		$j.ajax({
			type:"POST",
			url: url_save+'/save?action='+act,
			data: $j("#form_input_data").serialize(),
			success: function(data, status) {
			
				$j("#media-test").html(data);
				var obj2 = JSON.parse(data);
				if (obj2.success==true){
					
					
					$j('#largeModal .modal-body').html("<h5>"+obj2.message+"</h5>");
					$j('#largeModal .modal-footer #tombol_batal').html("<i class=\"fa fa-times\"></i>&nbsp;Tutup");
					
					$j('#largeModal .modal-footer #tombol_simpan').remove();
					//alert($j.fn.DataTable.isDataTable( '#list_data' ));
					console.log($j.fn.DataTable);
					if ( $j.fn.DataTable.isDataTable( '#list_data' ) ) {
						$j('#list_data').DataTable().columns().search().draw();
					}
				}else{
					//$j("#largeModal .modal-body").addClass("error");
					
					if( obj2.form_error !== undefined)
					{
						alert(obj2.message);
						errorForm(obj2.form_error);
					}else{
						$j('#largeModal .modal-body').html("<div class=\"error\"><h5 class=\"lbl_error\">"+obj2.message+"</h5></div>");
						$j('#largeModal .modal-footer #tombol_batal').html("<i class=\"fa fa-times\"></i>&nbsp;Tutup");
						$j('#largeModal .modal-footer #tombol_simpan').remove();
					}
				}
				
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
					}else{
						
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
