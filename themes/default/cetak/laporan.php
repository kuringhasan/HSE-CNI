<?php
set_time_limit(3600);
ini_set("memory_limit","1024M");
ob_start();
?>
<style>
.media-cetak, .media-cetak table tr td{
	font-size:0.8em;
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
	font-size:0.8em;
}
.media-cetak table tr td{
	vertical-align:top;
	padding:2px 3px 2px 3px;
}
.media-cetak {
	width:660px;
	border:0px solid #000;
}
.media-cetak .title{
	font-size:1.1em;
	font-weight:bold;
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
<?php
if($format=="html"){
	?>
     <div id="media_aksi_print" style="width:790px; text-align:center; padding:5px 5px 5px 5px; border:1px solid #666; margin-bottom:10px;">  <button type="button" onclick="cetak();" id="cetak_priview">Print</button> &nbsp;<input type="checkbox" id="hide_tombol_print" onchange="buka(this.value);" /><span id="label_bersihkan">&nbsp;Bersihkan</span>
  </div>
    <script>
	
	document.getElementsByTagName("body")[0].style.marginLeft ='50px';
	if (window.matchMedia("(orientation: portrait)").matches) {
   // you're in PORTRAIT mode
   		document.getElementsByTagName("body")[0].setAttribute("class", 'landscape');
	}
	
	if (window.matchMedia("(orientation: landscape)").matches) {
	   // you're in LANDSCAPE mode
	  // document.getElementsByTagName("body")[0].setAttribute("class", currMode);
	}
	
   function buka(nilai)
	{
		if (document.getElementById('hide_tombol_print').checked) 
		 {
			 document.getElementById('media_aksi_print').style.display="none";
		 } else {
			 alert('not');
		 }
	}
     
	function cetak(){
		document.getElementById('media_aksi_print').style.display="none";
		window.print();
		document.getElementById('media_aksi_print').style.display="block";
	}
	</script>
	</script>
    <?php
}?>

<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm"  >
<div class="media-cetak" >
<?php

if (trim($KopSurat)=="on")
{
	$master=new Master_Ref_Model();
	$master->kopsurat($format_cetak);
}
 //echo "<pre>";print_r($listdata);echo "</pre>"; 
 ?>
<div style="text-align:center; width:800px;" ><span class="title">BADAN PEMBERDAYAAN MASYARAKAT DAN PEMERINTAH DESA</span>
<br />
Laporan Semester
</div>


    <table  cellpadding="3" cellspacing="3"  style="border-collapse:collapse;border:0px solid #000; width:800px;" border="1" >
          
            <tr>
                <th style="max-width:40px;width:40px;">No</th>
                <th style="max-width:100px;width:100px;">Tanggal</th>
                <th >Nama</th>
                <th>Alamat</th>
                <th>Instansi</th>
                <th>Keperluan</th>
                <th>Yang Dituju</th>
            </tr>
        <?php
        $i=1;
        while($data=current($listdata))
        {
           
        ?>
       <tr>
       		<td class='isi' style="text-align:center;width:30px;"><?php echo $i;?></td>
       		<td class='isi' style="text-align:center;width:60px;"><?php echo $data->tmNama;?></td>
        	<td class='isi tengah' style="width:140px;"><?php echo $data->tmNama;?></td>
        	<td class='isi tengah' style=""><?php echo $data->tmAlamat;?></td>
         	<td class='isi tengah' style="text-align:left;width:70px;"><?php echo $data->tmAlamat;?></td>
         	<td class='isi tengah' style="text-align:left; font-weight:bold;width:110px;"><?php echo $data->tmKeperluan;?></td>
            <td class='isi tengah' style="width:180px;"><?php echo $data->pNoInduk."<br />".$data->pNama;?></td>
              </tr>
         	
			 <?php 
			  
			$i++;
			next($listdata);
        }
        ?>
        
  </table>
      
<table border="0"  class="tabel-utama" style="width:95%;">
<tr>
 	<td  style="text-align:left; width:35%;"></td>
     <td style="text-align:left;width:30%;"></td>
     <td  style="text-align:left;width:35%;"><?php echo $sp->TempatTTD;?>, <?php echo $sp->TanggalNama;?></td>
 </tr>
 <tr>
 	<td  style="text-align:center; width:35%;">
        MENGETAHUI<br />
        KEPALA SKPD,<br />
        <br />
    	<br />
        <br />
		 <strong><u><?php echo $TTD['KepalaSKPD']['Nama'];?></u>
            </strong><br />
            <small>NIP. <?php echo $TTD['KepalaSKPD']['NIP'];?></small>
    </td>
    <td style="text-align:left;width:30%;"></td>
    <td  style="text-align:center;width:35%;">
        <?php echo $TitiMangsa;?><br />
        Kepala Subbagian Umum,<br />
        <br />
        <br />
        <br />
        <strong ondblclick="editpangurus();"><u><?php   echo  $TTD['Pengurus']['Nama'] ;?></u></strong><br />
		<small><?php  echo "NIP. ".$TTD['Pengurus']['NIP'];?></small> 
    </td>
  </tr>
</table>


</div>
</page>

<?php
if($format=="pdf"){
    	
	$content = ob_get_clean();

    $html2pdf = htmlpdf::build("L","F4",array(25,5,25,5));
	$html2pdf->pdf->SetDisplayMode('fullpage');
	$html2pdf->writeHTML($content);
	$html2pdf->Output('rekap_bi.pdf');    
}
?>