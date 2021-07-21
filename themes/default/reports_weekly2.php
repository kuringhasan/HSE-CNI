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
#list_rekap tr th{
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
        <div class="box-header text-center">
          <h3 class="box-title" id="listdata-title" >
        
          </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
       
         
         <table id="list_data" class="table table-bordered table-hover dataTable"  style="width:100%">
            <thead>
            <tr>
              <th style="width:25px;" class="no_urut">No</th>
              <th style="width:170px;">Week</th>
              <th style="width:60px;">HJS </th> 
              <th style="width:60px;">LCP</th>
              <th style="width:60px;">PL</th>
             <th style="width:60px;">BKM</th>
             <th style="width:60px;">Subtotal HJS</th>
             <th style="width:60px;">Subtotal LCP</th>
             <th style="width:60px;">Subtotal PL</th>
             <th style="width:60px;">Subtotal BKM</th>
             <th style="width:60px;">Total</th>
             <th style="width:60px;">Plan</th>
             <th style="width:60px;">Total Plan</th>
              
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

<script>


var $j = jQuery.noConflict();
  $j(document).ready(function() {
	  
    // $j('#list_kota').removeClass("sorting_asc");
	var table=$j('#list_data').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax": {
						"url": "<?php echo $url_listdata;?>/listdata",
						"type": "POST"
					},
					"columnDefs": [
						{ "orderable": false, "targets": [1,3,5,6]},
						{
							  "targets": [0,2,4,5], // your case first column
							  "className": "text-center"
						 }
					 ],
						"columns": [
						{ "data": "No",'sortable': false,'className':'col-number' },
						{ "data": "week" },
						{ "data": "tahun" },
						{ "data": "qty_hjs" },
						{ "data": "qty_lcp" },
						{ "data": "qty_pl" },
						{ "data": "qty_bkm" },
						{ "data": "total_hjs" },
						{ "data": "total_lcp" },
						{ "data": "total_pl" },
						{ "data": "total_bkm" },
						 "data": "plan" },
						{ "data": "total_plan" }
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						console.log(aData);
						$j(nRow).attr('id', 'tr_' + aData.ID); // or whatever you choose to set as the id
					},
					"initComplete": function(settings, json) {
						
					
					  
					  },
					 "drawCallback": function ( settings ) {
						  console.log(settings);
						  $j("#listdata-title").html(settings.json.title);
					 }
				});
	   alert('cel');
		$j("#list_data_filter").css("display","none");
		
		//var i ="";  // getting column index
		//var v =""; 
		// Apply the search
       
		$j('.search-by-pelayanan').on( 'keyup click', function () {   // for text boxes
		
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
		} );
		$j('.search-by-petugas').on( 'keyup click', function () {   // for text boxes
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
  function pilih(nilai,index_data,el){
	
	var thn = el=='cr_tahun'?nilai:document.getElementById('cr_tahun').value;
	var bln = el=='cr_bulan'?nilai:document.getElementById('cr_bulan').value;
	if(bln !=='' || el=='cr_bulan'){
	 	if(thn!=='' && thn.length>=4){
			if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
				//alert('cek1');
				$j('#dw_bulan').val(bln);
				$j('#list_data').DataTable().columns(5).search(bln).draw();
				$j('#list_rekap').DataTable().columns(5).search(bln).draw();
			}
		 
		}
	}
	
	
	if(thn !== '' && thn.length>=4){
		if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
			//alert(thn);
			$j('#dw_tahun').val(thn);
			$j('#list_data').DataTable().columns(6).search(thn).draw();
			$j('#list_rekap').DataTable().columns(6).search(thn).draw();
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
