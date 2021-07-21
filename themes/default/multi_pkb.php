<link rel="stylesheet" href="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"> 
<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />

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
                  <th id="row_no" style="width:15px;">No</th>
                  <th style="width:90px;">Sapi</th>
                  <th style="width:90px;">IB Terakhir</th>
                  <th style="width:90px;">Masa</th>
                  <th style="width:90px;">Jadwal PKB</th>
                  <th style="">Anggota</th>
                  <th style="">Periksa Kebuntingan</th>
                  <th style="width:50px;"></th>
                </tr>
                </thead>
                <thead>
                <tr>
                  <td style="width:25px;padding-top:2px;;padding-bottom:2px;"></td>
                  <td ><input type="text" data-column="1"  class="form-control search-by-eartag"  style="width:100%"></td>
                  <td >
                 
                  </td>
                  <td > </td>
                  <td >
                 <select name="search-limit-time" data-column="2" class="form-control input-xm search-limit-time" id="search-limit-time" >
                 		<option value="">--Batasan--</option>
                        <option value="1" >Hari Ini</option>
                        <option value="-5" >Dr 5 Hari Lalu</option>
                        <option value="0" >Sudah Lewat</option>
                        </select>
                  </td>
                   <td ><input type="text" data-column="0"   class="form-control search-by-id-anggota "  style="width:100%" placeholder="Anggota"></td>
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
					"ajax": {
						"url": "<?php echo $url_listdata;?>",
						"type": "POST"
					},
					"columnDefs": [
						{ "orderable": false, "targets": [1,3,5,6,7]},
						{
							  "targets": [1,2,3,4,5,6,7], // your case first column
							  "className": "text-center"
						 }
					 ],
					"columns": [
						{ "data": "No",'sortable': false,'className':'col-number' },
						{ "data": "NoEartag" },
						{ "data": "TanggalIB" },
						{ "data": "Masa" },
						{ "data": "TanggalPKB" },
						{ "data": "Anggota" },
						{ "data": "PKB" },
						{ "data": "Tombol",'className':'btn-action' }
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						console.log(aData);
						$j(nRow).attr('id', 'tr_' + aData.ID); // or whatever you choose to set as the id
					},
					"initComplete": function(settings, json) {
						console.log(json);
						$j('#row_no').removeClass("sorting_asc");
						$j( "#list_data_wrapper .row" ).find( "div" ).eq( 0 ).attr('class', 'col-sm-3');
						$j( "#list_data_wrapper .row" ).find( "div" ).eq( 2 ).attr('class', 'col-sm-9');
						//$j( "#list_person_wrapper .row" ).find( "div" ).eq( 2 ).css('border', '1px solid');
						
						//preventDefault();
					
					  }
				});
	   
		$j("#list_data_filter").css("display","none");
		//var i ="";  // getting column index
		//var v =""; 
		// Apply the search
        table.on('click', '.pkb', function (e) {
		//$j('.pkb').on( 'click', function () {   // for text boxes
			var nilai = $j(this).val();
			var get_id = $j(this).attr('id');
			//alert(get_id);
			var target = $j(this).attr('rel');
			var title = $j(this).attr('title');
			var pkb="";
			var form_html="";
			var cow_id="";
			
			if (nilai==7){
				pkb="Bunting";
				cow_id=get_id.replace("bunting_","");
				form_html="<form id=\"form_input_data\" class=\"responsive-form\" ><div class=\"row-form\"><span class=\"label\" >Umur </span> <input type=\"text\" class=\"input\" name=\"umur_kebuntingan\"  size=\"4\" /></div><div class=\"row-form\"><span class=\"label\" >Tanda Kebuntingan </span><select name=\"tanda_bunting\" id=\"tanda_bunting\"  class=\"input\"  >\n"+
				"<option value=\"\"   >-- pilih --</option>\n"+
             <?php
                    
                    $List=$ListTandaBunting;
					
                    while($data = each($List)) {
                       ?>
               "<option value=\"<?php echo $data['key'];?>\"   ><?php echo $data['value'];?></option>\n"+
                <?php
                    }
                 ?>
            "</select></div></form>";
			$j('#largeModal .modal-body').css("text-align","left");
			}
			if (nilai==25){
				pkb="Kosong";
				cow_id=get_id.replace("kosong_","");
				form_html="";
				$j('#largeModal .modal-body').css("text-align","center");
			}
			//alert(id);
		  	$j('#largeModal .modal-title').html("Konfirmasi PKB ");
			$j('#largeModal .modal-body').html("<h4>Anda yakin "+title+" : <strong>"+pkb+"</strong>?</h4>"+form_html );
			
			$j('#largeModal .modal-footer').html('');
			$j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_simpan\" onclick=\"simpan("+nilai+","+cow_id+");\" ><i class=\"fa fa-save\"></i>&nbsp;Yakin</button>");
			$j('#largeModal').modal('show');
		   
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
		table.on('click', '.btn-detail-data', function (e) {
			var target = $j(this).attr('href');
			
		  	$j('#largeModal .modal-title').html("Detail Mutasi");
			$j('#largeModal .modal-body').load(target, function() {
				 $j('#largeModal').modal('show');
			});
			$j('#largeModal .modal-footer').html('');
			 $j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button>");
		   
	
			e.preventDefault();
		});
		table.on('click', '.btn-edit-data', function (e) {
			var target = $j(this).attr('href');
			
		  	$j('#largeModal .modal-title').html("Form Edit Data");
			$j('#largeModal .modal-body').load(target, function() {
				 $j('#largeModal').modal('show');
			});
			$j('#largeModal .modal-footer').html('');
			 $j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button>");
		   
	
			e.preventDefault();
		});
	
		
      
  });
  function pilih(nilai,index_data,el){
	
	if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
		//alert(nilai);
		$j('#list_data').DataTable().columns(index_data).search(nilai).draw();
	}
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
function simpan(event_id,cow_id){
	//alert("<?php echo $url_save;?>");
		$j(".input").removeClass("error");
		$j.ajax({
			type:"POST",
			url: "<?php echo $url_save;?>",
			data: $j("#form_input_data").serialize()+"&event_id="+event_id+"&cow_id="+cow_id,
			success: function(data, status) {
			
				$j("#media-test").html(data);
				var obj2 = JSON.parse(data);
				if (obj2.success==true){
					//table.columns([0,1,2]).search($("#crProvinsi").val()).draw();
					//loaddata($j("#form_cari").serialize());
					//$( "#list_staff tbody" ).prepend( obj2.html );
					$j('#largeModal .modal-body').html("<h5>"+obj2.message+"</h5>");
					$j('#largeModal .modal-footer #tombol_batal').html("<i class=\"fa fa-times\"></i>&nbsp;Tutup");
					$j('#largeModal .modal-header').remove();
					$j('#largeModal .modal-footer').remove();
					if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
						$j('#list_data').DataTable().columns().search().draw();
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
					$j('#largeModal .modal-footer #tombol_simpan').remove();
					
					
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
