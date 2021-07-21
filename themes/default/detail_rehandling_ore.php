<?php  //echo "<pre>"; print_r($detail);echo "</pre>";?>
<style>

table tr td{
	font-size:12px;
}
</style>
<div class="row">
    <div class="col-md-7">
    	<!-- About Me Box -->
          <div class="box box-primary">
         
            <!-- /.box-header -->
            <div class="box-body" >
                <table>
               <tr>
                 <td style="width:100px;">Transaction ID</td>
                 <td style="width:8px;">:</td>
                 <td><?php echo $detail->transaction_id;?></td>
               </tr>
               <tr>
               		<td>Kontraktor</td>
                    <td>:</td>
                    <td><?php echo $detail->contractor_name?></td>
               </tr>
               <tr>
               		<td>Barge Tujuan</td>
                    <td>:</td>
                    <td><?php //echo $detail->contractor_name?></td>
               </tr>
               <tr>
               		<td>Dome Asal</td>
                    <td>:</td>
                    <td><?php //echo $detail->contractor_name?></td>
               </tr>
               <tr>
               		<td style="width:100px;">Date</td>
                    <td style="width:8px;">:</td>
                    <td><?php echo $detail->tanggal['Lengkap'];?></td>
               </tr>
                <tr>
                  <td style="width:100px;">Shift</td>
                  <td style="width:8px;">:</td>
                  <td><?php echo $detail->shift;?></td>
                </tr>
                <tr>
               		<td style="width:100px;">Week</td>
                    <td style="width:8px;">:</td>
                    <td><?php echo $detail->periode;?></td>
               </tr>
             
                <tr>
               		<td>Kontraktor</td>
                    <td>:</td>
                    <td><?php echo $detail->contractor_name?></td>
               </tr>
                <tr>
                 <td style="width:100px;">Entry Time</td>
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
                <tr>
                  <td>Total Ritase</td>
                  <td>:</td>
                  <td><?php echo $detail->total_ritase;?></td>
                </tr>
                <tr>
                  <td>Total Qty.</td>
                  <td>:</td>
                  <td><?php echo $detail->total_quantity;?></td>
                </tr>
               </table>
             
            
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
    </div>
</div>
<div class="row">
    <div class="col-md-12">
    <table border="1" class="table table-bordered bordered child" style="border-collapse:collapse;width:95%;" >
                            <tr style="border-top:1px">
                                <th style="width:25px;">No</th>
                                <th style="width:180px;">Drum Truck</th>
                                <th style="width:120px;">Ritase</th>
                                <th style="width:120px;">Qty.</th>
                            </tr>
                            
                            <?php
                $no=1;
                foreach($detail->detail as $key=>$value){
                   ?>
                    <tr>
                                <td style="text-align:center;"><?php echo $no;?></td>
                                <td style="text-align:left;"><?php echo $value->truck_nomor;?></td>
                                <td style="text-align:center;" ondblclick="editdata('<?php echo $value->id;?>');"><span id="lbl-ritase<?php echo $value->id;?>"><?php echo $value->ritase;?></span> <input type="text" name="ritase[]" id="value-ritase<?php echo $value->id;?>" value="<?php echo $value->ritase;?>" style="display:none;width:100%;text-align:center"/> </td>
                                <td style="text-align:center;" ondblclick="editdata('<?php echo $value->id;?>');">
                                <span id="lbl-qty<?php echo $value->id;?>"><?php echo $value->quantity;?></span>
                                <input type="text" name="qty[]" id="value-qty<?php echo $value->id;?>" value="<?php echo $value->quantity;?>"  style="display:none;width:100%;text-align:center"/>
                                </td>
                            </tr>
                      <?php
                    $no++;
                    
                }
				?>
				</table>
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