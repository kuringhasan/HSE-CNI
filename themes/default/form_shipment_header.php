<link rel="stylesheet" href="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"> 
<link rel="stylesheet" href="<?php echo $theme_path;?>plugins/step/assets/css/style.css">
<link type="text/css" href="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />

<script>
//alert('cek');
console.log('cek');			
$(document).ready(function () {
			
   var progress_line = $('.f1-steps').find('.f1-progress-line');
 
   loadform('<?php echo $url_form; ?>','shipment_id=<?php echo $shipment_id;?>');

   if(parseInt(<?php echo $step; ?>)==1){
	   $('#btn-prev').hide();
   }
   
   bar_progress(progress_line, '');
   $('#btn-skip').on('click', function() {
	   $('#skip').val('skip');
	   $('#btn-next').click();
   });
   
  
   
   
  $('#btn-next').on('click', function() {
		$('#loader_form').show();						  
    	//var next_step = true;
    	// navigation steps / progress steps
    	var current_active_step =$('.f1-steps').find('.f1-step.active');
    	var progress_line 	= $('.f1-steps').find('.f1-progress-line');
    	var current_step 	=  parseInt($('#current_step').val());
		var next_step=current_step+1;
		var aksi=$('#aksi').val();
		//alert(aksi);
		var url_save='<?php echo $url_action; ?>/save/';
		if(aksi=="edit"){
			url_save='<?php echo $url_action_edit; ?>/save/';
		}
		
	    if((current_step+1)==7){
		   //window.location="<?php echo $url_current;?>";
		}
		$(".input").removeClass("error");
		
    	// fields validation
    	//if( current_step<5 ) {
			//var frmdata= new FormData(document.getElementById("form_input_data"));
			//var files = $('#file_foto_parent')[0].files[0];
        	//frmdata.append('file',files);
			//console.log($("#form_input_data").serialize());
			var hsl = $.ajax({
				type: "POST",
				url:url_save,
				data:  $("#form_input_data").serialize(),
				async: false
			}).responseText;
			
			//$("#media-notice").html(hsl);
			var obj2 = JSON.parse(hsl);
			//alert(hsl);
			if (obj2.success==true){
				
				if(next_step>1){
				   $('#btn-prev').show();
				   $('#btn-next').show();
				}
				var shipment_id='<?php echo $shipment_id;?>';
				if(aksi=="add"){
					shipment_id=obj2.id;
				}
				if(next_step<=5){
					 bar_progress(progress_line,'right');
					 current_active_step.next().removeClass('activated').addClass('active');
					 loadform('<?php echo $url_form; ?>','shipment_id='+shipment_id);
				}else{
					$('#largeModal').modal('hide');
					window.location="<?php echo $url_detail;?>/"+obj2.id;
					/*if (  $.fn.DataTable.isDataTable( '#list_data' ) ) {
						$('#list_data').DataTable().columns().search().draw();
					}*/
					
				}
				//window.location="<?php echo $url_current;?>/"+obj2.id;
			}else{
				alert(obj2.message);
				if( obj2.form_error !== undefined)
				{
					errorForm(obj2.form_error);
				}
			}
			
    	//}
    });
	$('#btn-prev').on('click', function() {
    	// navigation steps / progress steps
		//alert('<?php echo $url_action_edit; ?>/prev');
    	var current_active_step = $('.f1-steps').find('.f1-step.active');
    	var progress_line = $('.f1-steps').find('.f1-progress-line');
    	var current_step = $('#current_step').val();
		var prev_step=current_step-1;
		if(current_step<=5){
			if(current_step>=1){
			   $('#btn-skip').show();
			}
			if(prev_step<=1){
			   $('#btn-prev').hide();
			}else{
				$('#btn-prev').show();
			}
			$('#btn-next').show();
			$('#btn-selesai').hide();
			var url_save='<?php echo $url_action_edit; ?>/prev';
			//alert(url_save);
			var hsl2 = $.ajax({
				type: "POST",
				url:url_save,
				data: "current_step="+current_step,
				async: false
			}).responseText;
			//$("#media-notice").html(hsl2);
			var obj2 = JSON.parse(hsl2);
			
			if (obj2.success==true){
				
				bar_progress(progress_line,'left');
				 loadform('<?php echo $url_form; ?>','shipment_id=<?php echo $shipment_id;?>');
			}
			
    		// change icons
    		current_active_step.removeClass('active').prev().removeClass('activated').addClass('active');
    		// progress bar
    		bar_progress(progress_line, 'left');
			
		}
    });
    
    
 
});


