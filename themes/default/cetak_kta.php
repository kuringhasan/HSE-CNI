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
 <div class="row">
        <div class="col-xs-12">
         
          <div class="box">
            <div class="box-header">
              <!-- <h3 class="box-title">Data Kota & Kabupaten
            
              </h3>-->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
           
             <form method="post" id="form_listdata"  target="media-cetak">
              <table id="list_data" class="table table-bordered table-hover dataTable"  style="width:100%">
                <thead>
                <tr>
                  <th rowspan="2" class="no_urut" style="width:25px;">No</th>
                  <th rowspan="2" style="width:35px;" >&nbsp;</th>
                  <th rowspan="2" style="width:50px;">No. Anggota </th>
                  <th rowspan="2" style="width:180px;">Nama </th> 
                  <th rowspan="2" style="width:70px;">Gender</th>
                  <th rowspan="2" style="width:70px;">Status Kenggotaan</th>
                  <th rowspan="2" style="width:60px;">Aktif</th>
                 
                  <th rowspan="2" style="width:80px;">Masuk</th>
                  <th rowspan="2" style="width:150px;">MCP/Kelompok</th>
                  <th colspan="2"  >Barcode Aktif</th>
                  <th rowspan="2" style="width:55px;"></th>
                </tr>
                <tr>
                  <th  style="width:80px;">Produksi</th>
                  <th  style="width:80px;">Logistik</th>
                  </tr>
                </thead>
                 <thead>
                <tr>
                  <th ></th>
                  <th ><input name="check_all" class="check_all" type="checkbox" /></th>
                  <th >
                  <input type="text" data-column="0"  class="form-control search-by-noanggota" style="width:100%" id="search-by-no-anggota"></th>
                  <th style="" class="text-center">
                  <input type="text" data-column="1"  class="form-control search-input-text " size="8" style="width:100%">
                  </th>
                 <th style="">
                 
                 </th>
                 <th >
                  <select name="search-by-status" data-column="2" class="form-control search-by-status" id="search-by-status" style="width:100%">
                          <?php
                        echo '<option value="">-- Status --</option>';
                        $List=$ListStatus;
                        while($data = each($List)) {
                           ?>
                      <option value="<?php echo $data['key'];?>" ><?php echo $data['value'];?></option>
                      <?php
                      
                        }
                     ?>
              		</select>
                 </th>
                 <th >&nbsp;</th>
                 <th style=""></th>
                  <th style="" class="text-center">
                   <select name="tpk" data-column="7" class="form-control input-xm search-by-tpk" id="search-by-tpk" style="width:100%">
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
                
                  </th>
                  <th style="" class="text-center">&nbsp;</th>
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
         	</form>
            <button type="button" class="btn btn-primary btn-xs btn-print-multi" title="KTA Produksi" ><i class="fa  fa-credit-card"></i> Cetak KTA</button>
            <button type="button" class="btn btn-primary btn-xs btn-print-logistik" title="KTA Logistik" data-toggle="modal" data-remote="true"  data-target="#mediCetakModal" ><i class="fa  fa-credit-card"></i> KTA Logistik</button>
             
             <br />
           
         </div>
            <!-- /.box-body -->
            
            
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="mediCetakModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Preview KTA</h4>
        </div>
        <div class="modal-body" style="text-align:center">
            <iframe name="media-cetak" id="media-cetak" style="height:425px;border-collapse:collapse;border:1px solid #999;margin:0px 0px 0px 0px;padding:0px 0px 0px 0px; width:268px;overflow-x:hidden;overflow-y:scroll;"   align="middle"  marginwidth="0" marginheight="0" frameborder="0" vspace="0" hspace="0"></iframe><br />
           <div id="media_confirm_printed" style="display:none;"> <input name="confirm_printed" class="confirm_printed" type="checkbox" value="1"  /> KTA Sudah Diprint</div>
        </div>
      <div class="modal-footer">
    <button type="button" class="btn btn-primary btn-xs btn-print" title="Print KTA" ><i class="fa  fa-print"></i> Print</button>
       
      </div>
    </div>
  </div>
