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
<?php  //echo "<pre>"; print_r($list_sapi);echo "</pre>";?>
<div id="media-print">
<?php //echo $kop_surat; ;?>
<br />
<br />
<div style="text-align:center;width:auto;font-weight:bold">DATA KEPEMILIKAN SAPI</div>
<br />
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
  	<table class="list-data" style="border-collapse:collapse" border="0"  width="100%">
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
            <td style=""><?php echo $no;?></td>
            <td style=""><?php echo $data->NoEartag;?></td>
            <td><?php echo $data->tipe_nama;?></td>
            <td style=""><?php echo $data->LaktasiKe;?></td>
            <td style=""><?php echo $data->CaraPerolehanNama;?></td>
            <td style=""><?php echo $data->TanggalIdentifikasi['Tanggal'];?></td>
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
</body>
</html>