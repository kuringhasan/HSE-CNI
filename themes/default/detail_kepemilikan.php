
<style>
#media-print{
	padding:0px 5px 5px 5px;
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
	text-align:center;
	padding:2px 3px 2px 3px;
}

</style>
<?php  //echo "<pre>"; print_r($list_sapi);echo "</pre>";?>
<div id="media-print">

  <table cellpadding="2" cellspacing="2" style="border-collapse:collapse;margin-bottom:8px;" id="data-header"> 
   
    <tr>
    	<td style="vertical-align:top;">Anggota</td><td style="vertical-align:top;">:</td>
        <td><?php echo $member->NoAnggota." - ".$member->NAMA;?>
        </td>
      
    </tr>
    <tr>
    	<td style="vertical-align:top;">TPK/Kelompok</td><td style="vertical-align:top;">:</td>
        <td><?php echo $member->NamaTPK."/".$member->NamaKelompok;?>
        </td>
      
    </tr>
    
    </table>
    <?php //echo "<pre>";print_r($DataAkademik);echo "</pre>";echo "<pre>";print_r($list_krs);echo "</pre>";exit;?>
  	<table class="list-data" style="border-collapse:collapse" border="1"  width="100%">
        <thead>
          <tr style="">              
            <th style="max-width:40px;width:40px; ">No</th>
            <th style="width:130px">No. Eartag</th>
            <th style="width:130px;">Tipe Sapi</th>
            <th style="">Laktasi Ke</th>
            <th style="width:130px;">Perolehan</th>
            <th style="">Tanggal Identifikasi</th>
          </tr>
        </thead>
        <tbody>
        <?php
        if(!empty($list_sapi)){
            $no=1;
            while($data=current($list_sapi)){
        ?>
         <tr>
            <td style="text-align:center;"><?php echo $no;?></td>
            <td style=""><?php echo $data->NoEartag;?></td>
            <td style="text-align:center;"><?php echo $data->tipe_nama;?></td>
            <td style="text-align:center;"><?php echo $data->LaktasiKe;?></td>
            <td style="text-align:center;"><?php echo $data->CaraPerolehanNama;?></td>
            <td style="text-align:center;"><?php echo $data->TanggalIdentifikasi['Tanggal'];?></td>
          </tr>
           <?php
           $no++;
            next($list_sapi);
            }
        }
            ?>
        </tbody>
    </table>
  
</div>