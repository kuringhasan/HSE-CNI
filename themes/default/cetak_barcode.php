<style>
.media-cetak, .media-cetak table tr td{
	font-family:Times, serif;
	font-size:0.9em;
	font-weight:normal;
	line-height:13px;
	
}
.media-cetak table {
	border-collapse:collapse;
	margin-bottom:5px;
	margin-top:5px;
}
.media-cetak table tr th{
	vertical-align:middle;
	padding-left:3px;
	padding-right:3px;
	text-align:center;
	font-size:0.9em;
}
.media-cetak {
	width:660px;
	border:0px solid #000;
}
.media-cetak .title{
	font-size:1.2em;
	font-weight:bold;
	line-height:0.9em;
}
.tabel-utama {
	width:655px;
	border:0px solid #000;
	margin-bottom:8px;
	margin-top:5px;
	border-collapse:collapse;
}
.tabel-utama tr td{
	padding-bottom:3px;
	padding-top:3px;
	padding-left:3px;
	padding-right:3px;
}
</style>
<div class="media-cetak">
<table cellpadding="0" cellspacing="0" >
<?php
foreach($data as $key=>$value){
?>

<tr style=""><td style="width:90px; text-align:center;border-top:1px dotted #000;"><img src="<?php echo $theme_path;?>images/logo_tsm.png"  height="70" alt="-"  style="margin-left:0px;" /></td>
  <td style="text-align:center;border-top:1px dotted #000;"><span class="title"><strong>BARANG MILIK PEMERINTAH<br />
 KOTA TASIKMALAYA</strong></span></td>
</tr>
<tr><td colspan="2" style="text-align:left;">
&nbsp;<?php echo $value['SKPD'];?><br />
<img src="<?php echo $url_base.barcode::build($value['ID'],true,false,70);?>" width="400" /></td></tr>
<tr>
  <td colspan="2" style="height:10px;"></td>
</tr>
<?php
}
?>
</table>
</div>