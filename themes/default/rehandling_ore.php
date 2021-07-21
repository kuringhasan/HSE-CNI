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
          <h3 class="box-title" id="listdata-title" >
        Rehandling Ore Report
          </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">


         <table id="list_data" class="table table-bordered table-hover dataTable"  style="width:100%">
            <thead class="header-data">
            <tr>
				<th style="width:25px;" class="row_no">No</th>
				<th style="width:65px;">Tanggal</th>
				<th style="width:50px;">Shift</th>
				<th style="width:60px;">Entry Time</th>
               	<th style="width:60px;">Sent Time</th>
                <th style="width:60px;">Received Time</th>
				<th style="width:200px;">Kontraktor</th>
				<th style="width:120px;">Barge</th>
				<th style="width:120px;">Dome Asal</th>
				<th style="width:50px;text-align:center">Ritase</th>
				<th style="width:60px;text-align:center">Qty</th>
				<th style="width:60px;text-align:center">Checker</th>
				<th style="width:60px;text-align:center">Status</th>
              	<th style="width:150px;text-align:center"><?php echo $TombolTambah?></th>

            </tr>
            </thead>
             <thead class="header-search" >
             <tr>
              <th class="no-form">&nbsp;</th>
              <th ><input type="text" data-column="1"  class="form-control search-by-tanggal" style="width:100%" id="search-by-tanggal" placeholder="Tanggal"></th>
              <th class="no-form">&nbsp;</th>
              <th class="no-form">&nbsp;</th>
              <th class="no-form">&nbsp;</th>
              <th class="no-form">&nbsp;</th>
              <th >
                <select name="search-by-kontraktor" data-column="4" class="form-control search-by-kontraktor" id="search-by-kontraktor" style="width:100%">
                  <?php
                        echo '<option value="">-- Kontraktor --</option>';
                        $List=$list_kontraktor;
                        while($data = each($List)) {
                           ?>
                  <option value="<?php echo $data['key'];?>" ><?php echo $data['value'];?></option>
                  <?php

                        }
                     ?>
                  </select>
              </th>
	<th class="no-form"><input type="text" data-column="5"  class="form-control search-by-barge" style="width:100%" id="search-by-barge" placeholder="Barge"></th>
              <th class="no-form">&nbsp;</th>
              <th class="no-form">&nbsp;</th>
              <th class="no-form">&nbsp;</th>
              <th class="no-form">&nbsp;</th>
              <th class="no-form">&nbsp;</th>
		<th class="no-form">&nbsp;</th>
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
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
              <h3 class="box-title">Download Report</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

             <form method="post" id="form-download"  target="media-download">
              <input type="hidden" name="dw_tahun" id="dw_tahun" size="3" />
             <input type="hidden" name="dw_bulan" id="dw_bulan" size="3" />
             <div class="form-group">
                <select name="tpk[]" class="form-control input select2" multiple="multiple" data-placeholder="Pilih Periode"
                        style="width:90%;color:#03C;">
                 		<?php


                        $List=$ListTPK;
                        while($data = each($List)) {

                           ?>
                      <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$tpk?"selected":""; ?> ><?php echo $data['value'];?></option>
                      <?php

                        }
                     ?>
                </select>
             	</div>
                <div class="form-group">
                 <?php
				//print_r($ListPetugas);echo "</pre>";?>
                <select name="staf_petugas[]" class="form-control input select-petugas" multiple="multiple" data-placeholder="Pilih Kontraktor"
                        style="width:90%;color:#03C;">
                 		<?php


                        $List=$ListPetugas;
                        while($data = each($List)) {

                           ?>
                      <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$tpk?"selected":""; ?> ><?php echo $data['value'];?></option>
                      <?php

                        }
                     ?>
                </select>
             	</div>
             </form>
                <?php echo $TombolDownload;?>
                <?php echo $TombolDownloadPDF;?>
             <img src="<?php echo $theme_path."/images/loader.gif";?>" style="display:none; height:8px;"  id="spinner_loading"/>
             <div id='progress_wrapper'  style="float:left">
                    <div id="progressor" ></div>
            </div>
             <iframe name="media-download" style="display:none;"></iframe>

			</div>
        </div><!-- /.box -->
    </div>
<!-- /.col -->

	</div>

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
<div id="ModalRitase" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><h4>Tambah Ritase</h4></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-xs">Simpan</button>
      </div>
    </div>
  </div>
</div>


      <div id="media-test"></div>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>
<!-- DataTables -->

<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/select2/dist/js/select2.full.min.js"></script>
<script>
const currentUrl = window.location.href;

