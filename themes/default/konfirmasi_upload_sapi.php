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
//echo $_SESSION['success_upload'];
echo $btn_upload;
//echo "<pre>";print_r($list_hasil);echo "</pre>";
if(trim($Pesan)==""){
?>
<table class="table table-bordered dataTable table-hover" >
<tr>
	<th>No</th>
	<th>No Eartag</th>
    <th>Nama</th>
    <th>Pemilik</th>
    <th>Status Sapi</th>
    <th>Tanggal Lahir</th>
    <th>Cara Perolehan</th>
    <th>Upload</th>
    <th>Keterangan</th>
</tr>
<?php 
$i=1;
while($data=current($list_hasil)){
?>
<tr>
	<td><?php echo $i;?></td>
	<td><?php echo $data['no_eartag'];?></td>
    <td><?php echo $data['nama'];?></td>
    <td><?php echo  $data['id_pamilik']." - ".$data['nama_pamilik'];?></td>
    <td><?php echo $data['status_sapi'];?></td>
    <td><?php echo $data['tanggal_lahir'];?></td>
    <td><?php echo $data['cara_perolehan'];?></td>
     <td><?php echo  $data['success']?"Berhasil":"Gagal";?></td>
    <td><?php echo $data['message'];?></td>
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