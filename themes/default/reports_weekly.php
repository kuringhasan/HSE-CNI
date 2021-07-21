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
  .header-search  tr:first-of-type {
    border-top: 1px solid #ccc;
  }

  .header-search  th {
    display: table-row;
	
	
  }
  .header-search th:before {
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
    border-bottom: 1px solid #ccc;
  }
  
  
  
  #list_data tr,#list_bulan tr{
    display: block;
    position: relative;
    padding: 1.2em 0;
  }
  #list_data tr:first-of-type,#list_bulan tr:first-of-type {
    border-top: 1px solid #ccc;
  }

  #list_data td, #list_bulan td  {
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
	

}



@media screen and (min-width: 320px) {
 


  #list_data td, #list_data th,#list_bulan td, #list_data th  {
    padding: 0.4em 0.6em;
    vertical-align: top;
   /* border: 1px solid #ccc;*/
  }

  #list_data th, #list_bulan th  {
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
        Weekly Report Production
          </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
       
         
         <table id="list_data" class="table table-bordered table-hover dataTable display nowrap"  style="width:100%">
            <thead class="header-data">
            <tr>
              <th style="width:25px;" >No</th>
              <th style="width:170px;">Week</th>
              <th style="width:60px;">Tahun</th>
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
            
      
     </div>
        <!-- /.box-body -->
        
        
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
<div class="row">
    <div class="col-xs-12">
     
      <div class="box">
        <div class="box-header text-center">
          <h3 class="box-title" id="listdata-title" >
        	Monthly Report Production
          </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
       
         
         <table id="list_bulan" class="table table-bordered table-hover dataTable"  style="width:100%">
            <thead class="header-data">
            <tr>
              <th style="width:25px;" >No</th>
              <th style="width:170px;">Bulan</th>
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
            
      
     </div>
        <!-- /.box-body -->
        
        
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

<div class="row">
<style>
.select2-selection__choice{
	color:#09F;
}
</style>
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
                <select name="tpk[]" class="form-control input select2" multiple="multiple" data-placeholder="Pilih TPK"
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
                <select name="staf_petugas[]" class="form-control input select-petugas" multiple="multiple" data-placeholder="Pilih Petugas"
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

    
      
      <div id="media-test"></div>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>
<!-- DataTables -->

<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> 
		