var $j = jQuery.noConflict();
  $j(document).ready(function() {
	$j('.select2').select2()
	    $j('.select-petugas').select2()
				//$j("#list_data_scrollBody th").removeAttr('class');
		var table2=$j('#list_data').DataTable({
			"processing": true,
			"serverSide": true,
			"order": [[ 1, "desc" ]],
			"ajax": {
				"url": "<?php echo $url_listdata;?>",
				"type": "POST"
			},
			"columnDefs": [
				{ "orderable": false, "targets": [0,5,8]},
				{
					  "targets": [0,1,2,5,13], // your case first column
					  "className": "text-center"
				 },
				 {
					  "targets": [4], // your case first column
					  "className": "text-right"
				 }
			 ],
			 "columns": [
				{ "data": "No",'sortable': false},
				{ "data": "Tanggal" },
				{ "data": "shift" },
				{ "data": "entry_time" },
				{ "data": "sent_time" },
				{ "data": "received_time" },
				{ "data": "Kontraktor" },
				{ "data": "barge_name" },
				{ "data": "dome_name" },
				{ "data": "total_ritase" },
				{ "data": "total_quantity" },
				{ "data": "checker" },
				{ "data": "state" },
				// { data: "Aksi",
				// 	render: function(data , type , row) { if(row.state == "draft"){  return row.AksiDraft  }else{ return data }  },
				// 	sortable: false 
				// }
				{ data: "Aksi",'sortable': false}
			],
			'fnCreatedRow': function (nRow, aData, iDataIndex) {
				$j('.row_no').removeClass("sorting_asc");
				$j(nRow).attr('class', 'details-control');
			console.log(aData);
				$j(nRow).attr('id', 'tr_' + aData.ID); // or whatever you choose to set as the id
				$j( nRow ).find('td:eq(0)').attr('data-label',"No");
				$j( nRow ).find('td:eq(1)').attr('data-label',"Tanggal");
				$j( nRow ).find('td:eq(2)').attr('data-label',"Shift");
				$j( nRow ).find('td:eq(3)').attr('data-label',"Entry Time");
				$j( nRow ).find('td:eq(4)').attr('data-label',"Sent Time");
				$j( nRow ).find('td:eq(5)').attr('data-label',"Received Time");
				$j( nRow ).find('td:eq(6)').attr('data-label',"Lokasi PIT");
				$j( nRow ).find('td:eq(7)').attr('data-label',"Kontraktor");
				$j( nRow ).find('td:eq(8)').attr('data-label',"Barge");
				$j( nRow ).find('td:eq(9)').attr('data-label',"Ritase");
				$j( nRow ).find('td:eq(10)').attr('data-label',"Quantity");
				$j( nRow ).find('td:eq(11)').attr('data-label',"Aksi");
				if(aData.state == "draft"){
					$j( nRow ).addClass("label-warning");
				}
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
				row.child( format(row.data()) ).show();
				//row.child("selamat").show();
				tr.addClass('shown');

			}
		} );
		//table2.removeClass("sorting_asc");
				$j("#list_data_filter").css("display","none");
				$j('#search-by-tanggal').on( 'keyup click', function () {   // for text boxes
					var i =$j(this).attr('data-column');  // getting column index
					var v =$j(this).val();  // getting search input value

					table2.columns(i).search(v).draw();
					//$j('.row_no').removeClass("sorting_asc");
				} );
				$j('#search-by-kontraktor').on( 'keyup click', function () {   // for text boxes
					var i =$j(this).attr('data-column');  // getting column index
					var v =$j(this).val();  // getting search input value

					table2.columns(i).search(v).draw();
					//$j('.row_no').removeClass("sorting_asc");
				} );
				$j('#search-by-barge').on( 'keyup click', function () {   // for text boxes
					var i =$j(this).attr('data-column');  // getting column index
					var v =$j(this).val();  // getting search input value

					table2.columns(i).search(v).draw();
					//$j('.row_no').removeClass("sorting_asc");
				} );
				$j('#btn-tambah-data').on( 'click', function () {   // for text boxes

					var target = $j(this).attr('href');
					$j('#largeModal .modal-title').html("Form Data Produksi");
					$j('#largeModal .modal-body').load(target, function() {
						 $j('#largeModal').modal('show');
					});
					$j('#largeModal .modal-footer').html('');
					// $j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/save');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
					$j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/save?type=simpan');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/save?type=kirim');\"><i class=\"fa fa-send\"></i>&nbsp;Kirim</button>");
				} );
				table2.on('click', '.btn-edit-data', function (e) {
				//$j('.btn-detail-data').on( 'click', function () {   // for text boxes
					var target = $j(this).attr('href');
					$j('#largeModal .modal-title').html("Edit Rehandling Ore");
					$j('#largeModal .modal-body').load(target, function() {
						 $j('#largeModal').modal('show');
					});
					$j('#largeModal .modal-footer').html('');
					$j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Tutup</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/save');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>  <button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/kirim');\"><i class=\"fa fa-send\"></i>&nbsp;Kirim</button>");

				} );
				table2.on('click', '.btn-detail-data', function (e) {
				//$j('.btn-detail-data').on( 'click', function () {   // for text boxes
					var target = $j(this).attr('href');
					$j('#largeModal .modal-title').html("Detail Rehandling Ore");
					$j('#largeModal .modal-body').load(target, function() {
						 $j('#largeModal').modal('show');
					});
					$j('#largeModal .modal-footer').html('');
					$j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Tutup</button>");

				} );
				table2.on('click', '.btn-verifikasi-data', function (e) {
					var target = $j(this).attr('href');
					var tr_id = $j(this).attr('role');
					$j('#largeModal .modal-title').html("Verifikasi Produksi");
					$j('#largeModal .modal-body').load(target, function() {
						 $j('#largeModal').modal('show');
					});
					$j('#largeModal .modal-footer').html('');
					 $j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_verifikasi\" onclick=\"verifikasi('"+target+"','"+tr_id+"');\" ><i class=\"fa fa-check\"></i>&nbsp;Verifikasi</button>");


					e.preventDefault();
				});
  });
