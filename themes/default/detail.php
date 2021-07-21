<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="RaFee" />
	 <link href="<?php echo $theme_path;?>ref/pretty-gallery/prettyPhoto.css" rel="stylesheet" type="text/css" />
   <script src="<?php echo $theme_path;?>ref/pretty-gallery/jquery-1.6.4.min.js" type="text/javascript"></script>
    
    <script src="<?php echo $theme_path;?>ref/pretty-gallery/jquery.prettyPhoto.js" type="text/javascript"></script>
    <script src="<?php echo $theme_path;?>ref/pretty-gallery/setup.js" type="text/javascript"></script>
</head>
<body>
<script>
$(function() {
		 
		
});


</script>
<style>
.label{
	width:160px;
}
.error{
	border:1px solid #F99;
	background-color:#FFC;
}
.lbl_error{
	color:#F00;
	padding-left:5px;
}
.tengah{
	text-align:center;
}
</style>
<div id="bi">
<table border="0" id="tabel_detail_barang">
<tr>
<td  class="label" style="vertical-align:top;">Kode Lokasi</td>
<td style="vertical-align:top;">:</td>
<td  ></td>
</tr>
<tr>
<td class="label" style="vertical-align:top;">Kode Barang</td>
<td style="vertical-align:top;">:</td>
<td style=""><?php echo $detail->sskelKode;?>
</td>
</tr>
<tr>
  <td class="label" style="vertical-align:top;">No. Register</td>
  <td style="vertical-align:top;">&nbsp;</td>
  <td style=""><?php echo $detail->BrgNoreg;?></td>
</tr>
<tr>
<td class="label" style="vertical-align:top;">Jenis Barang</td>
<td style="vertical-align:top;">:</td>
<td style=""> <?php echo $detail->sskelNama;?></td>
</tr>

<tr>
  <td class="label"> Tahun Perolehan</td>
  <td>:</td>
  <td><?php echo $detail->BrgTahunPerolehan;?></td>
</tr>
<tr>
    <td class="label">Harga Satuan</td>
    <td>:</td>
    <td><?php echo "Rp. ".number_format($detail->BrgHarga,0,",",".");?></td>
</tr>
<tr>
    <td class="label">Harga Atribusi</td>
    <td>:</td>
    <td><?php echo "Rp. ".number_format($detail->BrgHargaAtribusi,0,",",".");?></td>
</tr>
<tr>
    <td class="label">Total Harga</td>
    <td>:</td>
    <td><?php echo "Rp. ".number_format($detail->JumlahHarga,0,",",".");?></td>
</tr>
<tr>
    <td class="label">Asal-Usul/Cara Perolehan</td>
    <td>:</td>
    <td><?php echo $detail->cpNama;?></td>
</tr>
<tr>
    <td class="label">Kondisi</td>
    <td>:</td>
    <td><?php echo $detail->KondisiNama;?></td>
</tr>
<tr>
    <td class="label">Tanggal Pembukuan</td>
    <td>:</td>
    <td></td>
</tr>

<tr>
    <td class="label">Alamat</td>
    <td>:</td>
    <td><?php echo $detail->BrgAlamat;?></td>
</tr>
<tr>
    <td class="label">Kecamatan</td>
    <td>:</td>
    <td><?php echo $detail->kecNama;?></td>
</tr>
<tr>
    <td class="label">Koordinat</td>
    <td>:</td>
    <td><?php echo $detail->BrgKoordinat;?></td>
</tr>
</table>
</div>
<div id="kib">
</div>
<table border="0">
<tr>
<td  class="label" style="vertical-align:top;">Keterangan</td>
<td style="vertical-align:top;">:</td>
<td  ></td>
</tr>
</table>
   
<script type="text/javascript">

	$(document).ready(function () {
		var iframe = document.getElementById("media-galery");
		if (iframe) {
		   var iframeContent = (iframe.contentWindow || iframe.contentDocument);
		 
		  // iframeContent.errorForm(obj2.form_error);
		   iframeContent.setupPrettyPhoto();
			iframeContent.setupLeftMenu();
			iframeContent.setSidebarHeight();
		}
		


	});
</script>
 <style>
 
/* PrettyPhoto styling */
/*
.prettygallery{ margin-top:10px; }
.prettygallery li{display:inline; padding:0px 4px 4px 4px; margin:0px;  float:left; }
.prettygallery img {
	
	box-shadow:0px 0px 5px #ccc;
	-moz-box-shadow:0px 0px 5px #ccc;
	-webkit-box-shadow:0px 0px 5px #ccc;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	padding:5px;
	background:#fff;
	
}*/
 </style>
 <?php //print_r($gallery);?>
Gallery Foto:<br />
 <!--   <div class="block">
    <ul class="prettygallery clearfix">
   
    <?php 
    while($data=current($gallery))
    {
    ?>
        <li><a href="<?php echo $url_foto."/".$detail->BrgTahunPerolehan."/".$data->gbrNamaFile;?>" rel="prettyPhoto[gallery2]"
            title="">
            <img src="<?php echo $url_foto."/".$detail->BrgTahunPerolehan."/".$data->gbrNamaFile;?>" width="100" alt="<?php echo $data->gbrJudul;?>" /></a></li>
       <?php 
       next($gallery);
    }
    ?>  
       
            
    </ul>
</div>-->
<iframe id="media-galery"  src="http://localhost/simaset/index.php/tata_usaha/galery/121023020101010101110400120140001" style="width:750px;"></iframe>
</body>
</html>     
<script type="text/javascript">

function autosizeIframe(id_frame) {
 	var newheight;
    var newwidth;
    if(document.getElementById){
        newheight=300+parseInt(document.getElementById('tabel_detail_barang').offsetHeight);
        newwidth=60+parseInt(document.getElementById('tabel_detail_barang').offsetWidth);
		
    }
	//alert(newwidth+'  '+newheight);
	// window.parent.$('#confirmDelete .modal-dialog').css('width', newwidth+'px');
	window.parent.document.getElementById(id_frame).height= (newheight) + "px";
	window.parent.document.getElementById(id_frame).width= (newwidth) + "px";
   
} 

</script>
