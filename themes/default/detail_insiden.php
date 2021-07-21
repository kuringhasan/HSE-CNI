<?php  //echo "<pre>"; print_r($detail);echo "</pre>";?>
<style>

table tr td{
	font-size:12px;
}
</style>
<div class="row">
    <div class="col-md-12">
    	<!-- About Me Box -->
          <div class="box box-primary">
         
            <!-- /.box-header -->
            <div class="box-body" >
                <table>
               <tr>
               		<td>Tanggal</td>
                    <td>:</td>
                    <td><?php echo $detail->waktu?></td>
               </tr>
               <tr>
               		<td>Pelapor</td>
                    <td>:</td>
                    <td><?php echo $detail->nama_pelapor?></td>
               </tr>
               <tr>
               		<td>Nama Company</td>
                    <td>:</td>
                    <td><?php echo $detail->nama_company?></td>
               </tr>
                <tr>
               		<td>Lokasi</td>
                    <td>:</td>
                    <td><?php echo $detail->lokasi?></td>
               </tr>
                <tr>
                 <td style="width:100px;">Jenis Kecelakaan</td>
                 <td style="width:8px;">:</td>
                 <td><?php echo $detail->jenis_kecelakaan;?></td>
               </tr>
               <tr>
               		<td>Jumlah Korban</td>
                    <td>:</td>
                    <td><?php echo $detail->jumlah_korban;?></td>
               </tr>
                <tr>
               		<td>Tingkat Keparahan</td>
                    <td>:</td>
                    <td><?php echo $detail->tingkat_keparahan;?></td>
               </tr>
                <tr>
                  <td>Bantuan</td>
                  <td>:</td>
                  <td><?php echo $detail->bantuan;?></td>
                </tr>
                <tr>
                  <td>Foto</td>
                  <td>:</td>
                  <td><?php echo $detail->foto;?></td>
                </tr>
               </table>
             
            
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
    </div>
</div>
