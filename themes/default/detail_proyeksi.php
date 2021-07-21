<?php  //echo "<pre>"; print_r($detail);echo "</pre>";?>

<div class="row">
    <div class="col-md-6">
    	<!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Data Sapi</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             
              <p class="text-muted">
               <table>
               <tr>
               		<td style="width:100px;">No Eartag</td>
                    <td style="width:8px;">:</td>
                    <td><?php echo $detail->NoEartag;?></td>
               </tr>
                <tr>
               		<td>Nama Sapi</td>
                    <td>:</td>
                    <td><?php echo trim($detail->koloni_name)<>""?$detail->koloni_name:"-";?></td>
               </tr>
                <tr>
               		<td>Identifikasi</td>
                    <td>:</td>
                    <td><?php echo !empty($detail->Cow->TanggalIdentifikasi)?$detail->Cow->TanggalIdentifikasi['Lengkap']:"-";?></td>
               </tr>
               <tr>
               		<td>Laktasi Ke</td>
                    <td>:</td>
                    <td><?php echo $detail->Cow->laktasi_ke;?></td>
               </tr>
                <tr>
               		<td>Metode Perolehan</td>
                    <td>:</td>
                    <td><?php echo $detail->Cow->MetodePerolehanNama;?></td>
               </tr>
               </table>
             
              </p>

             
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
    </div>
    <div class="col-md-6">
    	<!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Pemilik Sapi</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" >
               <p class="text-muted" style="color:#000">
               <?php echo $detail->pemilik;?>
              </p>
				
              <p class="text-muted" style="color:#000">
               <?php echo "TPK  ".$detail->Kelompok->mcp_nama."<br />Kelompok  ".$detail->Kelompok->kelompok_nama;?><br />
               <?php echo $detail->Kelompok->mcap_alamat;?>
              </p>

             
            
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    <table>
       <tr>
            <td style="width:120px;">Tanggal Kejadian</td>
            <td style="width:8px;">:</td>
            <td><?php echo $detail->TanggalKejadian['IndoHari'].", ".$detail->TanggalKejadian['Lengkap'];?></td>
       </tr>
        <tr>
            <td>Jenis Layanan</td>
            <td>:</td>
            <td><?php echo $detail->pelayanan_nama;?></td>
       </tr>
        <tr>
            <td>Jenis Mutasi</td>
            <td>:</td>
            <td><?php echo $detail->jenis_mutasi_nama;?></td>
       </tr>
       <tr>
            <td>No Polis</td>
            <td>:</td>
            <td><?php echo $detail->no_polis;?></td>
       </tr>
       <tr>
            <td>Kadaluarsa</td>
            <td>:</td>
            <td><?php echo $detail->KadaluarsaPolis['Lengkap'];?></td>
       </tr>
        <tr>
            <td style="vertical-align:text-top;">Alasan</td>
            <td style="vertical-align:text-top;">:</td>
            <td style="vertical-align:text-top;"><?php echo "1. ".$detail->alasan1_text;?><br />
            	<?php echo "2. ".$detail->alasan2_text;?>
            </td>
       </tr>
     </table>
    </div>
    <div class="col-md-6">
    <table>
    	<tr>
            <td>Petugas</td>
            <td>:</td>
            <td><?php echo $detail->PetugasNamaLengkap;?></td>
       </tr>
       <tr>
            <td style="width:100px;">Lama Dipelihara</td>
            <td style="width:8px;">:</td>
            <td><?php echo str_replace("<br />"," ",$detail->Cow->LamaDiperlihara);?></td>
       </tr>
        <tr>
            <td>Kondisi Kandang</td>
            <td>:</td>
            <td><?php echo $detail->kondisi_kandang;?></td>
       </tr>
        <tr>
            <td>Kondisi Sapi</td>
            <td>:</td>
            <td><?php echo $detail->kondisi_sapi;?></td>
       </tr>
       <tr>
            <td>Laporan Anggota</td>
            <td>:</td>
            <td><?php echo $detail->laporan;?></td>
       </tr>
       
        <tr>
            <td style="vertical-align:top;">Santunan</td>
            <td style="vertical-align:top;">:</td>
            <td style="vertical-align:top;"><?php echo  number_format($detail->santunan,2,",",".");?><br />
           <small style="font-style:italic"> <?php echo  $detail->santunan_terbilang;?></small>
            </td>
       </tr>
     </table>
    </div>
</div><!-- end row 1-->
