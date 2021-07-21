<!-- <link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" /> -->
<!-- <link rel="stylesheet" href="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"> -->

<style>

.responsive-form .label{
	min-width:195px;
	width:auto;
}
.label_typeahead{
	width:300px;
	height:auto;
	white-space:pre-line;
	background-color:#06F
	margin:0px 0px 0px 0px;
}
.wajib{
	color:#F00;
}
.catatan{
	display:inline-block;
	font-size:0.7em;
}
.row-form{


}
.text-center{
	text-align:center;
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
<?php //echo "<pre>";print_r($detail);echo "</pre>";    ?>

<form id="form_input_data"  action="<?php echo $url_action; ?>/save" method="post" style="display:inline-block" enctype="multipart/form-data" >
        <div class="responsive-form" >
        <input type="hidden" class="input" name="current_step" id="current_step" size="2" value="<?php echo $step;?>"/>
        <input type="hidden" class="input" name="skip" id="skip" size="5" value=""/>
         <input type="hidden" class="input" name="id" id="id" size="4" value="<?php echo $detail->id;?>"/>

        <?php

			if($step==1 ){



				$jetty_id=(isset($detail->jetty_id)==true and trim($detail->jetty_id)<>"")?$detail->jetty_id:"";
				$gate_id=(isset($detail->gate_id)==true and trim($detail->gate_id)<>"")?$detail->gate_id:"";

			?>

			<script>
			$(document).ready(function () {
				$("#berth_time").datetimepicker({
					 format: 'dd/mm/yyyy hh:ii',
					 minuteStep: 1,
					 autoclose: true
				});
				$("#commenced_time").datetimepicker({
					 format: 'dd/mm/yyyy hh:ii',
					 minuteStep: 1,
					 autoclose: true
				});
				comboAjax('<?php echo $url_comboAjax;?>/listgate','<?php echo $jetty_id;?>','gate_id','<?php echo $gate_id;?>','','loader_gate');
			    $("#jetty_id").change(function(){
					var parentkode=	$(this).val();
					comboAjax('<?php echo $url_comboAjax;?>/listgate',parentkode,'gate_id','','','loader_gate');
				});


			});

			</script>


				<div class="row-form">
                    <span class="label" >Barge/Tongkang <small class="wajib">*</small></span>

                     <select name="barge_id" id="barge_id" class="input" >
                         <?php
                        echo '<option value="">--Barge--</option>';

                            $List=$list_barge;
                            while($data = each($List)) {

                                $classification=isset($_POST['barge_id'])?$_POST['barge_id']:$detail->barge_id;
                               ?>
                                <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$classification?"selected":""; ?> >
                             <?php echo $data['value'];?></option>
                             <?php
                            }
                        ?>
                        </select>
                </div>
                <div class="row-form">
                    <span class="label" title="Rencana Muat">Pre Stowage Plan (PSP) <small class="wajib">*</small></span>

                    <input type="text" class="input " name="rencana_muat" id="rencana_muat" size="10" value="<?php echo number_format($detail->pre_stowage_plan,2,",",".");?>" onchange="ubah_angka(this);" />&nbsp;MT

                </div>
                <div class="row-form">
                    <span class="label" title="Lay Time Target">Time Allowed </span>

                    <input type="text" class="input" name="lay_time_target" id="lay_time_target"  size="4" value="<?php echo  number_format($detail->lay_time_plan,2,",",".");?>" onchange="ubah_angka(this);"/> &nbsp; Jam

                </div>
                 <div class="row-form">
                     <span class="label" >Jetty</span>
                    <select name="jetty_id" class="input"  id="jetty_id" >

                         <?php
                            echo '<option value="">--- jetty ---</option>';
                            $List=$list_jetty;
                            while($data = each($List)) {
                               ?>
                        <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$jetty_id?"selected":""; ?> >
                          <?php echo $data['value'];?></option>
                        <?php
                            }
                         ?>
                  </select>
               </div>
               <div class="row-form">
                  <span class="label">Gate  <small class="wajib">*</small></span>

                    <select name="gate_id" id="gate_id"  class="input"  >
                     <?php
                            echo '<option value="">--- gate ---</option>';
                            $List=$ListGate;
                            while($data = each($List)) {
                               ?>
                        <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$kota?"selected":""; ?> >
                          <?php echo $data['value'];?></option>
                        <?php
                            }
                         ?>
                    </select>

                    <img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:16px; vertical-align:middle; border:0px;"  alt=""  id="loader_gate"/>

               </div>
                 <div class="row-form">
                    <span class="label" title="Waktu Sandar">Berth Time <small class="wajib">*</small></span>

                    <input type="text" class="input form_datetime" name="berth_time" id="berth_time" placeholder="Berth"  size="15" value="<?php echo $detail->berth_time2;?>" />

                </div>
                 <div class="row-form">
                    <span class="label">Commenced Time <small class="wajib">*</small></span>

                    <input type="text" class="input" name="commenced_time" id="commenced_time" placeholder="Commenced"  size="15" value="<?php echo $detail->commenced_time2;?>"/>

                </div>
                <div class="row-form">
                    <span class="label">Jumlah Dump Truck </span>

                    <input type="text" class="input" name="jumlah_truck" id="jumlah_truck"  size="2" value="<?php echo $detail->jumlah_truck;?>"/>

                </div>
                 <div class="row-form">
                    <span class="label">Est. Load Per Truck </span>

                    <input type="text" class="input" name="est_load_truck" id="est_load_truck"  size="2" value="<?php echo $detail->est_load_truck;?>"/>

                </div>

			 <?php
			}
			if($step==2){

			?>

			<style>
		   #list_ritase tr td{
			  padding-top:2px;
			  padding-bottom:2px;
			  font-size:11px;
			  vertical-align:middle;
		   }
		   #list_ritase tr th{
			 vertical-align:middle;
			 font-size:11px;
			 background-color:#CCC;
		   }
			</style>
			<div class="row-form">
             <span class="label" >Barge </span> : <?php echo $detail->barge_name;?>
            </div>

           <div class="row-form">
                 <span class="label" >Jetty/Gate </span>  : <?php echo $detail->jetty_name."/".$detail->gate_name;?>

            </div>

            <div class="row-form">
                 <span class="label" >Berth Time</span> : <?php echo $detail->berth_time;?>

            </div>
             <div class="row-form">
                 <span class="label" >Commenced Time</span> : <?php echo $detail->commenced_time;?>

            </div>



             <div class="row-form" style="margin-top:2px;">
              <input type="checkbox" name="lewati_dulu" id="lewati_dulu" style="margin-left:2px;"> Lewati Dulu
			<table  id="list_ritase"  class="table table-bordered table-hover dataTable" style="margin-top:4px; width:auto" >
                <thead>
                <tr >
                    <th class="no_urut" style="width:25px;">No</th>
                    <th style="width:105px;">Kontraktor</th>
                     <th style="width:70px;">Asal Dome</th>
                    <th style="width:65px;">Shift</th>
                    <th style="width:120px;">Mulai</th>
                    <th style="width:120px;">Akhir</th>
                    <th style="width:70px;">Ritase</th>
                    <th style="width:60px;">Intermediate  DS (MT)</th>

                 <th style="width:65px;"><?php echo $tombol_ritase;?></th>
                </tr>
                </thead>

			</table>
             </div>

		  <!-- <script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
			<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>-->
			<!-- <script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
			<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> 	 		 -->
			<script>

			//var $m = jQuery.noConflict();
			$(document).ready(function() {

				var table1=$('#list_ritase').DataTable({
					"processing": true,
					"serverSide": true,
					"ajax": {
						"url": "<?php echo $url_ritase;?>/listdata",
						"type": "POST"
					},
					"columnDefs": [
						{ "orderable": false, "targets": [0,1,2,3,4,5,6,7,8] },
						{"targets": [0,1,2,3,4,5,6,7],"className": "text-center"}
					 ],
					"columns": [
						{ "data": "No",'sortable': false,'className':'col-number' },
						{ "data": "contractor_alias" },
						{ "data": "dome_name" },
						{ "data": "shift" },
						{ "data": "start_time" },
						{ "data": "end_time" },
						{ "data": "ritase" },
						{ "data": "intermediate_draugh_survey" },
						{ "data": "Tombol",'className':'btn-action' }
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						$(nRow).attr('id', 'tr_' + aData.Kode); // or whatever you choose to set as the id
					},
					"initComplete": function(settings, json) {
						//console.log(json);
						//$j('#list_data').removeClass("sorting_asc");
						$('#list_ritase tr th').removeClass("sorting_asc");
						$('#list_ritase_info').remove();;
						$('#list_ritase_paginate').remove();
						$("#list_ritase_length").remove();
						$("#list_ritase_filter").remove();
						$('#list_ritase .no_urut').removeClass("sorting_asc");
					  }
				});
				table1.on('click', '#btn-add-ritase', function (e) {
					var target = $(this).attr('href');
					//alert(target);
					var url_form = target+'/form';
					//alert(target);
					$('#largeModalChild .modal-title').html("Tambah Activity & Ritase");
					$('#largeModalChild .modal-body').load(url_form, function() {
						 $('#largeModalChild').modal('show');
					});
					$('#largeModalChild .modal-footer').html('');
					$('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\"  id=\"tombol_batal_child\" onclick=\"tutup('largeModalChild');\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/add','form_input_ritase','list_ritase','');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
					e.preventDefault();

				} );
				table1.on('click', '.btn-edit-ritase', function (e) {
					var target = $(this).attr('href');
					var ritase_id = $(this).attr('role');
					//alert(target);
					$('#largeModalChild .modal-title').html("Edit Activity & Ritase");
					$('#largeModalChild .modal-body').load(target+'/form?ritase_id='+ritase_id, function() {
						 $('#largeModalChild').modal('show');
					});
					$('#largeModalChild .modal-footer').html('');
					$('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\"  id=\"tombol_batal_child\" onclick=\"tutup('largeModalChild');\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/edit','form_input_ritase','list_ritase','"+ritase_id+"');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
					e.preventDefault();

				} );

				table1.on('click', '.btn-del-ritase', function (e) {
					var tr_id = $(this).attr('role');
					 var url_del = $(this).attr('href')+'/del';

					$('#largeModalChild .modal-title').html("Konfirmasi Hapus Data");
					$('#largeModalChild .modal-body').html("<h4>Yakin data akan dihapus?</h4>");//.css("text-align","center");
					$('#largeModalChild .modal-footer').html('');
					$('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" onclick=\"tutup('largeModalChild');\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_hapus\" onclick=\"hapus('"+url_del+"','"+tr_id+"','list_ritase');\" ><i class=\"fa fa-trash-o\"></i>&nbsp;Hapus</button>");
					e.preventDefault();
				});


			});

			</script>

			<?php
			}
			if($step==3){

			?>

			<style>
		   #list_gangguan tr td{
			  padding-top:2px;
			  padding-bottom:2px;
			  font-size:11px;
			  vertical-align:middle;
		   }
		   #list_gangguan tr th{
			 vertical-align:middle;
			 font-size:11px;
			 background-color:#CCC;
		   }
			</style>
			<div class="row-form">
             <span class="label" >Barge </span> : <?php echo $detail->barge_name;?>
            </div>

           <div class="row-form">
                 <span class="label" >Jetty/Gate </span>  : <?php echo $detail->jetty_name."/".$detail->gate_name;?>

            </div>

            <div class="row-form">
                 <span class="label" >Berth Time</span> : <?php echo $detail->berth_time;?>

            </div>
             <div class="row-form">
                 <span class="label" >Commenced Time</span> : <?php echo $detail->commenced_time;?>

            </div>



             <div class="row-form" style="margin-top:2px;">
              <input type="checkbox" name="tidak_ada_gangguan" id="tidak_ada_gangguan" style="margin-left:2px;"> Tidak Ada Gangguan/Lewati Dulu
			<table  id="list_gangguan"  class="table table-bordered table-hover dataTable" style="margin-top:4px; width:auto" >
                <thead>
                <tr >
                    <th class="no_urut" style="width:25px;">No</th>
                    <th style="width:105px;">Gangguan</th>
                    <th style="width:70px;">Shift</th>
                    <th style="width:120px;">Waktu Mulai</th>
                    <th style="width:120px;">Waktu Akhir</th>
                    <th style="width:60px;">Jumlah Jam</th>
                    <th style="width:150px;">Description</th>
                 <th style="width:60px;"><?php echo $tombol_gangguan;?></th>
                </tr>
                </thead>

			</table>
             </div>

		  <!-- <script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
			<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>
			<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
			<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> -->
			<script>

			//var $m = jQuery.noConflict();
			$(document).ready(function() {
				//alert("<?php echo $url_gangguan;?>/listdata");
				var table1=$('#list_gangguan').DataTable({
					"processing": true,
					"serverSide": true,
					"ajax": {
						"url": "<?php echo $url_gangguan;?>/listdata",
						"type": "POST"
					},
					"columnDefs": [
						{ "orderable": false, "targets": [0,1,2,3,4,5,6,7] },
						{"targets": [0,1,2,3,4,5,6],"className": "text-center"}
					 ],
					"columns": [
						{ "data": "No",'sortable': false,'className':'col-number' },
						{ "data": "name" },
						{ "data": "shift" },
						{ "data": "start_time" },
						{ "data": "end_time" },
						{ "data": "jumlah_jam" },
						{ "data": "description" },
						{ "data": "Tombol",'className':'btn-action' }
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						$(nRow).attr('id', 'tr_' + aData.Kode); // or whatever you choose to set as the id
					},
					"initComplete": function(settings, json) {
						//console.log(json);
						//$j('#list_data').removeClass("sorting_asc");
						$('#list_gangguan tr th').removeClass("sorting_asc");
						$('#list_gangguan_info').remove();;
						$('#list_gangguan_paginate').remove();
						$("#list_gangguan_length").remove();
						$("#list_gangguan_filter").remove();
						$('#list_gangguan .no_urut').removeClass("sorting_asc");
					  }
				});
				table1.on('click', '#btn-add-gangguan', function (e) {
					var target = $(this).attr('href');
					//alert(target);
					var url_form = target+'/form';
					//alert(target);
					$('#largeModalChild .modal-title').html("Tambah Gangguan");
					$('#largeModalChild .modal-body').load(url_form, function() {
						 $('#largeModalChild').modal('show');
					});
					$('#largeModalChild .modal-footer').html('');
					$('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\"  id=\"tombol_batal_child\" onclick=\"tutup('largeModalChild');\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/add','form_input_gangguan','list_gangguan','');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
					e.preventDefault();

				} );
				table1.on('click', '.btn-edit-gangguan', function (e) {
					var target = $(this).attr('href');
					var gangguan_id = $(this).attr('role');

					$('#largeModalChild .modal-title').html("Edit Gangguan");
					$('#largeModalChild .modal-body').load(target+'/form?gangguan_id='+gangguan_id, function() {
						 $('#largeModalChild').modal('show');
					});
					$('#largeModalChild .modal-footer').html('');
					$('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\"  id=\"tombol_batal_child\" onclick=\"tutup('largeModalChild');\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/edit','form_input_gangguan','list_gangguan','"+gangguan_id+"');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
					e.preventDefault();

				} );

				table1.on('click', '.btn-del-gangguan', function (e) {
					var tr_id = $(this).attr('role');
					 var url_del = $(this).attr('href')+'/del';

					$('#largeModalChild .modal-title').html("Konfirmasi Hapus Data");
					$('#largeModalChild .modal-body').html("<h4>Yakin data akan dihapus?</h4>");//.css("text-align","center");
					$('#largeModalChild .modal-footer').html('');
					$('#largeModalChild .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" onclick=\"tutup('largeModalChild');\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_hapus\" onclick=\"hapus('"+url_del+"','"+tr_id+"','list_gangguan');\" ><i class=\"fa fa-trash-o\"></i>&nbsp;Hapus</button>");
					e.preventDefault();
				});


			});

			</script>

			<?php
			}

		?>


        <script>

		function simpan(url_save,form_id,table_id,child_id){

			$(".input").removeClass("error");
			var serialis=$("#"+form_id).serialize();
			if(child_id!==''){
				if(table_id=="list_ritase"){
					serialis=serialis+'&ritase_id='+child_id;
				}
				if(table_id=="list_gangguan"){
					serialis=serialis+'&gangguan_id='+child_id;
				}
			}
			//alert(serialis);
			$.ajax({
				type:"POST",
				url: url_save,
				data: serialis+"&child=1",
				success: function(data, status) {
					//alert(data);
					var obj2 = JSON.parse(data);
					if (obj2.success==true){
						$('#largeModalChild .modal-body').html("<h5>"+obj2.message+"</h5>");
						$('#largeModalChild .modal-footer #tombol_batal_child').html("<i class=\"fa fa-times\"></i>&nbsp;Tutup");
						$('#largeModalChild .modal-footer #simpan_data').remove();
						if (  $.fn.DataTable.isDataTable( '#'+table_id ) ) {
							$('#'+table_id).DataTable().columns().search().draw();
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
		function hapus(url_hapus,id,table_id)
		{

			$.ajax({
					type:"POST",
					url: url_hapus,
					data: 'child_id='+id,
					success: function(data, status) {
						//alert(data);
						var obj2 = JSON.parse(data);
						if (obj2.success==true){

							$('#largeModalChild .modal-body').html("<h5>"+obj2.message+"</h5>");
							$('#largeModalChild .modal-footer #tombol_batal').html("<i class=\"fa fa-times\"></i>&nbsp;Tutup");
							$('#largeModalChild .modal-footer #tombol_hapus').remove();
							if (  $.fn.DataTable.isDataTable( '#'+table_id ) ) {
								$('#'+table_id).DataTable().columns().search().draw();
							}
						}else{
							$('#largeModalChild .modal-body').html("<h5>"+obj2.message+"</h5>");
							if( obj2.form_error !== undefined)
							{
								errorForm(obj2.form_error);
							}
						}

					}
				});

		}
		function tutup(id_modal){

			$('#'+id_modal).modal('hide');
		}
		</script>
        <?php

		if($step==5 ){
		?>
        <script>
			$(document).ready(function () {
				$("#completed_time").datetimepicker({
					 format: 'dd/mm/yyyy hh:ii',
					 minuteStep: 1,
					 autoclose: true
				});



			});

			</script>
            <div class="row-form">
                    <span class="label" >Barge/Tongkang </span> :
                    <?php echo $detail->barge_name;?>

                </div>
                <div class="row-form">
                    <span class="label" title="Rencana Muat">Pre Stowage Plan (PSP)</span> :

                    <?php echo $detail->pre_stowage_plan;?>

                </div>
                 <div class="row-form">
                     <span class="label" >Jetty</span> :
                   <?php echo $detail->jetty_name."/".$detail->gate_name;?>
               </div>

                 <div class="row-form">
                    <span class="label" title="Waktu Sandar">Berth Time </span> :
                   <?php echo $detail->berth_time;?>

                </div>
                 <div class="row-form">
                    <span class="label">Commenced Time </span> :
                   <?php echo $detail->commenced_time;?>

                </div>
       	<div class="row-form">
            <span class="label">Completed Time <small class="wajib">*</small></span>

            <input type="text" class="input" name="completed_time" id="completed_time" placeholder="Completed"  size="15" value="<?php echo $detail->completed_time2;?>"/>

        </div>
        <div class="row-form">
            <span class="label">Jumlah Dump Truck <small class="wajib">*</small></span>

            <input type="text" class="input" name="jumlah_truck" id="jumlah_truck"  size="2" value="<?php echo $detail->jumlah_truck;?>"/>

        </div>
         <div class="row-form">
            <span class="label">Final Draugh Survey <small class="wajib">*</small></span>

            <input type="text" class="input" name="final_draugh_survey" id="final_draugh_survey"  size="10" value="<?php echo number_format($detail->final_draugh_survey,2,",",".");?>"/> &nbsp; MT

        </div>
         <div class="row-form">
            <span class="label">Urutan Pengiriman <small class="wajib">*</small></span>

            <input type="text" class="input" name="urutan_pengiriman" id="urutan_pengiriman"  size="2" value="<?php echo $urutan_pengiriman;?>"/>

        </div>

        <?php
		}//end of step=5

		?>
        </div>
        </form>

        <?php
		if($step==4){
		?>

        <form id="uploadimage" action="<?php echo $url_action; ?>/save" method="post" enctype="multipart/form-data" target="media-upload">
         <div class="responsive-form" >
         <input type="hidden" class="input" name="current_step" id="current_step2" size="2" value="<?php echo $step;?>"/>
         <input type="hidden" class="input" name="nik_lama" id="nik_lama" size="15" value="<?php echo $nik_lama;?>"/>
         <input type="hidden" class="input" name="current_child" id="current_child" size="2" value=""/>
         <script>
		$(document).ready(function () {

			$("#uploadimage").on('submit',(function() {

				//e.preventDefault();
				var current_step = $('#current_step2').val();
				//$("#message").empty();
				//alert(current_step);
				//alert('<?php echo $url_action; ?>/save');
				$('#rsLoaderUpload').show();
				$.ajax({
					url: '<?php echo $url_action; ?>/save', // Url to which the request is send
					type: "POST",             // Type of request to be send, called as method
					data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
					contentType: false,       // The content type used when sending data to the server.
					cache: false,             // To unable request pages to be cached
					processData:false,        // To send DOMDocument or non processed data file it is set to false
					success: function(data)   // A function to be called if request succeeds
					{
						//alert(data);

						$("#media-testing").html(data);
						$('#rsLoaderUpload').hide();
						var obj2 = JSON.parse(data);
						$("#media-testing").html(obj2.message);
						if (obj2.success==true){
						 // var progress_line = $('.f1-steps').find('.f1-progress-line');
						  //bar_progress(progress_line, 'right');
						//  $('#btn-next').hide();
						  //$('#btn-selesai').show();
						  $("#err_file_error").html(obj2.message);
						}else{
							alert(obj2.message);
							if( obj2.form_error !== undefined)
							{
								errorForm(obj2.form_error);
							}
						}
					}
				});
			}));
			$(".file_foto").change(function(e) {
				var child_id= $(this).attr('role');
				$("#current_child").val(child_id);
				//$("#message").empty(); // To remove the previous error message

				var file = this.files[0];
				var imagefile = file.type;
				var match= ["image/jpeg","image/png","image/jpg"];
				if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
				{
					$('#previewing'+child_id).attr('src','<?php echo $url_nofoto;?>');
					$("#message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
					return false;
				}
				else
				{

					var reader = new FileReader();
					reader.onload = imageIsLoaded;
					reader.readAsDataURL(this.files[0]);
					var uk=(file.size/1000);
					$('#err_file_error'+child_id).html(accounting.formatNumber(uk, 2,".",",")+'KB');
				}
			});
			function imageIsLoaded(e) {
				console.log(e);
				child_id=$("#current_child").val();
				$("#file_dokumen"+child_id).css("color","green");
				$('#image_preview'+child_id).css("display", "block");

				$('#previewing'+child_id).attr('src', e.target.result);
				//$('#previewing').attr('width', '250px');
				//$('#previewing').attr('height', '230px');

			};
		});
		</script>
      	<div class="row" style="margin-left:3px;">
         <div class="col-xs-5" style="border:1px solid #999;margin:2px 2px 2px 2px;padding-top:3px;">
          <div class="row-form" style="">
               <strong>BA Commenced Barging</strong>
              <?php  
			  $list_file=array();
			  foreach($detail->files as $key=>$value){
				  $list_file[$key]=(array)$value;
			  }
			 
			  
			 $key = array_search('BA Commenced Barging', array_column($list_file, 'nama_berkas')); // $key = 2; 
			 ?>
        <input type="hidden" class="input" name="berita_acara_for[0]"  size="2" value="BA Commenced Barging"/>
            </div>
            <div class="row-form" style="text-align:center;">

                <div id="image_preview_commenced"><img id="previewing_commenced" src="<?php echo trim($key)<>""?$detail->files[$key]->url_file:"";?>" height="250"  /></div>
            </div>

            <div class="row-form" >

            <input type="hidden" class="input" name="pilih_category[0]"  size="2" value="shipment"/>
            <input type="hidden" class="input" name="id[0]"  size="4" value="<?php echo $detail->id;?>"/>
            <input type="file" name="file_dokumen[0]" id="file_foto_commenced"  class="input file_foto" size="35"  role="_parent" >



            </div>
            <div class="row-form" >
            <input type="hidden" class="input" name="pilih_nama_berkas[0]"  size="25" placeholder="Nama Berkas" value="commenced_barging"/>
            </div>
            <div class="row-form">
            <i>Ukuran : 30 - 500KB</i>

            </div>
            <div class="row-form">
            <button type="submit"  class="btn btn-primary btn-xs" value="upload" name="upload" ><i class="fa fa-upload"></i> Upload File</button>
            <img src="<?php echo $theme_path;?>images/loading50.gif" id="rsLoaderUpload_commenced" style="display:none; width:16px;" />
            <span id="err_file_error_commenced"></span>
            </div>

         </div>
         
          <div class="col-xs-5" style="border:1px solid #999;margin:2px 2px 2px 2px;padding-top:3px;">
          <div class="row-form" style="">
               <strong>BA Joint Cargo</strong>
              <?php $key = array_search('BA Joint Cargo', array_column($list_file, 'nama_berkas'));?>
        <input type="hidden" class="input" name="berita_acara_for[1]"  size="2" value="BA Joint Cargo"/>
            </div>
            <div class="row-form" style="text-align:center;">
  
                <div id="image_preview_joint"><img id="previewing_joint" src="<?php echo  trim($key)<>""?$detail->files[$key]->url_file:"";?>" height="250"  /> <span id="err_file_error_joint"></span></div>
            </div>

            <div class="row-form" >

            <input type="hidden" class="input" name="pilih_category[1]"  size="2" value="shipment"/>
            <input type="hidden" class="input" name="id[1]"  size="4" value="<?php echo $detail->id;?>"/>
            <input type="file" name="file_dokumen[1]" id="file_foto_joint"  class="input file_foto" size="35"  role="_joint" >



            </div>
            <div class="row-form" >
            <input type="hidden" class="input" name="pilih_nama_berkas[1]"  size="25" placeholder="Nama Berkas" value="joint_cargo"/>
            </div>
            <div class="row-form">
            <i>Ukuran : 30 - 500KB</i>

            </div>
            <div class="row-form">
            <button type="submit"  class="btn btn-primary btn-xs" value="upload" name="upload" ><i class="fa fa-upload"></i> Upload File</button>
            <img src="<?php echo $theme_path;?>images/loading50.gif" id="rsLoaderUpload_joint" style="display:none; width:16px;" />
            <span id="err_file_error_joint"></span>
            </div>

         </div>
         </div>
      <div class="row" style="margin-left:3px;">
        
         <div class="col-xs-5" style="border:1px solid #999;margin:2px 2px 2px 2px;padding-top:3px;">
          <div class="row-form" style="">
               <strong>BA Completed Cargo</strong>
               <?php $key = array_search('BA Completed Cargo', array_column($list_file, 'nama_berkas'));?>
        <input type="hidden" class="input" name="berita_acara_for[2]"  size="2" value="BA Completed Cargo"/>
            </div>
            <div class="row-form" style="text-align:center;">
  
                <div id="image_preview_joint"><img id="previewing_completed" src="<?php echo  trim($key)<>""?$detail->files[$key]->url_file:"";?>" height="250"  /> <span id="err_file_error_completed"></span></div>
            </div>

            <div class="row-form" >

            <input type="hidden" class="input" name="pilih_category[2]"  size="2" value="shipment"/>
            <input type="hidden" class="input" name="id[2]"  size="4" value="<?php echo $detail->id;?>"/>
            <input type="file" name="file_dokumen[2]" id="file_foto_completed"  class="input file_foto" size="35"  role="_completed" >



            </div>
            <div class="row-form" >
            <input type="hidden" class="input" name="pilih_nama_berkas[2]"  size="25" placeholder="Nama Berkas" value="completed_cargo"/>
            </div>
            <div class="row-form">
            <i>Ukuran : 30 - 500KB</i>

            </div>
           <div class="row-form">
            <button type="submit"  class="btn btn-primary btn-xs" value="upload" name="upload" ><i class="fa fa-upload"></i> Upload File</button>
            <img src="<?php echo $theme_path;?>images/loading50.gif" id="rsLoaderUpload_completed" style="display:none; width:16px;" />
            <span id="err_file_error_completed"></span>
            </div>

         </div>
        
        
          <div class="col-xs-5" style="border:1px solid #999;margin:2px 2px 2px 2px;padding-top:3px;">
          <div class="row-form" style="">
               <strong>BA Final Draugh Survey</strong>
                <?php $key = array_search('BA Final Draugh Survey', array_column($list_file, 'nama_berkas')); ?>
        <input type="hidden" class="input" name="berita_acara_for[3]"  size="2" value="BA Final Draugh Survey"/>
            </div>
            <div class="row-form" style="text-align:center;">

                <div id="image_preview_parent"><img id="previewing_parent" src="<?php echo  trim($key)<>""?$detail->files[$key]->url_file:"";?>" height="250"  /></div>
            </div>

            <div class="row-form" >

            <input type="hidden" class="input" name="pilih_category[3]"  size="2" value="shipment"/>
            <input type="hidden" class="input" name="id[3]"  size="4" value="<?php echo $detail->id;?>"/>
            <input type="file" name="file_dokumen[3]" id="file_foto_parent"  class="input file_foto" size="35"  role="_parent" >



            </div>
            <div class="row-form" >
            <input type="hidden" class="input" name="pilih_nama_berkas[3]"  size="25" placeholder="Nama Berkas" value="final_draugh_survey"/>
            </div>
            <div class="row-form">
            <i>Ukuran : 30 - 500KB</i>

            </div>
            <div class="row-form">
            <button type="submit"  class="btn btn-primary btn-xs" value="upload" name="upload" ><i class="fa fa-upload"></i> Upload File</button>
            <img src="<?php echo $theme_path;?>images/loading50.gif" id="rsLoaderUpload_parent" style="display:none; width:16px;" />
            <span id="err_file_error_parent"></span>
            </div>

         </div>
         
        <!-- 
        <?php
		//echo "<pre>";print_r($detail);echo "</pre>";
		if(!empty($detail->detail)){
			while($child=current($detail->detail)){
				if($child->intermediate_draugh_survey>0){
					$url_file=$child->file->url_file;
					$nama_berkas=$child->file->nama_berkas;
			?>
			  <div class="col-xs-5" style="border:1px solid #999;margin:2px 2px 2px 2px;padding-top:3px;">
			   <div class="row-form"  style="text-align:center;">
					<strong>Intermediate Draugh Survey<br /><?php echo $child->name;?> </strong><br />
                    <input type="hidden" class="input" name="draugh_survey_for[]"  size="2" value="Intermediate DS  <?php echo $child->alias;?>"/>

				</div>
				<div class="row-form" style="text-align:center;">

					<div id="image_preview<?php echo $child->id;?>"><img id="previewing<?php echo $child->id;?>" src="<?php echo $url_file;?>" height="250" /></div>
				</div>

				<div class="row-form">

				<input type="hidden" class="input" name="pilih_category[]"  size="2" value="shipment_detail"/>
				<input type="hidden" class="input" name="id[]"  size="4" value="<?php echo $child->id;?>"/>
				<input type="file" name="file_dokumen[]" id="file_dokumen<?php echo $child->id;?>"  class="input file_foto" size="35"  role="<?php echo $child->id;?>" >



				</div>
                 <div class="row-form" >
            <input type="text" class="input" name="pilih_nama_berkas[]"  size="25" placeholder="Nama Berkas" value="<?php echo $nama_berkas;?>"/>
            </div>
				<div class="row-form">
				<i>Ukuran : 30 - 500KB</i>

				</div>
				<div class="row-form">
				<button type="submit"  class="btn btn-primary btn-xs" value="upload" name="upload" ><i class="fa fa-upload"></i> Upload File</button>
				<img src="<?php echo $theme_path;?>images/loading50.gif" id="rsLoaderUpload<?php echo $child->id;?>" style="display:none; width:16px;" />
				<span id="err_file_error<?php echo $child->id;?>"></span>
				</div>

			 </div>
			 <?php
				}//enf of if intermediate_draugh_survey>0
			 next($detail->detail);
			}
		}//enf of empty
		 ?>
		-->
       </div>
    </div>
     <div id="media-testing"></div>
    </form>
	<?php
    }
    ?>
