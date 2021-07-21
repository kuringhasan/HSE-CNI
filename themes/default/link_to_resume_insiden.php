<?php  //echo "<pre>"; print_r($detail);echo "</pre>";?>
<style>

table tr td{
	font-size:12px;
}
</style>
<div class="row">
    <div class="col-md-12">
    	<!-- About Me Box -->
          <div class="box box-primary">
         
            <!-- /.box-header -->
            <div class="box-body" >

            <div class="row">
                <div class="col-xs-12">
                
                <div class="box box-solid">
                    <div class="box-header">
                    <!-- <h3 class="box-title">Data Kota & Kabupaten
                    
                    </h3>-->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                    <form name="form_data" id="form_data" action="">
                    <div class="row-form">
                        <span>Masukkan No.Register</span>
                        <input id="foto" type="text" name="no_register" >
                        <a onclick="cari_data()" class="btn btn-primary btn-sm">Cari</a>
                    </div>
                    </form>
                    <div class="table-responsive">
                        <table style="display:none;" id="list_data" class="table table-bordered table-hover dataTable"  style="width:100%">
                            <thead class="header-data">
                                <tr>
                                <th style="width:200px;">No Register</th>
                                <th style="width:200px;">Kontraktor</th>
                                <th style="width:150px;">Tanggal</th>
                                <th style="width:200px;">Keterangan</th>
                                <th style="width:200px;">Status</th>
                                <th style="width:200px;"> <i class="fa fa-gear"></i> </th>
                                </tr>
                            </thead>
                            <tbody id="data_list">
                                <tr>
                                <th class="no-form"></th>
                                <th class="no-form"></th>
                                <th class="no-form"></th>
                                <th class="no-form"></th>
                                <th class="no-form"></th>
                                <th class="no-form"></th>
                                </tr>
                            </tbody>
                            
                        
                        </table>
                    </div>
                </div>
                    <!-- /.box-body -->
                    
                    
                </div>
                <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
    </div>
</div>

<script>
var url_link = '<?php echo $url_link; ?>';
function cari_data(){
    // alert();
    $j.ajax({
        type:"POST",
        url: url_link,
        data: $j("#form_data").serialize(),
        success: function(data, status) {
            var dt = JSON.parse(data);
            $('#list_data').css("display","block");
            var html 
            for (var i = 0; i < dt.length; i++) {
               html += "<tr>"+
                        "<th class='no-form'>"+dt[i]['no_register']+"</th>"+
                        "<th class='no-form'></th>"+
                        "<th class='no-form'></th>"+
                        "<th class='no-form'></th>"+
                        "<th class='no-form'></th>"+
                        "<th class='no-form'><a onclick='pilihResume("+dt[i]['id_resume']+")' class='btn btn-primary btn-sm'>Pilih</a></th>"+
                        "</tr>";
            }
            $('#data_list').html(html);
        }
    });
}

var id_pelaporan = '<?php echo $id_pelaporan; ?>';

function pilihResume(id){
    if (confirm('Sambungkan data resume?')) {
        $j.ajax({
            type:"POST",
            url: url_link,
            data: {id_resume:id,id_pelaporan:id_pelaporan},
            success: function(data, status) {
                if(data=="Berhasil"){
                    location.reload();
                }else{
                    alert(data);
                }
            }
        });
    }
}
</script>