<script>
var $j = jQuery.noConflict();
  $j(document).ready(function() {
	  var table=$j('#list_data').DataTable({
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
						{ "data": "No",'sortable': false},
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
						{ "data": "plan" },
						{ "data": "total_plan" }
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						console.log(aData);
						$j(nRow).attr('id', 'tr_' + aData.ID); // or whatever you choose to set as the id
						$j( nRow ).find('td:eq(0)').attr('data-label',"No");
						$j( nRow ).find('td:eq(1)').attr('data-label',"Week");
						$j( nRow ).find('td:eq(2)').attr('data-label',"Tahun");
						$j( nRow ).find('td:eq(3)').attr('data-label',"HJS");
						$j( nRow ).find('td:eq(4)').attr('data-label',"LCP");
						$j( nRow ).find('td:eq(5)').attr('data-label',"PL");
						$j( nRow ).find('td:eq(6)').attr('data-label',"BKM");
						$j( nRow ).find('td:eq(7)').attr('data-label',"Cum HJS");
						$j( nRow ).find('td:eq(8)').attr('data-label',"Cum LCP");
						$j( nRow ).find('td:eq(9)').attr('data-label',"Cum PL");
						$j( nRow ).find('td:eq(10)').attr('data-label',"Cum BKM");
						$j( nRow ).find('td:eq(11)').attr('data-label',"Cum Total");
						$j( nRow ).find('td:eq(12)').attr('data-label',"Plan");
						$j( nRow ).find('td:eq(13)').attr('data-label',"Total Plan");
					},
					"initComplete": function(settings, json) {
						console.log(json);
						$j('#row_no').removeClass("sorting_asc");
						$j( "#list_data_wrapper .row" ).find( "div" ).eq( 0 ).attr('class', 'col-sm-3');
						$j( "#list_data_wrapper .row" ).find( "div" ).eq( 2 ).attr('class', 'col-sm-9');
						
						var element = document.getElementById('list_data_filter');
						var child = document.createElement('div');
						child.style.float = "right";
						child.innerHTML = '<input type="text" name="cr_tahun" class="form-control input-xm cr_tahun" id="cr_tahun" onkeyup="pilih(this.value,10,\'cr_tahun\');" placeholder="tahun" size="4">\n';
						
						var elementParent = element.parentNode;
						elementParent.insertBefore(child, element.nextSibling);
						$j("#cr_tahun").keyup(function(){
							var dt=$j(this).val()
							
							if (dt.length>3){
								
								$j("#cr_tahun2").val(dt);
								$j('#list_data').DataTable().columns(10).search(dt).draw();
								$j('#list_bulan').DataTable().columns(10).search(dt).draw();
							}
						}); 
					
					  
					}
				});
				$j("#list_data_filter").css("display","none");
				//$j("#list_data_scrollBody th").removeAttr('class');
				var table2=$j('#list_bulan').DataTable({
		  			"processing": true,
					"serverSide": true,
					
					"ajax": {
						"url": "<?php echo $url_listdata;?>/bulan",
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
						{ "data": "No",'sortable': false},
						{ "data": "bulan" },
						{ "data": "qty_hjs" },
						{ "data": "qty_lcp" },
						{ "data": "qty_pl" },
						{ "data": "qty_bkm" },
						{ "data": "total_hjs" },
						{ "data": "total_lcp" },
						{ "data": "total_pl" },
						{ "data": "total_bkm" },
						{ "data": "plan" },
						{ "data": "total_plan" }
					],
					'fnCreatedRow': function (nRow, aData, iDataIndex) {
						console.log(aData);
						$j(nRow).attr('id', 'tr_' + aData.ID); // or whatever you choose to set as the id
						$j( nRow ).find('td:eq(0)').attr('data-label',"No");
						$j( nRow ).find('td:eq(1)').attr('data-label',"Bulan");
						$j( nRow ).find('td:eq(2)').attr('data-label',"HJS");
						$j( nRow ).find('td:eq(3)').attr('data-label',"LCP");
						$j( nRow ).find('td:eq(4)').attr('data-label',"PL");
						$j( nRow ).find('td:eq(5)').attr('data-label',"BKM");
						$j( nRow ).find('td:eq(6)').attr('data-label',"Cum HJS");
						$j( nRow ).find('td:eq(7)').attr('data-label',"Cum LCP");
						$j( nRow ).find('td:eq(8)').attr('data-label',"Cum PL");
						$j( nRow ).find('td:eq(9)').attr('data-label',"Cum BKM");
						$j( nRow ).find('td:eq(10)').attr('data-label',"Cum Total");
						$j( nRow ).find('td:eq(11)').attr('data-label',"Plan");
						$j( nRow ).find('td:eq(12)').attr('data-label',"Total Plan");
					},
					"initComplete": function(settings, json) {
						console.log(json);
						$j('#row_no').removeClass("sorting_asc");
						$j( "#list_bulan_wrapper .row" ).find( "div" ).eq( 0 ).attr('class', 'col-sm-3');
						$j( "#list_bulan_wrapper .row" ).find( "div" ).eq( 2 ).attr('class', 'col-sm-9');
						
						var element = document.getElementById('list_bulan_filter');
						var child = document.createElement('div');
						child.style.float = "right";
						child.innerHTML = '<select name="cr_bulan" data-column="9" class="form-control input-xm cr_bulan" id="cr_bulan2" onChange="pilih2(this.value,9,\'cr_bulan\');" title="Pilih Bulan">\n'+
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
			  '<input type="text" name="cr_tahun" class="form-control input-xm cr_tahun" id="cr_tahun2" onkeyup="pilih2(this.value,10,\'cr_tahun\');" placeholder="tahun" size="4">\n';
						
						var elementParent = element.parentNode;
						elementParent.insertBefore(child, element.nextSibling);
	
					
					  
					}
				});
				$j("#list_bulan_filter").css("display","none");
  });
  
function pilih2(nilai,index_data,el){
	
	var thn = el=='cr_tahun'?nilai:document.getElementById('cr_tahun2').value;
	var bln = el=='cr_bulan'?nilai:document.getElementById('cr_bulan2').value;
	
	if(bln !=='' || el=='cr_bulan'){
		$j('#dw_bulan').val(bln);
	 	if(thn!=='' && thn.length>=4){
			if (  $j.fn.DataTable.isDataTable( '#list_bulan' ) ) {
				//alert('cek1');
				$j('#list_bulan').DataTable().columns(9).search(bln).draw();
				//$j('#list_data').DataTable().columns(7).search(thn).draw();
			}
		 
		}
	}
	
	
	if(thn !== '' && thn.length>=4){
		//alert(bln +'  '+thn);
		$j('#dw_tahun').val(thn);
		if (  $j.fn.DataTable.isDataTable( '#list_bulan' ) ) {
			
			$j('#list_bulan').DataTable().columns(10).search(thn).draw();
			
			//$j('#list_data').DataTable().columns(6).search(bln).draw();
		}
		$j("#cr_tahun").val(thn);
		$j('#list_data').DataTable().columns(10).search(thn).draw();
	 
	}
 }

</script>
