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
</style>
<style class="cp-pen-styles">
.pagination{
	margin:0 0 0 0;
}
#largeModalChild .modal-dialog{
		width: 40%;
	}
@media screen and (max-width: 767px) {
  
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

  #header-search  td {
    display: table-row;
	
  }
  #header-search td:before {
    content: attr(data-label);
    display: table-cell;
    font-weight: bold;
    padding: 0.2em 0.6em 0.8em 0;
    text-align: right;
  }
  #header-search td:before .form-control {
   	width:100%;
  }
  #header-search td:last-child:after {
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



@media screen and (min-width: 768px) {
 


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
                  <th id="no_urut2" >No</th>
                   <th style="width:80px;">Odoo Account ID</th>
                  <th style="">Odoo Account Code</th>
                  <th style="width:110px;">Odoo Account Name</th>
                  <th style="width:110px;">Old Accont Code</th>
                  <th style="width:150px;"><span style="width:110px;">Old Accont Code</span></th>
                  <th style="width:150px;">Debit</th>
                 
                  <th style="width:170px;">Kredit</th>
                  <th style="width:70px;"></th>
                </tr>
                </thead>
                <thead id="header-search">
                <tr>
                  <td ></td>
                    <td ></td>
                  <td data-label="Pemilik Sapi">
                 <input type="text" data-column="0"  class="search-by-id-anggota form-control"  style="width:100%" placeholder="ID Anggota">
                  </td>
                  <td data-label="Sapi">&nbsp;</td>
                  <td data-label="Sapi"> <input type="text" data-column="1"  class="search-by-eartag form-control"  style="width:100%"></td>
                  <td data-label="Jenis Pelayanan">
               
                  </td>
                  <td data-label="Jenis Pelayanan"></td>
                   <td data-label="Petugas">
                     
                   </td>
                  <td data-label="Aksi" style="vertical-align:middle;text-align:center" class="td-content">
                  <?php echo $TombolTambah;?>
                  </td>
                </tr>
                </thead>
               
              
              </table>
          	  <button type="button" class="btn btn-primary btn-xs btn-export-excel"><i class="fa fa-file-excel-o"></i> Excel</button>
             <button type="button" class="btn btn-primary btn-xs btn-export-pdf"><i class="fa fa-file-pdf-o"></i> PDF</button>
             <img src="<?php echo $theme_path."/images/loader.gif";?>" style="display:none; height:8px;"  id="spinner_download"/>
             <form method="post" id="form-download"  target="media-download">
             <input type="hidden" name="dw_tahun" id="dw_tahun" size="3" />
             <input type="hidden" name="dw_bulan" id="dw_bulan" size="3" />
             </form>
             <div id='progress_wrapper'  style="float:left">
                    <div id="progressor" ></div>
            </div>
             <iframe name="media-download" style=""></iframe>
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

<div class="modal fade" id="largeModalChild" tabindex="-1" role="dialog" aria-labelledby="largeModalChild" aria-hidden="true" data-backdrop="false">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            
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
						{ "orderable": false, "targets": [3,4]},
						{
							  "targets": [1,,2], // your case first column
							  "className": "text-center"
						 },
						 {
							  "targets": [6,7], // your case first column
							  "className": "text-right"
						 }
					 ],
					"columns": [
						{ "data": "No",'sortable': false},
						{ "data": "odoo_account_id"},
						{ "data": "odoo_account_code"},
						{ "data": "odoo_account_name"},
						{ "data": "old_account_code"},
						{ "data": "old_account_name"},
						{ "data": "debit"},
						{ "data": "kredit"},
						{ "data": "Aksi"}
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						console.log(aData);
						$j(nRow).attr('id', 'tr_' + aData.ID); // or whatever you choose to set as the id
						
					},
					"initComplete": function(settings, json) {
						$j( "#list_data_wrapper .row" ).find( "div" ).eq( 0 ).attr('class', 'col-sm-3');
						$j( "#list_data_wrapper .row" ).find( "div" ).eq( 2 ).attr('class', 'col-sm-9');
						//$j( "#list_person_wrapper .row" ).find( "div" ).eq( 2 ).css('border', '1px solid');
						
						var element = document.getElementById('list_data_filter');
						var child = document.createElement('div');
						child.style.float = "right";
						child.innerHTML = '<input type="text" name="cr_tahun" class="form-control input-xm cr_tahun" id="cr_tahun" onkeyup="pilih(this.value,6,\'cr_tahun\');" placeholder="tahun" size="2">\n';
						
						var elementParent = element.parentNode;
						elementParent.insertBefore(child, element.nextSibling);
						//preventDefault();
					
					
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
		$j('.search-by-petugas').on( 'change', function () {   // for text boxes
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
		} );
		$j('.search-by-pelayanan').on( 'keyup click', function () {   // for text boxes
			var i =$j(this).attr('data-column');  // getting column index
			
			var v =$j(this).val();  // getting search input value
			
			table.columns(i).search(v).draw();
			
		} );
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
	
	
	
	if(thn !== '' && thn.length>=4){
		if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
			//alert(thn);
			$j('#dw_tahun').val(thn);
			$j('#list_data').DataTable().columns(6).search(thn).draw();
			$j('#list_rekap').DataTable().columns(6).search(thn).draw();
		}
	 
	}
 }
