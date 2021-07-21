<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
 <style>
#list_populasi tr td{
  padding-top:2px;
  padding-bottom:2px;
  font-size:11px;
  vertical-align:middle;
}
#list_populasi tr th{
 vertical-align:middle;
 font-size:11px;
 background-color:#CCC;
}
</style>
<div class="responsive-form" >
	<div class="row-form">
	 <span class="label" >Nama </span> : <?php echo $profil->NAMA;?>
	</div>
   
   <div class="row-form">
		 <span class="label" >NIK <small class="wajib"></small></span> : <?php echo $profil->NIK;?>
		 
	</div>
</div>
    <table  id="list_populasi"  class="table table-bordered table-hover dataTable" style="margin-top:4px; width:100%;" >
        <thead>
        <tr >
            <th class="no_urut" style="width:25px;">No</th>
            <th style="width:105px;">No Eartag</th>
            <th style="width:125px;">Nama</th>
            <th style="width:70px;">Tipe</th> 
            <th style="width:60px;">Gender</th>
         
        </tr>
        </thead>
       
        </table>  
        
		<script> 
		
		//var $m = jQuery.noConflict();
		$j(document).ready(function() {
			
			var table=$j('#list_populasi').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax": {
					"url": "<?php echo $url_listdata_populasi;?>",
					"type": "POST"
				},
				"columnDefs": [
					{ "orderable": false, "targets": [0,1,2,3,4] },
					{"targets": [0,1,2,3,4],"className": "text-center"}
				 ],
				"columns": [
					{ "data": "No",'sortable': false,'className':'col-number' },
					{ "data": "NoEartag" },
					{ "data": "Nama" },
					{ "data": "Tipe" },
					{ "data": "GenderNama" }
				],
				'fnCreatedRow': function (nRow, aData, iDataIndex) {
					$j(nRow).attr('id', 'tr_' + aData.Kode); // or whatever you choose to set as the id
				},
				"initComplete": function(settings, json) {
					//console.log(json);
					//$j('#list_data').removeClass("sorting_asc");
					$j('#list_populasi tr th').removeClass("sorting_asc");
					$j('#list_populasi_info').remove();;
					$j('#list_populasi_paginate').remove();
					$j("#list_populasi_length").remove();
					$j("#list_populasi_filter").remove();
					$j('#list_populasi .no_urut').removeClass("sorting_asc");
				  }
			});
			
			
			
		});
		
		</script>
        
        <?php echo core::error("Selanjutnya untuk melengkapi biodata sekaligus memverifikasi data menjadi tanggung jawab bagian keanggotaan","center");?>