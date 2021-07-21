<link rel="stylesheet" href="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $theme_path;?>css/select.dataTables.min.css">
<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

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
	width: 70%;
}

</style>
<style class="cp-pen-styles">
.pagination{
	margin:0 0 0 0;
}
#list_data thead tr th{
	border-collapse:collapse;
	border-bottom:0;

}
@media screen and (max-width: 767px) {
   #list_data_wrapper .row .col-sm-3, #list_bulan_wrapper .row .col-sm-3 {
	  float:left;
  }
   #list_data_wrapper .row .col-sm-9 div, #list_bulan_wrapper .row .col-sm-9 div{
	  float:left;
  }
	.td-content{
		text-align:left;
	}
   .header-data {
    display: none;
  }
  .header-search  tr {
    display: block;
    position: relative;
    padding: 1.2em 0;

  }
  .header-search  tr .no-form {
    display: none;
	background:none;

  }
  .header-search  tr:first-of-type {
    border-top: 1px solid #ccc;
  }

  .header-search tr th {
    display: table-row;
	background-color:#FFF;
	margin-bottom:2px;
	margin-top:0px;

  }
  .header-search tr  th:before {
    content: attr(data-label);
    display: table-cell;
    font-weight: bold;
    padding: 0.2em 0.6em 0.8em 0;
    text-align: right;
  }
  .header-search th:before .form-control {
   	width:100%;
  }
  .header-search th:last-child:after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    border-bottom: 0px solid #ccc;
  }



  #list_data tr,#list_bulan tr{
    display: block;
    position: relative;
    padding: 1.2em 0;
  }
  #list_data tr:first-of-type,#list_bulan tr:first-of-type {
    border-top: 1px solid #ccc;
  }

  #list_data tr td  {
    display: table-row;
	text-align:left;
  }


  #list_data td:before,#list_bulan td:before {
    content: attr(data-label);
    display: table-cell;
    font-weight: bold;
    padding: 0.2em 0.6em 0.2em 0;
    text-align: right;
  }
  #list_data td:last-child:after,#list_bulan td:last-child:after  {
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
	#largeModal .modal-dialog{
		width: 100%;
	}

}



@media screen and (min-width: 320px) {



  #list_data td, #list_data th  {
    padding: 0.4em 0.6em;
    vertical-align: top;
   /* border: 1px solid #ccc;*/
  }

  #list_data th  {
    background: #e1e1e1;
    font-weight: bold;
  }


}
</style>
<div class="row">
    <div class="col-xs-12">

      <div class="box">
        <div class="box-header text-center">
      
        </div>
        <!-- /.box-header -->
        <div class="box-body">


         <table id="list_data" class="table table-bordered table-hover dataTable"  style="width:100%">
            <thead class="header-data">
            <tr>
              <th style="width:25px;" class="row_no">No</th>
              <th style="width:70px;">Tanggal</th>
              <th style="width:55px;">Shift</th>
              <th style="width:110px;">Kontraktor</th>
               <th style="width:110px;">Kategori</th>
                
              <th style="width:70px;text-align:center">Last Verification</th>
              <th style="width:60px;">Status</th>
              <th style="width:75px;text-align:center">Created</th>
              <th style="width:60px;text-align:center">Lastupdated</th>

              <th style="width:60px;text-align:center"><?php echo $TombolTambah?></th>

            </tr>
            </thead>
          </table>
          
          


     </div>
        <!-- /.box-body -->


      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

<style>
.select2-selection__choice{
	color:#09F;
}
</style>
 <div id="media-test"></div>
 
<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Input Data</h4>
        </div>
        <div class="modal-body">
          <!--  <iframe id="media-create-report" style="width:100%;height:300px"></iframe>-->
        </div>
      <div class="modal-footer">


      </div>
    </div>
  </div>
</div><!-- end of modal-->



     
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>
<!-- DataTables -->

