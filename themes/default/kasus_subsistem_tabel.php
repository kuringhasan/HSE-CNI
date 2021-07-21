<link type="text/css" href="<?php echo $theme_path;?>css/tabel.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $theme_path;?>js/jquery/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>js/jquery-ui-1.8.14.custom.min.js"></script>
<form id="form-list-data" style="width:100%">
<div class="btn-group" style="float:left;padding-top:2px;padding-bottom:2px;">

<?php echo $tombol;?>
</div>
<?php
		$curr_page = $_POST['current_page']==''?1:$_POST['current_page'];
 ?>
<input name="current_page" id="current_page" type="hidden" value="<?php echo $curr_page; ?>" size="4"  />
<?php
		$jml_tampil = $_POST['jml_tampil']==''?20:$_POST['jml_tampil'];
 ?>

<div id="media-navigasi" style="padding-top:2px;padding-bottom:2px; text-align:right;padding-right:0px;" class="pull-right col-sm-3">

</div>

<div style="clear:both"></div>
<style>
#tbl-penampung th{
	border:1px solid #ccc;
	padding:3px 3px 3px 3px;
	background-color: #dce9f9;
    background-image: -webkit-gradient(linear, left top, left bottom, from(#ebf3fc), to(#dce9f9));
    background-image: -webkit-linear-gradient(top, #ebf3fc, #dce9f9);
    background-image:    -moz-linear-gradient(top, #ebf3fc, #dce9f9);
    background-image:     -ms-linear-gradient(top, #ebf3fc, #dce9f9);
    background-image:      -o-linear-gradient(top, #ebf3fc, #dce9f9);
    background-image:         linear-gradient(top, #ebf3fc, #dce9f9);
	font-size:0.9em;
	text-align:center;
	vertical-align:middle;
}
#tbl-penampung td{
	border:1px solid #ccc;
	padding:3px 3px 3px 3px;
	font-size:0.9em;
	
}
.nama{
	border-bottom:1px solid #dce9f9;
	font-weight:bold;
	width:95%;
}
.bidang{
	font-style:italic;
}
</style>

<div class="box-body table-responsive no-padding">
<table class="table table-bordered bordered" style="border-collapse:collapse;" id="tbl-penampung">
<tr>
<th style="max-width:40px;width:40px;">No</th>
<th style="max-width:100px;width:100px;">ID</th>
<th style="width:200px;">Nama Subsistem</th>
<th>Keterangan</th>
<th style="width:100px;vertical-align:middle">Aksi</th>
</tr> 
<?php
if(count($ListData)>0)
{
	$i=0;
	while($data=current($ListData))
	{
	?>
	<tr>
	<td style="text-align:center"><?php echo $data->No;?></td>
	<td style="text-align:center;"><?php echo $data->Kode;?></td>
	<td style="text-align:left"><?php echo $data->Nama;?></td>
    <td style="text-align:left"><?php echo $data->Keterangan;?></td>
    <td style="text-align:center"><?php echo $data->Kontrol;?></td>
	</tr>
	 <?php   
	$i++;
	next($ListData);
	}
}
?>
 
</table>
<div id="footer-navigasi" style="padding-top:2px; text-align:right;padding-right:0px;display:inline-block;" class="pull-right col-sm-3">
<script>
function toggleField(hideObj,showObj){
	
  hideObj.disabled=true;        
  hideObj.style.display='none';
  showObj.disabled=false;   
  showObj.style.display='inline';
 // showObj.focus();
}
<?php
if(in_array($jml_tampil,array(10,20,50,100))){
	echo 'toggleField(document.getElementById("in_jml_tampil"),document.getElementById("sel_jml_tampil"));';
}else{
	echo 'toggleField(document.getElementById("sel_jml_tampil"),document.getElementById("in_jml_tampil"));';
}
?>
</script>
Tampil : 

<select name="jml_tampil" id="sel_jml_tampil"
          onchange="if(this.options[this.selectedIndex].value=='customOption'){
              toggleField(this,this.nextSibling);
              this.selectedIndex='0';
          }" class="input" style="width:55px;">
            <option value=""></option>
            <option value="10" <?php echo 10==$jml_tampil?"selected":""; ?> >10</option>
            <option value="20" <?php echo 20==$jml_tampil?"selected":""; ?>>20</option>
            <option value="50" <?php echo 50==$jml_tampil?"selected":""; ?>>50</option>
            <option value="100" <?php echo 100==$jml_tampil?"selected":""; ?>>100</option>
            <option value="customOption">[xx]</option>
        </select><input name="jml_tampil" style="display:none;width:55px;"  id="in_jml_tampil"
        <?php echo in_array($jml_tampil,array(10,20,50,100))?"disabled=\"disabled\"":"";?>
            onblur="if(this.value==''){toggleField(this,this.previousSibling);}" class="input" value="<?php echo !in_array($jml_tampil,array(10,20,50,100))?$jml_tampil:"";?>">


</div>
</div>

</form> <!-- end of form-list-data -->


<script>
$(document).ready(function () {

	$('#btn-tambah-data').click(function(e) {
        var target = $(this).attr('href');
	
        // load the url and show modal on success 
		$('#largeModal .modal-title').html("Form Input Data");
		$('#largeModal .modal-body').load(target, function() {
             $('#largeModal').modal('show');
        });
		$('#largeModal .modal-footer').html('');
		 $('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_simpan\"  onclick=\"simpan('"+target+"/save','form_input_data');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
       

        e.preventDefault();
    });
	
	$(".btn-edit-data").click(function(e) {
        var target = $(this).attr('href');
        // load the url and show modal on success 
		$('#largeModal .modal-title').html("Form Edit Data");
		$('#largeModal .modal-body').load(target, function() {
             $('#largeModal').modal('show');
        });
		$('#largeModal .modal-footer').html('');
		 $('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"tombol_simpan\"  onclick=\"simpan('"+target+"/save','form_input_data');\"><i class=\"fa fa-save\"></i>&nbsp;Simpan</button>");
       

        e.preventDefault();
    });
	
	$('.btn-del-data').click(function(e) {
        var url_del = $(this).attr('href');
		 var judul = $(this).attr('title');
		var nama = $(this).attr('role');
		 $('#largeModal .modal-title').html("Konfirmasi "+judul);
		 $('#largeModal .modal-body').html("<h4>Yakin data <strong>"+nama+"</strong> akan dihapus?</h4>");//.css("text-align","center");
		 $('#largeModal .modal-footer').html('');
		 $('#largeModal .modal-footer').append("<button type=\"button\" class=\"btn btn-primary btn-xs\" data-dismiss=\"modal\" id=\"tombol_batal\"><i class=\"fa fa-times\"></i>&nbsp;Batal</button><button type=\"button\" class=\"btn btn-primary btn-xs\" id=\"hapus_pegawai\" onclick=\"hapus('"+url_del+"','"+nama+"');\" ><i class=\"fa fa-trash-o\"></i>&nbsp;Hapus</button>");
		 //$('#largeModal .modal-footer #simpan_pegawai').html("Hapus");
        // load the url and show modal on success
       /* $('#largeModal .modal-body').load(target, function() {
             $('#largeModal').modal('show');
        });

        e.preventDefault();*/
    });
	
});
function simpan(url_save,id_form){
	
	$.ajax({
			type:"POST",
			url: url_save,
			data: $("#"+id_form).serialize(),
			success: function(data, status) {
				var obj2 = JSON.parse(data);
				if (obj2.success==true){
					loaddata($("#form_cari").serialize());
					//$('#largeModal .modal-body').html(obj2.pesan);
					$('#largeModal .modal-body').html("<h5>"+obj2.message+"</h5>");
					$('#largeModal .modal-footer #tombol_batal').html("<i class=\"fa fa-times\"></i>&nbsp;Tutup");
					$('#largeModal .modal-footer #tombol_simpan').remove();
					
					
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
function hapus(url_hapus,nama)
{
	$.ajax({
			type:"POST",
			url: url_hapus,
			data: 'nama='+nama,
			success: function(data, status) {
				var obj2 = JSON.parse(data);
				if (obj2.success==true){
					loaddata($("#form_cari").serialize());
					//$('#largeModal .modal-footer').remove();
					//$('#largeModal .modal-body').html(obj2.pesan);
					$('#largeModal .modal-body').html("<h5>"+obj2.message+"</h5>");
					$('#largeModal .modal-footer #tombol_batal').html("<i class=\"fa fa-times\"></i>&nbsp;Tutup");
					$('#largeModal .modal-footer #hapus_pegawai').remove();
					
					
				}else{
					$('#largeModal .modal-body').html("<h5>"+obj2.message+"</h5>");
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
</script>