</div><!-- end of modal-->

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
						{ "orderable": false, "targets": [0,1,4,7,11] },
						{"targets": [1,2,4,5,6,7,9,10],"className": "text-center"}
					
					 ],
					"columns": [
						{ "data": "No",'sortable': false,'className':'col-number' },
						/*{ "data": "Foto","render": function (data) {
                             
                             return '<img src="data:image/gif;base64,{0},' + data + '" />';

                         }  
						},*/
						{ "data": "CheckBox" },
						{ "data": "NoAnggota" },
						{ "data": "Nama" },
						{ "data": "Gender" },
						{ "data": "Status" },
						{ "data": "Aktif" },
						{ "data": "TanggalMasuk" },
						{ "data": "TPK" },
						{ "data": "BarcodeProduksi" },
						{ "data": "BarcodeLogistik" },
						{ "data": "Tombol",'className':'btn-action' }
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						$j(nRow).attr('id', 'tr_' + aData.Kode); // or whatever you choose to set as the id
					},
					"initComplete": function(settings, json) {
						//console.log(json);
						//$j('#list_data').removeClass("sorting_asc");
						$j('.no_urut').removeClass("sorting_asc");
						$j( "#list_data_wrapper .row" ).find( "div" ).eq( 0 ).attr('class', 'col-sm-3');
						$j( "#list_data_wrapper .row" ).find( "div" ).eq( 2 ).attr('class', 'col-sm-9');
						//$j( "#list_person_wrapper .row" ).find( "div" ).eq( 2 ).css('border', '1px solid');
						
						var element = document.getElementById('list_data_filter');
						var child = document.createElement('div');
						child.style.float = "right";
						child.innerHTML = '<select name="crTPK" data-column="7" class="form-control input-xm crTPK" id="crTPK" onChange="pilih(this.value,7,\'crTPK\');" title="Pilih TPK">\n'+
						 '<option value="">-- TPK ---</option>\n'+
                        <?php
                         $tpk		=isset($_POST['crTPK'])?$_POST['crTPK']:$DataDapil['KodeTPK'];
						 $kelompok	=isset($_POST['crKelompok'])?$_POST['crKelompok']:$DataDapil['KodeKelompok'];
						 
                        $List=$ListTPK;
                        while($data = each($List)) {
							
                           ?>
                      '<option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$tpk?"selected":""; ?> ><?php echo $data['value'];?></option>\n'+
                      <?php
                      
                        }
                     ?>
              '</select>\n'+
			  '<select name="crKelompok" data-column="4" class="form-control input-xm crKelompok" id="crKelompok" onChange="pilih(this.value,4,\'crKelompok\');" title="Pilih Kelompok">\n'+
			   '<option value="">--- Kelompok ---</option></select>\n'+
			    '<img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:16px; vertical-align:middle; border:0px;"  alt=""  id="loaderCariKelompok"/>\n';
						//child = child.firstChild;
						 comboAjax('<?php echo $url_comboAjax;?>/listkelompok','<?php echo $tpk;?>','crKelompok','<?php echo $kelompok;?>','','loaderCariKelompok',4);
						
						var elementParent = element.parentNode;
						elementParent.insertBefore(child, element.nextSibling);
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
		$j('.search-by-tpk').on( 'change', function () {   // for select box
			var i =$j(this).attr('data-column');
			var v =$j(this).val();
			table.columns(i).search(v).draw();
		} );
		$j('.search-by-nik').on( 'keyup click', function () {   // for text boxes
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
		} );	
	    $j('.btn-print-multi').on( 'click', function () {   // for text boxes
			 var jml_dicek 	=$(".list_anggota:checked").length;
			 $('#media_confirm_printed').hide();
			 if(jml_dicek>0){
				 $( "#media-cetak" ).css( "width", "400px");
				 $('#largeModal').modal('show');
				//$j("#form_listdata").attr('action','<?php echo $url_print;?>?kta=logistik');
				//$j("#form_listdata").submit();
			 }else{
				 alert("Silahkan pilih dulu anggota");
			 }
			
		} );
		$j('.check_all').on( 'click', function () {   // for text boxes
			if($j(this).is(":checked"))
			{
				//$j(".list_anggota").attr("checked",true);	
				$j(".list_anggota").prop("checked",true);			
			}else{
				//$j(".list_anggota").attr("checked",false);	
				$j(".list_anggota").prop("checked",false);
			}
		} );
		table.on('click', '.list_anggota', function (e) {	
		//$j('.list_anggota').on( 'click', function () {   // for text boxes
		
			var jml_dicek 	=$(".list_anggota:checked").length;
			var jml_data 	=$(".list_anggota").length;
			var jml_uncek	=jml_data-jml_dicek;
			//alert('jml :'+jml_data+ ' jml_cek:'+jml_dicek);
			if(jml_dicek==jml_data){
				$j(".check_all").prop("checked",true);
			}
			if(jml_dicek<jml_data){
				$j(".check_all").prop("checked",false);
			}
			
		} );
		$j('.btn-print-logistik').on( 'click', function () {   // for text boxes
			 var jml_dicek 	=$(".list_anggota:checked").length;
			 $('#media_confirm_printed').hide();
			 if(jml_dicek>0){
				 $( "#media-cetak" ).css( "width", "268px");
				 $('#largeModal').modal('show');
				$j("#form_listdata").attr('action','<?php echo $url_print;?>?kta=logistik');
				$j("#form_listdata").submit();
			 }else{
				 alert("Silahkan pilih dulu anggota");
			 }
			
		} );
		$j('.btn-print').on( 'click', function () {   // for text boxes
			var frm = document.getElementById("media-cetak").contentWindow;
            frm.focus();// focus on contentWindow is needed on some ie versions
            frm.print();
			$j('#media_confirm_printed').show();
				
			
		} );
		$j('.confirm_printed').on( 'click', function () {   // for text boxes
			if ( $j(this).is(':checked') ) {	
				if(confirm("Yakin KTA sudah diprint (semua)?")){
					alert('fungsi ajax');
					
				}else{
					$j(this).prop("checked", false);
				}
			}
		} );
		
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
