<link type="text/css" href="<?php echo $theme_path;?>css/form_search.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path;?>css/pure-tables.css">
<style>
.notice{
	border:0px solid #0CF;
	background-color:#FFC;
	padding:10px 10px 5px 10px;
	font-size:12px;
	margin-bottom:10px;
}
.notice-red{
	border:0px solid #0CF;
	background-color:#FFC;
	padding:10px 10px 5px 10px;
	font-size:1.3em;
	margin-bottom:10px;
	color:#F00;
	text-align:center;
}
.jadwal{
	font-size:0.8em;
}
</style>
<div class="search-result" style="padding:8px 5px 8px 5px;">
<?php

if(trim($Pesan)==""){
?>
<table class="table table-bordered dataTable table-hover" >
<tr>
	<th>No</th>
	<th>Week</th>
    <th>Tahun</th>
    <th>Partner</th>
    <th>Qty</th>
    <th>Total</th>
    <th>Status Upload</th>
    <th>Keterangan</th>
</tr>
<?php 
$i=1;
while($data=current($list_hasil)){
?>
<tr>
	<td><?php echo $i;?></td>
	<td><?php echo $data['week'];?></td>
    <td s><?php echo $data['tahun'];?></td>
    <td><?php echo  $data['kontraktor'];?></td>
    <td style="text-align:right"><?php echo number_format($data['qty'],2,",",".");?></td>
    <td style="text-align:right"><?php echo number_format($data['total'],2,",",".");?></td>
     <td><?php echo  $data['success']?"Berhasil":"Gagal";?></td>
    <td><?php echo $data['pesan'];?></td>
</tr>
<?Php
$i++;
	next($list_hasil);
}
?>
</table>
<?php
}else{
	echo "<div class=\"notice-red\">".$Pesan."</div>";
}
?>
</div>