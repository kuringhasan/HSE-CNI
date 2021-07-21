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
               		<td>Nama</td>
                    <td>:</td>
                    <td><?php echo $detail->name?></td>
               </tr>
               <tr>
               		<td>NIK</td>
                    <td>:</td>
                    <td><?php echo $detail->nik?></td>
               </tr>
               <tr>
               		<td>Is Conpany?</td>
                    <td>:</td>
                    <td><?php if($detail->is_company==0){echo "NO";}else{echo "YES";}  ?></td>
               </tr>
                <tr>
               		<td>Is Contractor?</td>
                    <td>:</td>
                    <td><?php if($detail->is_contractor==0){echo "NO";}else{echo "YES";}  ?></td>
               </tr>
                <tr>
                 <td style="width:100px;">Kode</td>
                 <td style="width:8px;">:</td>
                 <td><?php echo $detail->code;?></td>
               </tr>
               <tr>
               		<td>No KK</td>
                    <td>:</td>
                    <td><?php echo $detail->no_kk;?></td>
               </tr>
                <tr>
               		<td>Alias</td>
                    <td>:</td>
                    <td><?php echo $detail->alias;?></td>
               </tr>
                <tr>
                  <td>Gelar Depan</td>
                  <td>:</td>
                  <td><?php echo $detail->gelar_depan;?></td>
                </tr>
                <tr>
                  <td>Gelar Belakang</td>
                  <td>:</td>
                  <td><?php echo $detail->gelar_belakang;?></td>
                </tr>
                <tr>
                  <td>Tempat Lahir</td>
                  <td>:</td>
                  <td><?php echo $detail->tempat_lahir;?></td>
                </tr>
                <tr>
                  <td>Tempat Lahir Lain</td>
                  <td>:</td>
                  <td><?php echo $detail->tempat_lahir_lain;?></td>
                </tr>
                <tr>
                  <td>Tanggal Lahir</td>
                  <td>:</td>
                  <td><?php echo $detail->tangal_lahir;?></td>
                </tr>
                <tr>
                  <td>Agama</td>
                  <td>:</td>
                  <td><?php echo $detail->agama;?></td>
                </tr>
                <tr>
                  <td>Gender</td>
                  <td>:</td>
                  <td><?php echo $detail->gender;?></td>
                </tr>
                <tr>
                  <td>Kewarganegaraan</td>
                  <td>:</td>
                  <td><?php echo $detail->kewarganegaraan;?></td>
                </tr>
                <tr>
                  <td>Golongan Darah</td>
                  <td>:</td>
                  <td><?php echo $detail->golongan_darah;?></td>
                </tr>
                <tr>
                  <td>Jenis Tanda Pengenal</td>
                  <td>:</td>
                  <td><?php echo $detail->pJenisTandaPengenal;?></td>
                </tr>
                <tr>
                  <td>Alamat</td>
                  <td>:</td>
                  <td><?php echo $detail->alamat;?></td>
                </tr>
                <tr>
                  <td>Alamat RT</td>
                  <td>:</td>
                  <td><?php echo $detail->alamat_rt;?></td>
                </tr>
                <tr>
                  <td>Alamat RW</td>
                  <td>:</td>
                  <td><?php echo $detail->alamat_rw;?></td>
                </tr>
                <tr>
                  <td>Phone</td>
                  <td>:</td>
                  <td><?php echo $detail->phone;?></td>
                </tr>
                <tr>
                  <td>Alamat Kecamatan</td>
                  <td>:</td>
                  <td><?php echo $detail->alamat_kecamatan;?></td>
                </tr>
                <tr>
                  <td>Alamat Desa</td>
                  <td>:</td>
                  <td><?php echo $detail->alamat_desa;?></td>
                </tr>
                <tr>
                  <td>Alamat Kabupaten</td>
                  <td>:</td>
                  <td><?php echo $detail->alamat_kabupaten;?></td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td>:</td>
                  <td><?php echo $detail->email;?></td>
                </tr>
                <tr>
                  <td>Kode Pos</td>
                  <td>:</td>
                  <td><?php echo $detail->kode_pos;?></td>
                </tr>
                <tr>
                  <td>Telepon</td>
                  <td>:</td>
                  <td><?php echo $detail->telepon;?></td>
                </tr>
                <tr>
                  <td>NPWP</td>
                  <td>:</td>
                  <td><?php echo $detail->npwp;?></td>
                </tr>
                <tr>
                  <td>Step</td>
                  <td>:</td>
                  <td><?php echo $detail->step;?></td>
                </tr>
                <tr>
                  <td>Last Update</td>
                  <td>:</td>
                  <td><?php echo $detail->last_update;?></td>
                </tr>
                <tr>
                  <td>Reg Step</td>
                  <td>:</td>
                  <td><?php echo $detail->reg_step;?></td>
                </tr>
                <tr>
                  <td>Reg Date</td>
                  <td>:</td>
                  <td><?php echo $detail->reg_date;?></td>
                </tr>
                <tr>
                  <td>Reg Last Update</td>
                  <td>:</td>
                  <td><?php echo $detail->reg_last_update;?></td>
                </tr>
                <tr>
                  <td>Status pernikahan</td>
                  <td>:</td>
                  <td><?php echo $detail->status_pernikahan;?></td>
                </tr>
                <tr>
                  <td>Nama Pasangan</td>
                  <td>:</td>
                  <td><?php echo $detail->nama_pasangan;?></td>
                </tr>
                <tr>
                  <td>Active</td>
                  <td>:</td>
                  <td><?php echo $detail->active;?></td>
                </tr>
               </table>
             
            
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
    </div>
</div>