<?php //echo "<pre>"; print_r($detail); echo "</pre>";?>
<style>

.data-parent tr td{
	font-size:1.1em;
	line-height:1.3em;
}
.data-list tr td{
	font-size:1em;
}
</style>
<div class="row">
    <div class="col-md-7">
    	<!-- About Me Box -->
          <div class="box box-default">
           <div class="box-header with-border">
              <h3 class="box-title"><strong>SHIPMENT <?php echo $detail->barge_name;?></strong></h3>
              <span class="pull-right"> <?php echo $tombol_cetak_pdf;?></span>
     	 </div> 
            <!-- /.box-header -->
            <div class="box-body" >
                <table class="data-parent">
               <tr>
                 <td style="width:150px;">Status</td>
                 <td style="width:8px;">:</td>
                 <td><?php echo $detail->step<=5?"<span class=\"label bg-green\">Progress</span>":"<span class=\"label  bg-red\">Done</span>";?></td>
               </tr>
               <tr>
                 <td style="width:150px;">Barge</td>
                 <td style="width:8px;">:</td>
                 <td><?php echo $detail->barge_name;?></td>
               </tr>
               <tr>
                 <td style="width:150px;">Urutan Pengiriman</td>
                 <td style="width:8px;">:</td>
                 <td><?php echo $detail->urutan_pengiriman;?></td>
               </tr>
                <tr>
                 <td style="width:150px;">Jetty/Gate </td>
                 <td style="width:8px;">:</td>
                 <td><?php echo $detail->jetty_name."/".$detail->gate_name;?>
               </td>
               </tr>
                <tr>
                 <td style="width:150px;">Pre Stowage Plan (PSP) </td>
                 <td style="width:8px;">:</td>
                 <td><?php echo number_format($detail->pre_stowage_plan,2,",",".");?>&nbsp;MT</td>
               </tr>
                <tr>
                 <td style="width:150px;">Time Allowed </td>
                 <td style="width:8px;">:</td>
                 <td><?php echo  number_format($detail->lay_time_plan,2,",",".");?> &nbsp; Jam </td>
               </tr>
             <tr>
                 <td style="width:150px;">Berth Time</td>
                 <td style="width:8px;">:</td>
                 <td><?php echo $detail->berth_time;?></td>
               </tr>
               <tr>
               		<td style="">Commenced Time</td>
                    <td style="width:8px;">:</td>
                    <td><?php echo $detail->commenced_time;?></td>
               </tr>
              <tr>
               		<td style="">Completed Time</td>
                    <td style="width:8px;">:</td>
                    <td><?php echo $detail->completed_time;?></td>
               </tr>
                <tr>
                 <td style="width:150px;">Lay Time (Real) </td>
                 <td style="width:8px;">:</td>
                 <td><?php echo  number_format($detail->lay_time_real,2,",",".");?> &nbsp; Jam </td>
               </tr>
               <tr>
               		<td style="">Total Ritase</td>
                    <td style="width:8px;">:</td>
                    <td><?php echo $detail->total_ritase;?></td>
               </tr>
                 <tr>
               		<td style="">Jumlah Dump Truck </td>
                    <td style="width:8px;">:</td>
                    <td><?php echo $detail->jumlah_truck;?></td>
               </tr>
          <tr>
               		<td style="">Final Draugh Survey </td>
                    <td style="width:8px;">:</td>
                    <td><?php echo number_format($detail->final_draugh_survey,2,",",".");?> MT</td>
               </tr>
               <tr>
                 <td style="">Entry Time</td>
                 <td style="width:8px;">:</td>
                 <td><?php echo $detail->entry_time;?></td>
               </tr>
               <tr>
               		<td>Sent Time</td>
                    <td>:</td>
                    <td><?php echo $detail->sent_time;?></td>
               </tr>
                <tr>
               		<td>Received Time</td>
                    <td>:</td>
                    <td><?php echo $detail->received_time;?></td>
               </tr>
               
               </table>
             
            
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
    </div>
