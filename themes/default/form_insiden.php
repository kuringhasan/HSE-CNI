<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />

<script>

function errorForm(msj_obj){
	 if (jQuery.isEmptyObject(msj_obj)==false)
	 {
		 var errors=msj_obj;
		for (var key in errors){
			$("#"+key).addClass("error");
			$("#err_"+key).html(errors[key]);
			$("#err_"+key).addClass("lbl_error");
			$("#err_"+key).show();
		}
	 }
}
    function setTextField(ddl) {
        document.getElementById('tipe_text').value = ddl.options[ddl.selectedIndex].text;
    }
</script>
<style>
.responsive-form #frmBulanLahir{
	margin-left:5px;
}
.responsive-form #frmTahunLahir{
	margin-left:5px;
}
@media screen and (max-width: 500px) {
	.responsive-form #frmBulanLahir{
		margin-left:0px;
	}
	.responsive-form #frmTahunLahir{
		margin-left:0px;
	}
}
@media screen and (max-width: 320px) {
	.responsive-form #frmBulanLahir{
		margin-left:0px;
	}
	.responsive-form #frmTahunLahir{
		margin-left:0px;
	}
}
</style>
<div class="responsive-form" >
 <form id="form_input_data"   method="post" enctype="multipart/form-data">
 <input type="hidden"name="id" value="<?php echo $id;?>"/>
 <div class="row-form">
        <span class="label">Waktu</span>
        <input type="text" class="input" name="waktu" id="waktu" placeholder="Waktu"  size="35" value="<?php echo $waktu;?>"/>
        
    </div>

    <div class="row-form">
        <span class="label">Nama Pelapor</span>
        <input type="text" class="input" name="pelapor" id="pelapor" placeholder="Nama Pelapor"  size="35" value="<?php echo $nama_pelapor;?>"/>
    </div>
  
     <!-- <div class="row-form">
        <span class="label" >Kontraktor</span>
        <select name="frm_contractor" data-column="1" class="input" id="frm_contractor" >
                  <?php
                echo '<option value="">-- Kontraktor --</option>';
                $List=$list_contractor;
                while($data = each($List)) {
                    ?>
              <option value="<?php echo $data['key'];?>" <?php echo $data['key']==$kode_company?"selected":""; ?>><?php echo $data['value'];?></option>
              <?php
              
                }
              ?>
        </select>
    </div> -->
    <div class="row-form">
        <span class="label">Lokasi</span>
        <input type="text" class="input" name="lokasi" id="lokasi" placeholder="Lokasi"  size="35" value="<?php echo $lokasi;?>"/>
    </div>
    <div class="row-form">
        <span class="label" >Mekanisme <br> Kecelakaan</span>
        <select name="jenis_kecelakaan" data-column="1" class="input" id="jenis_kecelakaan" >
                  <?php
                echo '<option value="0">-- Mekanisme Kecelakaan --</option>';
                $List=$list_jenis_kecelakaan;
                while($data = each($List)) {
                    ?>
              <option value="<?php echo $data['key'];?>" <?php echo $data['key']==$jenis_kecelakaan?"selected":""; ?>><?php echo $data['value'];?></option>
              <?php
              
                }
              ?>
        </select>
    </div> 
    <div class="row-form">
        <span class="label">Jumlah Korban<br>(bila ada)</span>
        <input type="number" class="input" name="jml_korban" id="jml_korban" placeholder="Jumlah Korban"  size="35" value="<?php echo $jumlah_korban;?>"/>
    </div>
    <div class="row-form">
        <span class="label" >Tingkat Keparahan<br>(bila ada)</span>
        <select name="tingkat_keparahan" data-column="1" class="input" id="tingkat_keparahan" >
                  <?php
                echo '<option value="0">-- Tingkat Keparahan --</option>';
                $List=$list_tingkat_keparahan;
                while($data = each($List)) {
                    ?>
              <option value="<?php echo $data['key'];?>" <?php echo $data['key']==$tingkat_keparahan?"selected":""; ?>><?php echo $data['value'];?></option>
              <?php
              
                }
              ?>
        </select>
    </div>
    <div class="row-form">
        <span class="label">Bantuan yang <br>Diperlukan</span>
        <textarea name="bantuan" rows="4" cols="40"><?php echo $bantuan;?></textarea>
    </div>
    <div class="row-form">
        <span class="label">Tambah Gambar</span>
        <input id="foto" type="file" name="file[]" accept="image/*" multiple>
    </div>

    <div id="foto"></div>
</form>
</div>

<?php
if($typeform == "edit"){
    // echo $list_foto;
?>
<hr>
<div  class="responsive-form" style="width: 100%;">
<center><h5>Photos</h5></center>
    <table class="table table-bordered table-hover dataTable">
        <thead class="header-data"> 
        <tr>
            <th>No</th>
            <th>Photo</th>
            <th style="width:10%; text-align:center;"><i class="fa fa-gear"></i></th>
        </tr>
        </thead>
        <tbody id="datafoto" >
        <?php
        foreach ($list_foto as $value) {
        ?>
            <tr>
                <td><?php echo $value['No']; ?></td>
                <td colspan='4' style='text-align:center;'>
                <a href="/files/hse/<?php echo $value['namafile']; ?>" target="_blank">
                <img src='/files/hse/<?php echo $value['namafile']; ?>' width='80%'>
                </a>
                </td>
                <td><a href="javascript:void(0);" onclick="hapusFoto('<?php echo $value['id'] ?>');" class="btn btn-sm btn-primary">Hapus&nbsp<i class="fa fa-trash"></i></a></td>
            </tr>        
        <?php
        }
        ?>
        </tbody>
    </table>
</div>
<?php 
}
?>



<!-- <script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> -->
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>

<link type="text/css" href="<?php echo $theme_path;?>bootstrap/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<script  src="<?php echo $theme_path;?>bootstrap/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>bootstrap/plugins/datetimepicker/js/locales/bootstrap-datetimepicker.id.js" charset="UTF-8"></script>
  
<script>

var $j = jQuery.noConflict();
$j(document).ready(function() {
   $j('#waktu').datetimepicker({
      format: 'yyyy-mm-dd hh:ii:ss',
      language: 'id'
                });
    showFoto();    
});

// var no = 0;
// function tambahFoto(){
//     var ft = "<div id='foto"+no+"' class='responsive-form' style='width: 100%;'>"+
//                 "<table class='table table-bordered table-hover dataTable'>"+
//                     "<tbody id='datafoto' >"+
//                         "<tr>"+
//                             "<td colspan='4' style='text-align:center;'>"+
//                             "<input name='fotos[]' type='file' multiple>"+
//                             "</td>"+
//                             "<td colspan='4' style='text-align:center;'>"+
//                             "<center><a onclick='removeFoto("+no+")'><i class='fa fa-close'></i></a></center>"+
//                             "</td>"+
//                         "</tr>"+        
//                     "</tbody>"+
//                 "</table>"+
//              "</div>";


//     $("#foto").append(ft);
//     no++;
// }
// function removeFoto(no){
//     var myobj = document.getElementById("foto"+no);
//     myobj.remove();
// }
</script>