function tutupmodal(modal_id){
	$('#'+modal_id).modal('hide')
}
function loadcheck(url_check,element_for_update,data_serialize)
{
	//$("#load-form").css("text-align","center");
//	alert(url_check);
	//$("#loader_form").fadeIn();
		 $.ajax({
            url : url_check,
			data:data_serialize,
            type : 'POST',
            success: function(msg){
				var obj2 = JSON.parse(msg);
				if(element_for_update=="kawin_sebelumnya"){
                	$('#'+element_for_update).html(obj2.TanggalPelayanan);
				}
				if(element_for_update=="tpk"){
                	$(".row-no_eartag").after( " <div class=\"row-form row-no_eartag\"><span class=\"label\" ></span><strong><i>"+obj2.tpk_name+'/'+obj2.kelompok_name+"</i></strong>as<input type=\"text\" class=\"text\" name=\"anggota_id\" size=\"6\"  value=\""+obj2.anggota_id+"\" /></div>" );
				}
                 //$("#loader_form").fadeOut();
				 
            }
        });
       return false;
} 
function errorForm(msj_obj){
	 if (jQuery.isEmptyObject(msj_obj)==false)
	 {
		 var errors=msj_obj;
		for (var key in errors){
			$j("#"+key).addClass("error");
			$("#"+key).parent().closest('div').after( " <div class=\"row-form row-error\"><span class=\"label\" ></span><span class=\"lbl_error\">"+errors[key]+"</span></div>" );
		}
	 }
}
function simpan(url_save){
		$j(".input").removeClass("error");
		$j(".row-error").remove();
		$j.ajax({
			type:"POST",
			url: url_save,
			data: $j("#form_input_data").serialize(),
			success: function(data, status) {
			
				$j("#media-test").html(data);
				var obj2 = JSON.parse(data);
				if (obj2.success==true){
					//table.columns([0,1,2]).search($("#crProvinsi").val()).draw();
					//loaddata($j("#form_cari").serialize());
					//$( "#list_staff tbody" ).prepend( obj2.html );
					$j('#largeModal .modal-body').html("<h5>"+obj2.message+"</h5>");
					$j('#largeModal .modal-footer #tombol_batal').html("<i class=\"fa fa-times\"></i>&nbsp;Tutup");
					$j('#largeModal .modal-footer #simpan_data').remove();
					if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
						$j('#list_data').DataTable().columns().search().draw();
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
function hapus(url_hapus,id)
{
	$j.ajax({
			type:"POST",
			url: url_hapus,
			data: $j("#form_delete_data").serialize(),
			success: function(data, status) {
				//$j("#media-test").html(data);
				var obj2 = JSON.parse(data);
				if (obj2.success==true){
					
					if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
						$j('#list_data').DataTable().columns().search().draw();
					}
					
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