function format ( data ) {
	    html="";
		if (data.Detail.length>0)
		{
		 	var list_data=data.Detail;
			var no=1;
			var html="<div style=\"margin-left:10px;width:100%;\" class=\"media-child\">Ritase :<br /><table  border=\"1\" class=\"list-child table table-bordered dataTable\" style=\"width:auto;border-top:1px;text-align:left;\" ><tr style=\"border-top:1px;\"><th style=\"width:25px;text-align:center;\">No</th><th style=\"width:100px;text-align:left;\">Dome Asal</th><th style=\"width:100px;text-align:left;\">Drum Truck</th><th style=\"width:60px;text-align:center;\">Ritase</th><th style=\"width:60px;text-align:center;\">Qty (Ton)</th></tr>";
			for (var key in list_data){
				//alert(list_obat[key].ObatNama);
				html=html+'<tr><td style=\"text-align:center;\">'+no+'</td><td style=\"text-align:left;\">'+list_data[key].dome_name+'</td><td style=\"text-align:left;\">'+list_data[key].truck_nomor+'</td><td style=\"text-align:center;\">'+list_data[key].ritase+'</td><td style=\"text-align:center;\">'+list_data[key].quantity+'</td></tr>';
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

	if(bln !=='' || el=='cr_bulan'){
		$j('#dw_bulan').val(bln);
	 	if(thn!=='' && thn.length>=4){
			if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
				//alert('cek1');
				$j('#list_data').DataTable().columns(4).search(bln).draw();
				//$j('#list_data').DataTable().columns(7).search(thn).draw();
			}

		}
	}


	if(thn !== '' && thn.length>=4){
		//alert(bln +'  '+thn);
		$j('#dw_tahun').val(thn);
		if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {

			$j('#list_data').DataTable().columns(5).search(thn).draw();

			//$j('#list_data').DataTable().columns(6).search(bln).draw();
		}
		$j("#cr_tahun").val(thn);
		$j('#list_data').DataTable().columns(10).search(thn).draw();

	}
 }
 function simpan(url_save){
	//alert(url_save);
		$j(".input").removeClass("error");
		$j.ajax({
			type:"POST",
			url: url_save,
			data: {datas : $j("#form_input_data").serializeArray(), ritases : ritases},
			success: function(data, status) {
				//$j("#media-test").html(data);
				var obj2 = JSON.parse(data);
				if (obj2.success==true){
					//table.columns([0,1,2]).search($("#crProvinsi").val()).draw();
					//loaddata($j("#form_cari").serialize());
					if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
						$j('#list_data').DataTable().columns().search().draw();
					}
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
function verifikasi(url_verfikasi,id){
	  //alert(url_verfikasi+'/save');
	  $j.ajax({
			type:"POST",
			url: url_verfikasi+'/save',
			data: $j("#form_input_data").serialize(),
			success: function(data, status) {

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
						if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
							$j('#list_data').DataTable().columns().search().draw();
						}
					}
				}

			}
		});
  }
  function deleteSubmit(id){
		var result = confirm("Anda yakin ingin menghapus data?");
			if (result) {
				// alert();
				$.post(currentUrl + '/deleteSubmit', {id:id}).done(() => {
				$j('#list_data').DataTable().draw();
				});
			}
        
    }
</script>
