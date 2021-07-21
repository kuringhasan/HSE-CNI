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
                  <th style="">Petugas</th>
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
          
              
                <?php echo $TombolDownload;?>
           
            <img src="<?php echo $theme_path."/images/loader.gif";?>" style="display:none; height:8px;"  class="spinner_download"/>
             <form method="post" id="form-download"  target="media-download">
              <input type="text" name="dw_tahun" id="dw_tahun" size="3" />
             <input type="text" name="dw_bulan" id="dw_bulan" size="3" />
             </form>
             <div id='progress_wrapper'  style="float:left">
                    <div id="progressor" ></div>
            </div>
             <iframe name="media-download" style="display:none;" ></iframe>
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
					"order": [ 1, "desc" ],
					"ajax": {
						"url": "<?php echo $url_listdata;?>",
						"type": "POST"
					},
					"columnDefs": [
						{ "orderable": false, "targets": [1,3,5,6,7]},
						{
							  "targets": [1,2,3,4,7], // your case first column
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
						{ "data": "Petugas" },
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
						
						var element = document.getElementById('list_data_filter');
						var child = document.createElement('div');
						child.style.float = "right";
						child.innerHTML = '<select name="cr_bulan" data-column="6" class="form-control input-xm cr_bulan" id="cr_bulan" onChange="pilih(this.value,6,\'cr_bulan\');" title="Pilih Bulan">\n'+
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
			  '<input type="text" name="cr_tahun" class="form-control input-xm cr_tahun" id="cr_tahun" onkeyup="pilih(this.value,7,\'cr_tahun\');" placeholder="tahun" size="2">\n';
						
						var elementParent = element.parentNode;
						elementParent.insertBefore(child, element.nextSibling);
						//preventDefault();
					
					  }
				});
	   
		$j("#list_data_filter").css("display","none");
		//var i ="";  // getting column index
		//var v =""; 
		// Apply the search
       
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
		$j('.search-limit-time').on( 'keyup click', function () {   // for text boxes
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
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
		$j('.btn-export-excel').on( 'click', function () {   // for text boxes
			if($j('#dw_tahun').val()!==""){;
				$j("#form-download").attr('action','<?php echo $url_export;?>');
				$j("#form-download").submit();
			}else{
				alert('Tahun harus diisi');
			}
		} );
		
		
		
		
      
  });
 function pilih(nilai,index_data,el){
	
	var thn = el=='cr_tahun'?nilai:document.getElementById('cr_tahun').value;
	var bln = el=='cr_bulan'?nilai:document.getElementById('cr_bulan').value;
	if(bln !=='' || el=='cr_bulan'){
	 	if(thn!=='' && thn.length>=4){
			if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
				//alert('cek1');
				$j('#dw_bulan').val(bln);
				$j('#list_data').DataTable().columns(index_data).search(bln).draw();
			}
		 
		}
	}
	
	
	if(thn !== '' && thn.length>=4){
		if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
			//alert(thn);
			$j('#dw_tahun').val(thn);
			$j('#list_data').DataTable().columns(index_data).search(thn).draw();
		}
	 
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