<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo $theme_path;?>js/dataTables.select.min.js"></script>
<script>
var $j = jQuery.noConflict();
const showForm = (url, modalSelector) => {
	$j.get(url).done(html => {
			const modal = $j(modalSelector);
			modal.find('.modal-body').html('');
			modal.find('.modal-body').html(html);
	});
}


  $j(document).ready(function() {

		$j.fn.dataTable.ext.errMode = 'none';

		// $.fn.dataTableExt.sErrMode = 'throw';
	//$j('.select2').select2()
	  //  $j('.select-petugas').select2()
				//$j("#list_data_scrollBody th").removeAttr('class');
		var table2=$j('#list_data').DataTable({
			"processing": true,
			"serverSide": true,
			"order": [[ 2, "desc" ]],
			"ajax": {
				"url": "<?php echo $url_listdata;?>",
				"type": "POST"
			},
			"columnDefs": [
				{ "orderable": false, "targets": [0,5,6,7,8,9]},
				{
					  "targets": [0,1,2,4,5,6,7,8,9], // your case first column
					  "className": "text-center"
				 },
				 {
					  "targets": [4], // your case first column
					  "className": "text-right"
				 }
			 ],
			 "columns": [
				{ "data": "no",'sortable': false},
				{ "data": "tanggal" },
				{ "data": "shift" },
				{ "data": "kontraktor" },
				{ "data": "kategori" },				
				{ "data": "current_verification" },
				{ "data": "matrix_state" },
				{ "data": "created" },
				{ "data": "lastupdated" },
				{ "data": "Aksi" }
			],
			'fnCreatedRow': function (nRow, aData, iDataIndex) {
				$j('.row_no').removeClass("sorting_asc");
				$j(nRow).attr('class', 'details-control');
			console.log(aData);
				$j(nRow).attr('id', 'tr_' + aData.ID); // or whatever you choose to set as the id
				$j( nRow ).find('td:eq(0)').attr('data-label',"No");
				$j( nRow ).find('td:eq(1)').attr('data-label',"Barge");
				$j( nRow ).find('td:eq(2)').attr('data-label',"Commenced");
				$j( nRow ).find('td:eq(3)').attr('data-label',"Completed");
				$j( nRow ).find('td:eq(4)').attr('data-label',"Progress");
				$j( nRow ).find('td:eq(5)').attr('data-label',"Ritase");
				$j( nRow ).find('td:eq(6)').attr('data-label',"Final Draught");
				$j( nRow ).find('td:eq(7)').attr('data-label',"Verification");
				$j( nRow ).find('td:eq(8)').attr('data-label',"Aksi");
			},
			"initComplete": function(settings, json) {
				//console.log(json);
				$j('.row_no').removeClass("sorting_asc");
				$j( "#list_data_wrapper .row" ).find( "div" ).eq( 0 ).attr('class', 'col-sm-3');
				$j( "#list_data_wrapper .row" ).find( "div" ).eq( 2 ).attr('class', 'col-sm-9');

				var element = document.getElementById('list_data_filter');
				var child = document.createElement('div');
				child.style.float = "right";
				child.innerHTML = '<select name="cr_bulan" data-column="7" class="form-control input-xm cr_bulan" id="cr_bulan2" onChange="pilih(this.value,7,\'cr_bulan\');" title="Pilih Bulan">\n'+
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
	  '<input type="text" name="cr_tahun" class="form-control input-xm cr_tahun" id="cr_tahun2" onkeyup="pilih(this.value,8,\'cr_tahun\');" placeholder="tahun" size="4">\n';

				var elementParent = element.parentNode;
				elementParent.insertBefore(child, element.nextSibling);



			}
		});
		table2.on('click', '.details-control', function (e) {
			//alert('cek');
			var tr = $j(this).closest('tr');
			var row = table2.row( tr );


			if ( row.child.isShown() ) {
				// This row is already open - close it
				row.child.hide();
				tr.removeClass('shown');
			}else {
				// Open this row
				row.child( matrix_verification(row.data()) ).show();
				//row.child("selamat").show();
				tr.addClass('shown');

			}
		} );
		//table2.removeClass("sorting_asc");
				$j("#list_data_filter").css("display","none");
				$j('#search-by-kontraktor').on( 'keyup click', function () {   // for text boxes
					var i =$j(this).attr('data-column');  // getting column index
					var v =$j(this).val();  // getting search input value

					table2.columns(i).search(v).draw();
					//$j('.row_no').removeClass("sorting_asc");
				} );
				$j('#btn-create-data').on( 'click', function () {   // for text boxes

					const url = $j(this).attr('href');
					const url_verifikasi = $j(this).attr('role');
					
					$j('#largeModal .modal-title').html("Verifikasi Produksi");
					showForm(url+'?category_id=1', '#largeModal');
					var url_save=url+'/draft';
					//var url_verifikasi=url+'/verified';
					$j('#largeModal .modal-footer').html('');
					$j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_close\"><i class=\"fa fa-times\"></i>&nbsp;Tutup</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_create_draft\" onclick=\"simpan_verification('"+url_save+"','"+url_verifikasi+"');\" ><i class=\"fa fa-save\"></i>&nbsp;Buat Draft</button>");
					e.preventDefault();
				});
				
				table2.on('click', '.btn-detail-data', function (e) {
				//$j('.btn-detail-data').on( 'click', function () {   // for text boxes
					var target = $j(this).attr('href');
					$j('#largeModal .modal-title').html("Detail Shipment");
					showForm(target, '#largeModal');
					$j('#largeModal .modal-footer').html('');
					$j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Tutup</button>");
					e.preventDefault();

				} );
				table2.on('click', '.btn-verifikasi-data', function (e) {
					const url = $j(this).attr('href');

					$j('#largeModal .modal-title').html("Verifikasi Produksi");
					showForm(url, '#largeModal');
					var url_save=url+'/draft';
					var url_verifikasi=url+'/verified';
					$j('#largeModal .modal-footer').html('');
					$j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Tutup</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_simpan\" onclick=\"verifikasi('"+url_save+"','tr_id');\" ><i class=\"fa fa-save\"></i>&nbsp;Simpan Draft</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_verifikasi\" onclick=\"verifikasi('"+url_verifikasi+"','tr_id');\" ><i class=\"fa fa-check\"></i>&nbsp;Verifikasi</button>");

					e.preventDefault();
				});
  });
