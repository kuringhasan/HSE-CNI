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
               		<td>Kode</td>
                    <td>:</td>
                    <td><?php echo $detail->kode?></td>
               </tr>
               <tr>
               		<td>Nama</td>
                    <td>:</td>
                    <td><?php echo $detail->nama_kondisi?></td>
               </tr>
               </table>
             
            
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
    </div>
</div>