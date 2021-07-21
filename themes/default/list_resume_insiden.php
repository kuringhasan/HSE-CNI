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
<style class="cp-pen-styles">
.pagination{
	margin:0 0 0 0;
}

@media screen and (max-width: 767px) {
   #list_data_wrapper .row .col-sm-3 {
	  float:left;
  }
   #list_data_wrapper .row .col-sm-9 div{
	  float:left;
  }
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

  #header-search  th {
    display: table-row;
	
	
  }
  #header-search th:before {
    content: attr(data-label);
    display: table-cell;
    font-weight: bold;
    padding: 0.2em 0.6em 0.8em 0;
    text-align: right;
  }
  #header-search th:before .form-control {
   	width:100%;
  }
  #header-search th:last-child:after {
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



@media screen and (min-width: 320px) {
 


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
<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="largeModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Form</h4>
        </div>
        <div class="modal-body">
           
        </div>
      <div class="modal-footer">
    
       
      </div>
    </div>
  </div>
</div>
 <div class="row">
        <div class="col-xs-12">
         
          <div class="box box-solid">
            <div class="box-header">
              <!-- <h3 class="box-title">Data Kota & Kabupaten
            
              </h3>-->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
           
             
              <table id="list_data" class="table table-bordered table-hover dataTable"  style="width:100%">
                <thead class="header-data">
                <tr>
                  <th style="width:35px;" id="row_no" class="row_no">No</th>
                  <th style="width:150px;">Kontraktor</th>
				  <th style="width:150px;">No.Register</th>
                  <th  style="width:150px;">Tanggal</th>
                  <th style="width:200px;">Keterangan</th>
                  <th style="width:200px;">Status</th>
                  <th style="width:200px;">Tanggal Awal Progress</th>
                  <th style="width:200px;">Tanggal Selesai Progress</th>
                  <th style="width:85px;"></th>
                </tr>
                </thead>
                <thead class="header-search" >
                    <tr>
                    <th class="no-form"></th>
                    <th class="no-form"></th>
					<th class="no-form"></th>
                    <th class="no-form"></th>
                    <th  style="text-align:center" class="no-form"></th>
                    <th >
                    </th><th  >
                    </th><th  >
                    </th>
					<th  style="text-align:center" class="no-form">
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

<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>
<!-- DataTables -->

<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> 

<script>
var $j = jQuery.noConflict();
  $j(document).ready(function() {
	  
	var table=$j('#list_data').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax": {
						"url": "<?php echo $url_listdata;?>",
						"type": "POST"
					},
					"columnDefs": [
						{ "orderable": false, "targets": [0,6] },
						//{"targets": [0,3,4,5],"className": "text-center"}
					 ],
					"columns": [
						{ "data": "No",'sortable': false,'className':'col-number' },
						{ "data": "Company" },
						{ "data": "NoRegister" },
						{ "data": "Checkin" },
						{ "data": "Keterangan" },
						{ "data": "Status" },
						{ "data": "TglAwalprogress" },
						{ "data": "TglSelesaiprogress" },
						{ "data": "Aksi",'className':'btn-action' }
						// { data: "Aksi",
						// 	render: function(data , type , row) { if(row.Status == "done"){  return row.AksiDone  }else{ return data }  },
						// 	sortable: false 
						// }
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						$j(nRow).attr('id', 'tr_' + aData.Kode); // or whatever you choose to set as the id
						$j('.row_no').removeClass("sorting_asc");
						//$j(nRow).attr('class', 'details-control'); 
					   /*  $j( nRow ).find('td:eq(0)').attr('data-label',"No");
						$j( nRow ).find('td:eq(1)').attr('data-label',"Tanggal");
						$j( nRow ).find('td:eq(2)').attr('data-label',"Masuk");
						$j( nRow ).find('td:eq(3)').attr('data-label',"Pulang");
						$j( nRow ).find('td:eq(4)').attr('data-label',"Kategori");
						$j( nRow ).find('td:eq(5)').attr('data-label',"Aksi"); */
					},
					"initComplete": function(settings, json) {
						$j('.row_no').removeClass("sorting_asc");
						$j( "#list_data_wrapper .row" ).find( "div" ).eq( 0 ).attr('class', 'col-sm-3');
						$j( "#list_data_wrapper .row" ).find( "div" ).eq( 2 ).attr('class', 'col-sm-9');
						
						var element = document.getElementById('list_data_filter');
						var child = document.createElement('div');
						child.style.float = "right";
						child.innerHTML = 
						'<select name="cr_company" data-column="2" class="form-control input-xm cr_company" id="cr_company" onChange="pilihcompany(this.value,2,\'cr_company\');" title="Pilih Kontraktor">\n'+
						 '<option value="">-- Kontraktor ---</option>\n'+
						<?php
						 $comp		=isset($_POST['cr_company'])?$_POST['cr_company']:"";
						$List=$list_contractor;
						while($data = each($List)) {
								?>
									'<option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$comp?"selected":""; ?> ><?php echo $data['value'];?></option>\n'+
							<?php
								}
							?>
						'</select>\n'+
						'<select name="cr_bulan" data-column="4" class="form-control input-xm cr_bulan" id="cr_bulan" onChange="pilih(this.value,4,\'cr_bulan\');" title="Pilih Bulan">\n'+
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
						'<input type="text" name="cr_tahun" class="form-control input-xm cr_tahun" id="cr_tahun" onkeyup="pilih(this.value,5,\'cr_tahun\');" placeholder="tahun" size="4">\n';
						
						var elementParent = element.parentNode;
						elementParent.insertBefore(child, element.nextSibling);
					  }
				});
	   
		$j("#list_data_filter").css("display","none");

		$j('.search-by-name').on( 'keyup click', function () {   // for text boxes
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
		} );	
		$j('.search-by-departemen').on( 'change', function () {   // for text boxes
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
		} );
		$j('.search-by-kontraktor').on( 'change', function () {   // for text boxes
			var i =$j(this).attr('data-column');  // getting column index
			var v =$j(this).val();  // getting search input value
			table.columns(i).search(v).draw();
		} );	
		

		table.on('click', '.btn-detail-data', function (e) {
				var target = $j(this).attr('href');
				
				$j('#largeModal .modal-title').html("Detail Resume Insiden");
				$j('#largeModal .modal-body').load(target, function() {
					$('#largeModal').modal('show');
				});
				$j('#largeModal .modal-footer').html('');
					$j('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Tutup</button>"); 
					

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
function pilih(nilai,index_data,el){
	 var lanjut=true;
	if(index_data==4){
		var thn = $j("#cr_tahun").val();
		
		if(thn==""){
			lanjut=false;
		}
	}
	
	if(lanjut==true){
		if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
			$j('#list_data').DataTable().columns(index_data).search(nilai).draw();
		}
	}
	//$j.fn.dataTable.columns(index_data).search(nilai).draw();
 }

 function pilihcompany(nilai,index_data,el){
	 var lanjut=true;
	if(index_data==2){
		var thn = $j("#cr_company").val();
		
		if(thn==""){
			lanjut=false;
		}
	}
	
	if(lanjut==true){
		if (  $j.fn.DataTable.isDataTable( '#list_data' ) ) {
			$j('#list_data').DataTable().columns(index_data).search(nilai).draw();
		}
	}
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