function format ( data ) {
	    html="";
		if (data.Detail.length>0)
		{
		 	var list_data=data.Detail;
			var no=1;
			var html="<div style=\"margin-left:10px;width:100%;\" class=\"media-child\">Detail Ritase/Quantity :<br /><table  border=\"1\" class=\"list-child table table-bordered dataTable\" style=\"width:auto;border-top:1px;text-align:left;\" ><tr style=\"border-top:1px;\"><th style=\"width:25px;text-align:center;\">No</th><th style=\"width:190px;text-align:center;\">Kontraktor</th><th style=\"text-align:center;\">Tanggal</th><th style=\"text-align:center;\">Shift</th><th style=\"text-align:center;\">Ritase</th><th style=\"text-align:center;\">Draught Survey (MT)</th></tr>";
			for (var key in list_data){
				//alert(list_obat[key].ObatNama);
				html=html+'<tr><td style=\"text-align:center;\">'+no+'</td><td style=\"text-align:left;\">'+list_data[key].name+'</td><td style=\"text-align:left;\">'+list_data[key].tanggal+'</td><td style=\"text-align:left;\">'+list_data[key].shift+'</td><td style=\"text-align:center;\">'+list_data[key].ritase+' ('+list_data[key].quantity+'MT)</td><td style=\"text-align:center;\">'+ list_data[key].draught_survey+'</td></tr>';
				no++;
			}
			html=html+'</table><div>';
		}else{
			html="<div style=\"width:100%;text-align:center;\" class=\"media-no-child\">Tidak ada data rincian</div>";
		}
		return html;
	}
function matrix_verification ( data ) {
	    html="";
		if (data.MatrixVerification.length>0)
		{
		 	var list_data=data.MatrixVerification;
			var no=1;
			var html="<div style=\"margin-left:10px;width:100%;\" class=\"media-child\">History Verification :<br /><table  border=\"1\" class=\"list-child table table-bordered dataTable\" style=\"width:auto;border-top:1px;text-align:left;\" ><tr style=\"border-top:1px;\"><th style=\"width:25px;text-align:center;\">No</th><th style=\"width:190px;text-align:center;\">Verifikasi</th><th style=\"text-align:center;\">Status</th><th style=\"text-align:center;\">Lastupdate</th><th style=\"text-align:center;\">Verified Time</th><th style=\"text-align:center;\">Verifikator</th></tr>";
			for (var key in list_data){
				//alert(list_obat[key].ObatNama);
				html=html+'<tr><td style=\"text-align:center;\">'+no+'</td><td style=\"text-align:left;\">'+list_data[key].matrix_name+'</td><td style=\"text-align:left;\">'+list_data[key].matrix_state+'</td><td style=\"text-align:left;\">'+list_data[key].lastupdated+'</td><td style=\"text-align:center;\">'+list_data[key].verified_time+' </td><td style=\"text-align:center;\">'+ list_data[key].verifier.Name+'</td></tr>';
				no++;
			}
			html=html+'</table><div>';
		}else{
			html="<div style=\"width:100%;text-align:center;\" class=\"media-no-child\">Tidak ada data rincian</div>";
		}
		return html;
	}