</div>
<?php
//echo "<pre>";print_r($detail->detail); echo  "</pre>";?>
<div class="row">
    <div class="col-md-12">
    <div class="box box-default">
     <div class="box-header with-border">
              <h3 class="box-title"><strong>Activity & Ritase Kontraktor</strong></h3>
      </div>     
    <!-- /.box-header -->
    <div class="box-body" >
    <table border="1" class="table table-bordered bordered child data-list" style="border-collapse:collapse;width:95%;" >
                            <tr style="border-top:1px">
                                <th style="width:25px;">No</th>
                                <th style="width:180px;">Kontraktor</th>
                                <th style="width:120px;text-align:center;">Dome</th>
                                <th style="width:100px;text-align:center;">Mulai</th>
                                <th style="width:100px;text-align:center;">Akhir</th>
                                <th style="width:90px;text-align:center;">Jumlah Jam</th>
                                <th style="width:90px;text-align:center;">Ritase</th>
                                <th style="width:150px;text-align:center;">Intermediate Draught Survey</th>
                            </tr>
                            
                            <?php
                $no=1;
                foreach($detail->detail as $key=>$value){
                   ?>
                    <tr>
                                <td style="text-align:center;"><?php echo $no;?></td>
                                <td style="text-align:left;"><?php echo $value->name;?></td>
                                <td style="text-align:center;"><?php echo $value->dome_name; echo trim($value->dome_distance)<>""?" (".$value->dome_distance.")":"";?></td>
                                <td style="text-align:center;"><?php echo $value->start_time2;?></td>
                                <td style="text-align:center;"><?php echo $value->end_time2;?></td>
                                <td style="text-align:center;"><?php echo $value->jumlah_jam;?></td>
                               <td style="text-align:center;"><?php echo $value->ritase;?> (<?php echo $value->quantity;?> MT)</td>
                                <td style="text-align:center;" >
                                <?php echo $value->intermediate_draugh_survey;?>
                              
                                </td>
                            </tr>
                      <?php
                    $no++;
                    
                }
				?>
				</table>
          </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
    </div>
    
</div><!-- end row 1-->
<?php
//echo "<pre>";print_r($detail->detail); echo  "</pre>";?>
<div class="row">
    <div class="col-md-12">
    <div class="box box-primary">
     <div class="box-header with-border">
              <h3 class="box-title"><strong>Detail Uneffective Time</strong></h3>
     	 </div>      
    <!-- /.box-header -->
    <div class="box-body" >
    <table border="1" class="table table-bordered bordered child" style="border-collapse:collapse;width:95%;" >
                            <tr style="border-top:1px">
                                <th style="width:25px;text-align:center;">No</th>
                                <th style="width:180px;">Gangguan</th>
                                <th style="width:120px;text-align:center;">Shift</th>
                                <th style="width:100px;text-align:center;">Mulai</th>
                                <th style="width:100px;text-align:center;">Akhir</th>
                                <th style="width:90px;text-align:center;">Jumlah Jam</th>
                                <th style="width:200px;text-align:center;">Description</th>
                            </tr>
                            
                            <?php
                $no=1;
                foreach($detail->list_gangguan as $key1=>$value1){
                   ?>
                    <tr>
                                <td style="text-align:center;"><?php echo $no;?></td>
                                <td style="text-align:left;"><?php echo $value1->name;?></td>
                                <td style="text-align:center;"><?php echo $value1->shift;?></td>
                                <td style="text-align:center;"><?php echo $value1->start_time2;?></td>
                                <td style="text-align:center;"><?php echo $value1->end_time2;?></td>
                                <td style="text-align:center;"><?php echo $value1->jumlah_jam;?></td>
                               <td style="text-align:center;"><?php echo $value1->description;?></td>
                              
                            </tr>
                      <?php
                    $no++;
                    
                }
				?>
				</table>
          </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
    </div>
    
</div><!-- end row 1-->
<div class="row">
<div class="col-md-12">
  <?php
$no=1;
foreach($detail->files as $key2=>$value2){
   ?>
   
	<img src="<?php echo $value2->url_file;?>"  style="width:65%"/>
<?php
	$no++;
	
}
?>
</div>
</div><!-- end row 1-->
<script>
function editdata(barang_id){
	document.getElementById("lbl-package"+barang_id).style.display="none";
	document.getElementById("value-package"+barang_id).style.display="block";
	document.getElementById("lbl-qty"+barang_id).style.display="none";
	document.getElementById("value-qty"+barang_id).style.display="block";
	//alert(barang_id);
}
</script>