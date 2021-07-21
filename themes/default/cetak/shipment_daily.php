<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title;?></title>
 <link rel="shortcut icon" type="image/x-icon" href="<?php echo $theme_path;?>images/favicon-32x32.png">
</head>

<body>
<style>
#media-print{
	padding:5px 5px 5px 5px;
	font-family:Verdana, Geneva, sans-serif;
	
}
.list-data tr td, #data-header  tr td{
	font-family:Verdana, Geneva, sans-serif;
	font-size:0.9em;
	padding:2px 3px 2px 3px;
	text-align:left;
}
.list-data tr th, #data-header  tr th{
	font-family:Verdana, Geneva, sans-serif;
	font-size:0.9em;
	border-top:1px solid #000;
	border-bottom:1px solid #000;
	text-align:left;
	padding:2px 3px 2px 3px;
}

#tanda-tangan tr td{
	font-family:Verdana, Geneva, sans-serif;
	font-size:0.8em;
	padding:1px 3px 1px 3px;
}
#tbl-item{
	border-collapse:collapse;
	margin:5px 0px 5px 0px;
}
#tbl-item tr td{
	padding:2px 3px 2px 3px;
	
}
#grand-total tr td{
	padding:4px 6px 4px 6px;
}
.tabel tr td{
	vertical-align:top;
}
.pdf-footer{
	font-family:Verdana, Geneva, sans-serif;
	font-size:.4em;
}
.pdf-footer-right{
	font-family:Verdana, Geneva, sans-serif;
	font-size:.4em;
	text-align:right;
	float:right;
}
.tbl-footer{
	border-top:1px solid #333;
	border-collapse:collapse;
}
.tbl-footer tr td{
	border-top:1px solid #333;
	font-family:Verdana, Geneva, sans-serif;
	font-size:10px;
}
</style>
<?php // echo "<pre>"; print_r($detail);echo "</pre>";?>