function pilih(nilai,index_data,el){

	var thn = el=='cr_tahun'?nilai:document.getElementById('cr_tahun2').value;
	var bln = el=='cr_bulan'?nilai:document.getElementById('cr_bulan2').value;
	if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
		//alert('cek1');
		$j('#list_data').DataTable().columns(7).search(bln).draw();
		$j('#list_data').DataTable().columns(8).search(thn).draw();
	}
	
 }
 function simpan_verification(url_save,url_verifikasi){
	
		//$j(".input").removeClass("error");
		//alert($j("#form_input_data").serialize());
		$j.ajax({
			type:"POST",
			url: url_save,
			data: $j("#form_input_data").serialize(),
			success: function(data, status) {
				//alert(data);
				//$j("#media-test").html(data);
				var obj2 = JSON.parse(data);
				if (obj2.success==true){
					alert(obj2.message);
					$j('#largeModal .modal-footer #tombol_create_draft').remove();
					url_draft=url_verifikasi+'/'+obj2.verification_id+'/draft';
					url_verified=url_verifikasi+'/'+obj2.verification_id+'/verified';
				
					$j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_simpan_draft\" onclick=\"verifikasi('"+url_draft+"','tr_id');\" ><i class=\"fa fa-save\"></i>&nbsp;Simpan Draft</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_verifikasi\" onclick=\"verifikasi('"+url_verified+"','tr_id');\" ><i class=\"fa fa-check\"></i>&nbsp;Verifikasi</button>");
					
					if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
						$j('#list_data').DataTable().columns().search().draw();
					}
					
					//$j('#largeModalChild .modal-body').html("<h5>"+obj2.message+"</h5>");
					//$j('#largeModalChild .modal-footer #tombol_batal').html("<i class=\"fa fa-times\"></i>&nbsp;Tutup");
					//$j('#largeModalChild .modal-footer #simpan_data').remove();
					//$j('#largeModal .modal-footer #simpan_verifikasi').remove();
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
function verifikasi(url_verfikasi,id){
	//alert(url_verfikasi);
	// var ischecked= $j('#konfirmasi').is(':checked');
	//if	(ischecked==true ){	
	$j('.row-error').remove();
	$j(".row-form").removeClass("error");
	  $j.ajax({
			type:"POST",
			url: url_verfikasi,
			data: $j("#form_input_data").serialize(),
			success: function(data, status) {
				//$j("#media-test").html(data);
				var obj2 = JSON.parse(data);
				if (obj2.success==true){
					alert(obj2.message);
					//loaddata($j("#form_cari").serialize());
					//$('#largeModal .modal-footer').remove();
					//$('#largeModal .modal-body').html(obj2.pesan);
					if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
						$j('#list_data').DataTable().columns().search().draw();
					}

					//$j('#largeModal .modal-body').html("<h5>"+obj2.message+"</h5>");
					//$j('#largeModal .modal-footer #tombol_batal').html("<i class=\"fa fa-times\"></i>&nbsp;Tutup");
					//$j('#largeModal .modal-footer #tombol_verifikasi').remove();


				}else{

					alert(obj2.message);
					if( obj2.form_error !== undefined)
					{
						errorForm(obj2.form_error);
						if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
							$j('#list_data').DataTable().columns().search().draw();
						}
					}
				}

			}
		});
	/*}else{
		alert('Pastikan konfirmasi verifikasi dicentang');
	}*/
  }
  function errorForm(msj_obj){
	  
	 if (jQuery.isEmptyObject(msj_obj)==false)
	 {
		 var errors=msj_obj;
		 $j('.row-error').remove();
		for (var key in errors){
			if(key=='konfirmasi'){
				//$("#"+key).addClass("error");
				$j("#"+key).parent().closest('div').addClass("error");
				$j("#"+key).parent().closest('div').after( " <div class=\"row-form row-error\"><span class=\"lbl_error\">"+errors[key]+"</span></div>" );
			}else{
				$j("#"+key).parent().closest('div').after( " <div class=\"row-form row-error\"><span class=\"label\" ></span><span class=\"lbl_error\">"+errors[key]+"</span></div>" );
			}
			
		}
	 }
}	
</script>
