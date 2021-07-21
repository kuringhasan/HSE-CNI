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
                    <th  id="row_no" style="width:30px;">No</th>
                    <th  style="width:130px;">Kode Kabupaten</th>
                    <th  style="width:130px;">Jenis</th>
                    <th  style="width:130px;">Nama</th>
                    <th  style="width:130px;">Provinsi</th>

                    <th  style="width:55px;"><?php echo $TombolTambah;?></th>
                    </tr>
               
                </thead>
                <thead>
                    <tr>
                    <th  id="row_no" style="width:30px;"></th>
                    <th ><input type="text" data-column="2"  class="form-control search-by-code" style="width:100%" placeholder="Kode"></th>
                    <th ><input type="text" data-column="4"  class="form-control search-by-jenis" style="width:100%" placeholder="Jenis"></th>
                    <th ><input type="text" data-column="3"  class="form-control search-by-name" style="width:100%" placeholder="Nama"></th>
                    <th >
                    <select name="search-by-provinsi" data-column="1" class="form-control input-xm search-by-provinsi" id="search-by-provinsi" style="width:100%">
                        <?php
                        echo '<option value="">-- Provinsi --</option>';
                        $List=$list_provinsi;
                        while($data = each($List)) {
                        ?>
                    <option value="<?php echo $data['key'];?>" ><?php echo $data['value'];?></option>
                        <?php
                        }
                        ?>
                    </select>
                    </th>
                    <th  style="width:55px;"></th>
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
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>
<!-- DataTables -->

<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> 

<script>
var $j = jQuery.noConflict();
$j(document).ready(function() {
    // alert("<?php echo $url_listdata;?>");
	 
    // $j('#list_data').removeClass("sorting_asc");
	var table=$j('#list_data').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo $url_listdata;?>",
                "type": "POST"
            },
            "columnDefs": [
                { "orderable": false, "targets": [0] },
                {"targets": [0],"className": "text-center"}
                ],
            "columns": [
                { "data": "No",'sortable': false,'className':'col-number' },
                { "data": "code" },
                { "data": "jenis" },
                { "data": "name" },
                { "data": "provinsi_name" },
                { "data": "Tombol",'sortable': false,'className':'btn-action' }
            ],
            'fnCreatedRow': function (nRow, aData, iDataIndex) {
                $j(nRow).attr('id', 'tr_' + aData.Id); // or whatever you choose to set as the id
            },
            "initComplete": function(settings, json) {
                console.log(json);
                $j('#row_no').removeClass("sorting_asc");
            }
    });
    $j("#list_data_filter").css("display","none");
    $j('.search-by-provinsi').on( 'change', function () {   // for text boxes
        var i =$j(this).attr('data-column');  // getting column index
        var v =$j(this).val();  // getting search input value
        table.columns(i).search(v).draw();
    } );	
    $j('.search-by-code').on( 'keyup', function () {   // for text boxes
        var i =$j(this).attr('data-column');  // getting column index
        var v =$j(this).val();  // getting search input value
        table.columns(i).search(v).draw();
    } );	
    $j('.search-by-name').on( 'keyup', function () {   // for text boxes
        var i =$j(this).attr('data-column');  // getting column index
        var v =$j(this).val();  // getting search input value
        table.columns(i).search(v).draw();
    } );
    $j('.search-by-jenis').on( 'keyup', function () {   // for text boxes
        var i =$j(this).attr('data-column');  // getting column index
        var v =$j(this).val();  // getting search input value
        table.columns(i).search(v).draw();
    } );
    

    $j('#btn-tambah-data').on( 'click', function () {   // for text boxes
        var target = $j(this).attr('href');
        $j('#largeModal .modal-title').html("Form Input Kabupaten");
        $j('#largeModal .modal-body').load(target, function() {
                $j('#largeModal').modal('show');
        });
        $j('#largeModal .modal-footer').html('');
        $j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"simpan_data\"  onclick=\"simpan('"+target+"/save');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
        
    } );	
    
    table.on('click', '.btn-edit-data', function (e) {
        var target = $j(this).attr('href');
        
        $j('#largeModal .modal-title').html("Form Input Kabupaten");
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
function simpan(url_save){
	//alert(url_save);
		$j(".input").removeClass("error");
		$j.ajax({
			type:"POST",
			url: url_save,
			data: $j("#form_input_data").serialize(),
			success: function(data, status) {
			
				$j("#media-test").html(data);
				var obj2 = JSON.parse(data);
				if (obj2.success==true){
					if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
						$j('#list_data').DataTable().columns().search().draw();
					}
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
// function comboAjax(url_cmb,parentkode,idTarget,nilai,other_parameters,idloader, idframe)
// {
//     //alert(url_cmb+'?nilai='+nilai+'&parentkode='+parentkode+'&'+other_parameters);
//     $j("#"+idTarget).hide();
//     $j("#"+idloader).show();
//     $j.ajax({
//             type:'POST',
//             dataType:'html',
//             url:url_cmb,
//             data:'nilai='+nilai+'&parentkode='+parentkode+'&'+other_parameters,
//             success:function(msg){
//                 //alert(msg);
//                 var obj=JSON.parse(msg);
//                 $j("#"+idloader).hide();
//                 if (obj.kosong==false)
//                 {	
//                     $j("#"+idTarget).fadeIn();
//                     $j("#"+idTarget).empty().append(obj.html);
//                 }else{
//                     // $("#"+idframe).fadeOut();
//                     $j("#"+idTarget).fadeIn();
//                     $j("#"+idTarget).empty().append(obj.html);
//                 }
                
//             }///akhisr sukses
//         }); //akhir $.ajax	
// }
</script>