function bar_progress(progress_line_object, direction) {
	var number_of_steps = progress_line_object.data('number-of-steps');
	var now_value = progress_line_object.data('now-value');
	var new_value = 0;
	var part		= ( 100 / (2*number_of_steps) );
	if(direction == 'right') {
		new_value = now_value + ( 100 / (number_of_steps)  );
		if(new_value>100){
			new_value = 100;
		}
	}
	else if(direction == 'left') {
		new_value = now_value - ( 100 / (number_of_steps)  );
		if(new_value<=part){
			new_value = part;
		}
	}else{
		new_value = now_value ;
	}
	progress_line_object.attr('style', 'width: ' + new_value + '%;');
	progress_line_object.attr('data-now-value', new_value);
}

function loadform(url_form,data_form)
{
	//alert(url_form);
	//$("#load-form").css("text-align","center");
	$("#loader_form").fadeIn();
		 $.ajax({
            url : url_form,
			data:data_form,
            type : 'POST',
            success: function(msg){
				
                $('#load-form').html(msg);
                 $("#loader_form").fadeOut();
				 
            }
        });
       
} 

function errorForm(msj_obj){
	 if (jQuery.isEmptyObject(msj_obj)==false)
	 {
		 var errors=msj_obj;
		 $('.row-error').remove();
		for (var key in errors){
			$("#"+key).addClass("error");
		
			$("#"+key).parent().closest('div').after( " <div class=\"row-form row-error\"><span class=\"label\" ></span><span class=\"lbl_error\">"+errors[key]+"</span></div>" );
			
			
		}
	 }
	 
}
function comboAjax(url_cmb,parentkode,idTarget,nilai,other_parameters,idloader, idframe)
	{
		//alert(url_cmb+'?nilai='+nilai+'&parentkode='+parentkode+'&'+other_parameters);
		$("#"+idTarget).hide();
		$("#"+idloader).show();
		$.ajax({
			   type:'POST',
			   dataType:'html',
			   url:url_cmb,
			   data:'nilai='+nilai+'&parentkode='+parentkode+'&'+other_parameters,
			   success:function(msg){
				  var obj=JSON.parse(msg);
				   $("#"+idloader).hide();
				   if (obj.kosong==false)
				   {	
					  $("#"+idTarget).fadeIn();
					  $("#"+idTarget).empty().append(obj.html);
				   }else{
					  // $("#"+idframe).fadeOut();
					   $("#"+idTarget).fadeIn();
					  $("#"+idTarget).empty().append(obj.html);
				   }
				   
			   }///akhisr sukses
		   }); //akhir $.ajax	
	}
	function ubah_angka(dta){
		
		var harga_satuan= convert2angka(dta.value);
		var new_value=accounting.formatNumber(harga_satuan, 2,".",",");
		var value_arr=new_value.split(",");
		
		if(value_arr[1]=="00"){
			dta.value=value_arr[0];
		}else{
			dta.value=new_value;
		}
		//dta.value=new_value[0];//accounting.formatNumber(harga_satuan, 2,".",",");
		
	}
	function convert2angka(nilai){
	// asumsi inputan : titik=ribuan, koma=desimal
	var hasil="";
	if(nilai !== ''){
		var str=String(nilai);
		var posisi_koma		=str.indexOf(',');
		var posisi_titik	=str.indexOf('.');
		if(posisi_koma>=0 && posisi_titik>=0){
			
				hasil = str.split('.').join('');
				//hasil	=str.replace(".","")
				hasil = hasil.split(',').join('.');
				//hasil	= hasil.replace(",",".")
			
		}else if(posisi_koma>=0 && posisi_titik==-1){
			//hasil	= str.replace(",",".")
			hasil = str.split(',').join('.');
		}else if(posisi_koma==-1 && posisi_titik>=0){
			hasil = str.split('.').join('');
			//hasil	= str.replace(".","")
		}else{
			hasil = str;
		}
	}else{
		hasil='0';
	}
	
	return hasil;//outpot, tanpa tanda komo, penanda komo adalah titik
}	
</script>
 <div class="row">
   <div class="col-xs-12">
    <div class="box">
    <div class="box-body">   