<?php //echo $kop_surat; ;?>
<br />
<?php
$ritase_loaded=0;
while($dt=current($detail->detail)){
	while($data=current($dt['data_shift'])){
		$ritase_loaded=$ritase_loaded+$data['ritase'];
?>
<div id="media-print" style="page-break-after: always;" >
 <img src="<?php echo $theme_path;?>images/header-logo.png" class="user-image" alt="User Image" height="55px;">
 <table class="list-data" style="border-collapse:collapse;" border="0"  width="100%" >
       
          <tr style="">              
            <td style="background-color:#CCC;font-weight:bold;">ACTIVITY REPORT PER SHIFT</td>
          </tr>
  
  </table>
  <table cellpadding="2" cellspacing="2" style="border-collapse:collapse;margin-bottom:8px;" id="data-header"> 
    
    <tr>
    	<td style="width:120px;">Hari Tanggal</td><td style="width:8px;">:</td>
        <td style="width:320px;"><?php echo $dt['detail_tanggal']['IndoHari'].", ".$dt['detail_tanggal']['Lengkap'];?>
        </td>
        <td>Hari Ke : </td>
      
    </tr>
    <tr>
    	<td style="vertical-align:top;">Shift</td><td style="vertical-align:top;">:</td>
        <td colspan="2"><?php echo $data['shift'];?>
        </td>
      
    </tr>
    
    </table>
     <table cellpadding="2" cellspacing="2" style="border-collapse:collapse;margin-bottom:8px;" id="data-header"> 
    
    <tr>
      <td style="width:16px;">A.</td>
    	<td style="width:180px;">Nama TB/BG</td><td style="width:8px;">:</td>
        <td style="width:320px;"><?php echo $detail->barge_name;?>
        </td>
      
    </tr>
    <tr>
      <td style="vertical-align:top;">&nbsp;</td>
    	<td style="vertical-align:top;">Berthing</td><td style="vertical-align:top;">:</td>
        <td ><?php echo $detail->berth_time2;?>
        </td>
      
    </tr>
    <tr>
      <td style="vertical-align:top;">&nbsp;</td>
    	<td style="vertical-align:top;">Jetty/ Gate</td><td style="vertical-align:top;">:</td>
        <td ><?php echo $detail->jetty_name."/".$detail->gate_name;?>
        </td>
      
    </tr>
    <tr>
      <td style="vertical-align:top;">&nbsp;</td>
    	<td style="vertical-align:top;">Rencana Muat</td><td style="vertical-align:top;">:</td>
        <td ><?php echo number_format($detail->pre_stowage_plan,2,",",".");?>
        </td>
      
    </tr>
     <tr>
      <td style="vertical-align:top;">&nbsp;</td>
    	<td style="vertical-align:top;">Commenced Barging</td><td style="vertical-align:top;">:</td>
        <td ><?php echo $detail->commenced_time2;?>
        </td>
      
    </tr>
    </table>
    <?php
	$i=1;
	while($dm=current($data['domes'])){
	?>
   <table cellpadding="2" cellspacing="2" style="border-collapse:collapse;margin-bottom:8px;" id="data-header"> 
    
    <tr>
      <td style="width:16px;"><?php echo $i==1?"B.":"";?></td>
    	<td style="width:180px;">Contractor</td><td style="width:8px;">:</td>
        <td style="width:320px;"><?php echo $dm['contractor_name'];?>
        </td>
      
    </tr>
    <tr>
      <td style="vertical-align:top;">&nbsp;</td>
    	<td style="vertical-align:top;">Asal Cargo</td><td style="vertical-align:top;">:</td>
        <td ><?php echo $dm['dome_name'];?>
        </td>
      
    </tr>
    <tr>
      <td style="vertical-align:top;">&nbsp;</td>
    	<td style="vertical-align:top;">Jarak Cargo</td><td style="vertical-align:top;">:</td>
        <td ><?php echo $dm['dome_distance'];?>
        </td>
      
    </tr>
   
    </table>
     <?php
	 $i++;
	 next($data['domes']);
	}
	?>
    <table cellpadding="2" cellspacing="2" style="border-collapse:collapse;margin-bottom:8px;" id="data-header"> 
    
    <tr>
      <td style="width:16px;">C.</td>
    	<td style="width:180px;">Jumlah DT</td><td style="width:8px;">:</td>
        <td style="width:320px;"><?php //echo $detail->barge_name;?>
        </td>
      
    </tr>
    <tr>
      <td style="vertical-align:top;">&nbsp;</td>
    	<td style="vertical-align:top;">Total Retase/Shift </td><td style="vertical-align:top;">:</td>
        <td ><?php echo $data['ritase'];?> Rit
        </td>
      
    </tr>
    <tr>
      <td style="vertical-align:top;">&nbsp;</td>
    	<td style="vertical-align:top;">Total Retase Loaded</td><td style="vertical-align:top;">:</td>
        <td ><?php echo $ritase_loaded;?> Rit
        </td>
      
    </tr>
    <tr>
      <td style="vertical-align:top;">&nbsp;</td>
    	<td style="vertical-align:top;">Rata-rata Retase/ Jam </td><td style="vertical-align:top;">:</td>
        <td ><?php echo round($data['ritase']/$data['jumlah_jam'],2);?> Rit/jam
        </td>
      
    </tr>
    <tr>
      <td style="vertical-align:top;">&nbsp;</td>
    	<td style="vertical-align:top;">Rata-rata Retase/ DT/ Jam</td><td style="vertical-align:top;">:</td>
        <td ><?php //echo $detail->jetty_name."/".$detail->gate_name;?> Rit/DT/Jam
        </td>
      
    </tr>
    </table>
    
     <table cellpadding="2" cellspacing="2" style="border-collapse:collapse;margin-bottom:8px;" id="data-header"> 
    
    <tr>
      <td style="width:16px;">D.</td>
    	<td style="width:180px;">Est. Muatan/ DT</td><td style="width:8px;">:</td>
        <td style="width:320px;">18
        </td>
      
    </tr>
    <tr>
      <td style="vertical-align:top;">&nbsp;</td>
    	<td style="vertical-align:top;">Est. Cargo Loaded/ Shift </td><td style="vertical-align:top;">:</td>
        <td ><?php //echo $detail->berth_time2;?>
        </td>
      
    </tr>
    <tr>
      <td style="vertical-align:top;">&nbsp;</td>
    	<td style="vertical-align:top;">Total Cargo Loaded</td><td style="vertical-align:top;">:</td>
        <td ><?php //echo $detail->jetty_name."/".$detail->gate_name;?>
        </td>
      
    </tr>
    <tr>
      <td style="vertical-align:top;">&nbsp;</td>
    	<td style="vertical-align:top;">Ballance Cargo </td><td style="vertical-align:top;">:</td>
        <td ><?php //echo $detail->berth_time2;?>
        </td>
      
    </tr>
   
    </table>
    
    
    <table cellpadding="2" cellspacing="2" style="border-collapse:collapse;margin-bottom:8px;" id="data-header"> 
    
    <tr>
      <td style="width:16px;">E.</td>
    	<td style="width:180px;">Waktu Effektif Kerja</td><td style="width:8px;">:</td>
        <td style="width:320px;"><?php echo ($data['jumlah_jam']-$data['uneffective_time']);?>
        </td>
      
    </tr>
    <tr>
      <td style="vertical-align:top;">&nbsp;</td>
    	<td style="vertical-align:top;">Waktu Non-Effektif </td><td style="vertical-align:top;">:</td>
        <td ><?php echo $data['uneffective_time'];?>
        </td>
      
    </tr>
    <tr>
      <td style="vertical-align:top;">&nbsp;</td>
    	<td colspan="3" style="vertical-align:top;">
         <table width="100%">
         <?php
		 while($un=current($data['list_gangguan'])){
		 ?>
         <tr>
         <td style="width:16px; vertical-align:top;">-</td>
         <td style="width:65%;"><?php echo $un['gangguan'];?></td>
         <td style="width:16px;vertical-align:top;">:</td>
         <td style=" vertical-align:top;"><?php echo $un['jumlah_jam'];?></td>
         </tr>
         <?php
		 next($data['list_gangguan']);
		 }?>
         </table>
         
        
        </td>
      </tr>
    
    </table>
    <?php //echo "<pre>";print_r($DataAkademik);echo "</pre>";echo "<pre>";print_r($list_krs);echo "</pre>";exit;?>
   </div>
 <?php
 	next($dt['data_shift']);
	}
 next($detail->detail);
}
?> 

</body>
</html>