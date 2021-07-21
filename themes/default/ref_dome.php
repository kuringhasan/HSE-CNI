<link rel="stylesheet" href="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">


<style>
.btn-action{
	text-align:center;
}
.col-number{
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
									<th></th>
                  <th style="width:35px;" id="row_no">No</th>
                  <th style="width:150px;">Nama</th>
                  <th style="width:200px;">Kontraktor</th>
                  <th style="width:85px;">Status</th>
                  <th style="width:85px;"></th>
                </tr>
                </thead>
                 <thead>
                <tr>
                  <th></th>
                  <th >
                  <input type="text" data-column="1"  class="form-control search-by-name" >
                  </th>
                   <th >
                    <select name="search-by-contractor" data-column="2" class="form-control input-xm search-by-contractor" id="search-by-contractor" style="width:100%">
                          <?php
                        echo '<option value="">-- Kontraktor --</option>';
                        $List=$list_contractor;
                        while($data = each($List)) {
                           ?>
                      <option value="<?php echo $data['key'];?>" ><?php echo $data['value'];?></option>
                      <?php

                        }
                     ?>
              		</select>
                  </th>
                   <th  style="text-align:center">
                    <select name="search-by-status" data-column="3"  class="input search-by-status" style=";" id="search-by-status">
											<option value="">--status--</option>
											<option value="ready">Ready</option>
											<option value="open">Open</option>
                                            <option value="pending">Pending</option>
                                            <option value="close">Close</option>
                                            <option value="shipping">Shipping</option>
                                            <option value="empty">Empty</option>
										</select>
                   </th>
                  <th  style="text-align:center">
                  <?php echo $TombolTambah;?>
                  </th>
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

	  <script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>
<!-- DataTables -->

<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>


<script>
var $j = jQuery.noConflict();
  $j(document).ready(function() {


    // $j('#list_data').removeClass("sorting_asc");
	var table=$j('#list_data').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax": {
						"url": "<?php echo $url_listdata;?>",
						"type": "POST"
					},
					"columnDefs": [
						{ "orderable": false, "targets": [0,4] },
						{"targets": [0,3],"className": "text-center"}
					 ],
					 "order": [
						 [0, 'desc']
					 ],
					"columns": [
						{ 'data': 'ID', 'name': 'id', 'visible': false },
						{ "data": "No",'sortable': false,'className':'col-number' },
						{ "data": "Nama" },
						{ "data": "Kontraktor" },
						{ "data": "State" },
						{ "data": "Tombol",'className':'btn-action' }
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						$j(nRow).attr('id', 'tr_' + aData.Kode); // or whatever you choose to set as the id
					},
					"initComplete": function(settings, json) {
						console.log(json);
						$j('#row_no').removeClass("sorting_asc");
					  }
				});

		$j("#list_data_filter").css("display","none");
		//var i ="";  // getting column index
		//var v ="";
		$j('.search-by-name').on( 'keyup click', function () {   // for text boxes
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
		} );
		$j('.search-by-contractor').on( 'keyup click', function () {   // for text boxes
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
		} );
		$j('.search-by-status').on( 'keyup click', function () {   // for text boxes
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
		} );

		$j('#btn-tambah-data').on( 'click', function () {   // for text boxes
			var target = $j(this).attr('href');
		  	$j('#largeModal .modal-title').html("Form Input");
			$j('#largeModal .modal-body').load(target, function() {
				 $j('#largeModal').modal('show');
			});
			$j('#largeModal .modal-footer').html('');
			$j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/save');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
		});

		table.on('click', '.btn-edit-data', function (e) {
			var target = $j(this).attr('href');
alert(target);
		  	$j('#largeModal .modal-title').html("Form Edit");
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
	alert(url_save);
		$j(".input").removeClass("error");

		const data = getFormData('#form_input_data');

		$j.post(url_save, data).done((data) => {
					const result = JSON.parse(data);
					if (result.success) {
							if ($j.fn.DataTable.isDataTable('#list_data'))
								$j('#list_data').DataTable().columns().search().draw();

							const largeModal = $j('#largeModal');
							largeModal.find('.modal-body')
							largeModal.find('.modal-footer #tombol_batal').html('<i class="fa fa-times"></i>&nbsp;Tutup');
							largeModal.find('.modal-body #simpan_data').remove();

							// ah yea jquery cant reach this block, but im not giving up by using this calbaaaaaaack
							try {
									$('#largeModal').modal('hide');
							} catch (e) {
									console.log(e);
							}
					} else {
							if (result.form_error) errorForm(result.form_error);
					}
		}).fail(() => {
	    	// callback fail
	  });

		// $j.ajax({
		// 	type:"POST",
		// 	url: url_save,
		// 	data: $j("#form_input_data").serialize(),
		// 	success: function(data, status) {
		//
		// 		//$j("#media-test").html(data);
		// 		var obj2 = JSON.parse(data);
		// 		if (obj2.success==true){
		// 			if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
		// 				$j('#list_data').DataTable().columns().search().draw();
		// 			}
		// 			$j('#largeModal .modal-body').html("<h5>"+obj2.message+"</h5>");
		// 			$j('#largeModal .modal-footer #tombol_batal').html("<i class=\"fa fa-times\"></i>&nbsp;Tutup");
		// 			$j('#largeModal .modal-footer #simpan_data').remove();
		//
		// 		}else{
		// 			if( obj2.form_error !== undefined)
		// 			{
		// 				errorForm(obj2.form_error);
		// 			}
		// 		}
		//
		// 		// $('#largeModal').modal('hide');
		//
		// 		//$('#remoteModal').removeData('bs.modal');
		// 		//$('#remoteModal .modal-content').html(data);
		// 	}
		// });
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
					if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
						$j('#list_data').DataTable().columns().search().draw();
					}


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

	const getFormData = (formSelector) => {
		const data = $(formSelector).serializeArray().reduce(function(obj, item) {
		    obj[item.name] = item.value;
		    return obj;
		}, {});

		return data;
	}

	const showFormUpdate = async (modalSelector, url, id) => {
			const updateForm = await $.get(url).done((html) => html).fail(err => null);

			if (updateForm) {
					const modalUpdate = $(modalSelector);

					modalUpdate.find('.modal-title').text('Form Update');
					modalUpdate.find('.modal-body').html(updateForm);
					modalUpdate.find('.modal-footer').html(`<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" onclick=\"updateSubmit('${modalSelector}', '${url}/save')\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>`);
					modalUpdate.modal('show');
			}
	}

	const updateSubmit = (modalSelector, urlSubmit) => {
			const modalUpdate = $(modalSelector);
			const data = getFormData(modalUpdate.find('form'));

			$.post(urlSubmit, data).done((data) => {
					const result = JSON.parse(data);
					if (result.success) {
							if ($j.fn.DataTable.isDataTable('#list_data'))
								$j('#list_data').DataTable().columns().search().draw();

								modalUpdate.find('.modal-body').html('');
								modalUpdate.find('.modal-footer').html('');
								modalUpdate.modal('hide');
					} else {
							if (result.form_error) errorForm(result.form_error);
					}
			});
	}
</script>
