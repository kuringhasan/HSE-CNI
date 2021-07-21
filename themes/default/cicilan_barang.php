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
                  <th class="no_urut" style="width:25px;">No</th>
                   <th class="kode" style="width:150px;">Anggota</th>
                   <th style="width:70px;">Tanggal</th>
                  <th style="min-width:100px;" >Nama Barang</th>
                  <th style="width:80px;">Harga Satuan</th>
                  <th style="width:40px;">Qty</th>
                   <th style="width:80px;">Total</th>
                   <th style="width:50px;">Frek. Cicilan</th>
                  <th style="width:40px;">Bayar Ke</th>
                  <th style="width:80px;">Total Bayar</th>
                  <th style="width:55px;">Sisa Hutang</th>
                </tr>
                </thead>
                 <thead>
                <tr>
                  <td style="width:25px;padding-top:2px;;padding-bottom:2px;"></td>
                  <td style="width:25px;padding-top:2px;;padding-bottom:2px;">
                   <input type="text" data-column="1"  class="search-by-member" size="8" style="width:100%"></td>
                    <td style="padding-top:2px;padding-bottom:2px;"> </td>
                  <td style="padding-top:2px;padding-bottom:2px;" class="text-center">
                  <input type="text" data-column="2"  class="search-input-text" size="8" style="width:100%">
                  </td>
                  <td style="padding-top:2px;padding-bottom:2px;"> </td>
                  <td style="padding-top:2px;padding-bottom:2px;"> </td>
                  <td style="padding-top:2px;padding-bottom:2px;"> </td>
                  <td style="padding-top:2px;padding-bottom:2px;"> </td>
                  <td  style="padding-top:2px;padding-bottom:2px;"> </td>
                  <td style="padding-top:2px;;padding-bottom:2px;" class="text-center"></td>
             
                  <td style="width:55px;padding-top:2px;;padding-bottom:2px;text-align:center;">
                  
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
        <div  class="col-xs-8" style="background-color:#FFF; padding:2px 4px 2px 4px;">
		 <?php echo $ExportExcel;?>
         </div>
         </div>
            <!-- /.box-body -->
         
            
          </div>
          <!-- /.box -->
        </div>
         
        <!-- /.col -->
      </div>
      <!-- /.row -->

<iframe id="media-download" name="media-download" src="" style="display:none"> </iframe>
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
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/plugins/ajax-typeahead/js/bootstrap-typeahead.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> 


<script> 
var $j = jQuery.noConflict();
  $j(document).ready(function() {
	 
	 
    // $j('#list_kota').removeClass("sorting_asc");
	var table=$j('#list_data').DataTable( {
					"scrollX": true,
					"processing": true,
					"serverSide": true,
					"ajax": {
						"url": "<?php echo $url_listdata;?>",
						"type": "POST"
					},
					"columnDefs": [
						{ "orderable": false, "targets": [0,4,5,6,7,8,9,10] },
						{"targets": [2,3,4,5,6,7,8,9],"className": "text-center"}
					 ],
					"columns": [
						{ "data": "No",'sortable': false,'className':'col-number' },
						{ "data": "Anggota" },
						{ "data": "Tanggal" },
						{ "data": "NamaBarang" },
						{ "data": "Harga" },
						{ "data": "Qty" },
						{ "data": "Total" },
						{ "data": "FrekCicilan" },
						{ "data": "BayarKe" },
						{ "data": "TotalBayar" },
						{ "data": "SisaHutang"}
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						$j(nRow).attr('id', 'tr_' + aData.Kode); // or whatever you choose to set as the id
					},
					"initComplete": function(settings, json) {
						console.log(json);
						//$j('#list_data').removeClass("sorting_asc");
						$j('.no_urut').removeClass("sorting_asc");
						$j('#list_data_filter input[type="search"]').attr("placeholder","Periode");
						$j( "#list_data_wrapper .row" ).find( "div" ).eq( 0 ).attr('class', 'col-sm-3');
						$j( "#list_data_wrapper .row" ).find( "div" ).eq( 2 ).attr('class', 'col-sm-9');
						//$j( "#list_person_wrapper .row" ).find( "div" ).eq( 2 ).css('border', '1px solid');
						
						var element = document.getElementById('list_data_filter');
						var child = document.createElement('div');
						child.style.float = "right";
						child.innerHTML = '<input type="hidden" data-column="5" id="search_by_periode_id" size="2" >\n';
						//child = child.firstChild;
						var elementParent = element.parentNode;
						elementParent.insertBefore(child, element.nextSibling);
						
							
					
					  }
				});
	   
		//$j("#list_data_filter").css("display","none");
		$j('#list_data_filter input[type="search"]').css("width","200px");
		//var i ="";  // getting column index
		//var v ="";
		//alert('cek');
		//table.on('keydown.autocomplete', '#search_by_periode', function (e) {
		//$j("#search_by_periode").on("keydown.autocomplete", function() {
		$j('#list_data_filter input[type="search"]').keyup(function(){
				var dt=$j(this).val()
				if (dt.length==0){
					$("#search_by_periode_id").val('');
					table.columns(5).search('').draw();
				}
		}); 	
		 $j('#list_data_filter input[type="search"]').on( 'keydown.autocomplete', function () {   // for text boxes											
				//alert("<?php echo $url_jsonData."";?>");
				$j(this).typeahead({
					ajax: { 
						url: "<?php echo $url_jsonData."";?>/list_periode",
						timeout: 500,
						displayField: "Nama",
						valueField :'ID',
						triggerLength: 1,
						method: "POST",
						loadingClass: "loading-circle",
						preDispatch: function (query) {
							//alert(query);
							//showLoadingMask(true);
							return {
								search: query
							}
						},
						preProcess: function (data) {
							console.log(data);
							//showLoadingMask(false);
							//alert(data.Lengkap);
							/*if (data.success === false) {
								// Hide the list, there was some error
								return false;
							}*/
							// We good!
							//return data.mylist;
							return data;
						}
					},
				   onSelect: function(item) {
						console.log(item);
						$j("#search_by_periode_id").val(item.value);
						table.columns(5).search(item.value).draw();
					}
				});
			});
		
		$j('.search-by-member').on( 'keyup click', function () {   // for text boxes
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
		} );	
		
		/*$j('#btn-download-excel').click(function(e) {
        
			var url_action = $(this).attr('role');
			
			$("#form_cari").attr('target',"media-download");
			$("#form_cari").attr('action',url_action);
			
			$("#form_cari").submit();
			
		});*/
		$j('#btn-tambah-data').on( 'click', function () {   // for text boxes
			var target = $j(this).attr('href');
		  	$j('#largeModal .modal-title').html("Form Data Kota/Kab.");
			$j('#largeModal .modal-body').load(target, function() {
				 $j('#largeModal').modal('show');
			});
			$j('#largeModal .modal-footer').html('');
			$j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/save');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
		   
		} );	
		
		table.on('click', '.btn-edit-data', function (e) {
			var target = $j(this).attr('href');
			
		  	$j('#largeModal .modal-title').html("Form Data Kota/Kab.");
			$j('#largeModal .modal-body').load(target, function() {
				 $j('#largeModal').modal('show');
			});
			$j('#largeModal .modal-footer').html('');
			 $j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/save');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
		   
	
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



<script>
/*
	$(document).ready(function () {  
	alert('cek23');
		$("#search-by-periode").keyup(function(){
			var dt=$(this).val()
			if (dt.length==0){
				$("#search-by-periode-id").val('');
			}
		}); 	
		
		 
	});*/
</script>    