<?php 
			
		
			$part	= round(100/(2*5),2);
			
			$width= ($step<=1)?$part:((int)$step*2*$part-$part);
			?>
        <div class="f1-steps" style="text-align:center;margin-top:2px;margin-bottom:2px;">
            <div class="f1-progress" >
           
            
                <div class="f1-progress-line" title="" data-now-value="<?php echo $width;?>" data-number-of-steps="5" style="width:<?php echo $width;?>%;"></div>
            </div>
             <div class="f1-step <?php echo $step==1?"active":"";?> <?php echo $step>1?"activated":"";?>" title="Pembuatan Laporan"> 
                <div class="f1-step-icon"><i class="fa fa-ship"></i></div>
                <p>Pembuatan Laporan</p>
            </div>
            <div class="f1-step <?php echo $step==2?"active":"";?> <?php echo $step>2?"activated":"";?>" title="Entri Ritase"> 
                <div class="f1-step-icon"><i class="fa fa-truck"></i></div>
                <p>Activity & Data Ritase</p>
            </div>
            <div class="f1-step <?php echo $step==3?"active":"";?> <?php echo $step>3?"activated":"";?>" title="Entri Gangguan"> 
                <div class="f1-step-icon"><span class="glyphicon glyphicon-remove"></span></div>
                <p>Gangguan/Uneffective Time</p>
            </div>
             <div class="f1-step <?php echo $step==4?"active":"";?> <?php echo $step>4?"activated":"";?>" title="Upload Berkas"> 
                <div class="f1-step-icon"><span class="fa fa-upload"></span></div>
                <p>Upload Berkas</p>
            </div>
             <div class="f1-step <?php echo $step==5?"active":"";?> <?php echo $step>4?"activated":"";?>" title="Finalisasi"> 
                <div class="f1-step-icon"><i class="fa  fa-hourglass-end"></i></div>
                <p>Finalisasi Laporan</p>
            </div>
          
           
        </div>
       </div> <!-- /.box-body -->
   </div>
  <!-- /.box -->
  </div>
<!-- /.col -->
</div>
<!-- /.row --> 
 <div class="row">
   <div class="col-xs-12">
    <div class="box">
    <div class="box-body">   
       
      <div id="load-form">
        
       </div>
       <div style="text-align:center"><img src="<?php echo $theme_path;?>images/loading50.gif" style="display:none; height:18px; vertical-align:middle; border:0px; position:relative"  alt=""  id="loader_form" align="middle" /></div>
       <div class="responsive-form" >
       <div class="row-form" id="media-notice">
       </div>
       <iframe name="media-upload" id="media-upload" style="display:none" ></iframe>
        </div>
       </div> <!-- /.box-body -->
   </div>
  <!-- /.box -->
  </div>
<!-- /.col -->
</div>
<!-- /.row --> 
     
       
<div class="row">
   <div class="col-xs-12">
    <div class="box">
    <div class="box-body" style="text-align:center">   
       
        
            <input type="hidden" class="input" name="aksi" id="aksi" size="10" value="<?php echo $aksi;?>"/>
           
             
            <?php 
			//echo  $label_tombol_next.$punya_akses;
				$step=(int)$step;
				$lbl_btn_next="Buat Laporan";
				if($aksi=="edit"){
					//$lbl_btn_next="Next";
					$lbl_btn_next=$step==5?"Selesai":"Next";
				}
			
				
				
				?>
				<button type="button" class="btn btn-primary btn-xs" style="min-width:90px;" id="btn-prev" >
				<i class="fa fa-arrow-left"></i> Previous</button>
				<button type="button" class="btn btn-primary btn-xs" style="min-width:90px;" id="btn-next" >
				<span id="label-next"><?php echo $lbl_btn_next;?></span> <i class="fa fa-arrow-right"></i></button>
				
					 <?php
				
				if($show_botton_next==true){
				$next=$step==4?"Selesai":"Next";?>
				
				<?php
				if($step==1){
				?>
				
				<?php 
				}else{
				?>
				<?php
				}
				if($step>1 and $step<4){  $display="";}else{ $display="display:none;";};?>
				 <button type="button" class="btn btn-primary btn-xs" style="min-width:90px;<?php echo $display;?>" id="btn-skip" value="skip" ><span id="label-skip">Skip </span> <i class="fa fa-mail-forward"></i></button>
				<?php
				}//end of show_botton_next
			
			?>
           
       </div> <!-- /.box-body -->
   </div>
  <!-- /.box -->
  </div>
<!-- /.col -->
</div>
<!-- /.row --> 
                    		
<div class="modal fade" id="largeModalChild" tabindex="-1" role="dialog" aria-labelledby="largeModalChild" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close close-largeModalChild" onclick="tutup('largeModalChild');">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Input Data</h4>
        </div>
        <div class="modal-body">
           
        </div>
      <div class="modal-footer">
    
       
      </div>
    </div>
  </div>
</div><!-- end of modal-->             

 
<!-- DataTables -->
<script type="text/javascript" src="<?php echo $theme_path;?>js/accounting.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

<!-- DataTables-->

<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>         
