<link rel="stylesheet" href="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"> 
<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo $theme_path;?>plugins/step/assets/css/style.css">

<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>js/jquery-3.3.1.js"></script>
<!-- DataTables -->

<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> 
<script>
$(document).ready(function () {
							
   var progress_line = $('.f1-steps').find('.f1-progress-line');
  
   loadform('<?php echo $url_form; ?>','');

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
    	var next_step = true;
    	// navigation steps / progress steps
    	var current_active_step =$('.f1-steps').find('.f1-step.active');
    	var progress_line 	= $('.f1-steps').find('.f1-progress-line');
    	var current_step 	=  parseInt($('#current_step').val());
		var aksi=$('#aksi').val();
		var url_save='<?php echo $url_action; ?>/save/';
		//alert(url_save);
	    if((current_step+1)==7){
		   window.location="<?php echo $url_current;?>";
		}
		$(".input").removeClass("error");
		
    	// fields validation
    	//if( current_step<5 ) {
			var hsl = $.ajax({
				type: "POST",
				url:url_save,
				data: $("#form_input_data").serialize(),
				async: false
			}).responseText;
			//alert(hsl);
			//$("#media-notice").html(hsl);
			var obj2 = JSON.parse(hsl);
			//alert(hsl);
			if (obj2.success==true){
				//alert("<?php echo $url_current;?>/"+obj2.id);
				window.location="<?php echo $url_current;?>/"+obj2.id;
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
		
    	var current_active_step = $('.f1-steps').find('.f1-step.active');
    	var progress_line = $('.f1-steps').find('.f1-progress-line');
    	var current_step = $('#current_step').val();
		if(current_step<=5){
			if(current_step>=1){
			   $('#btn-skip').show();
			}
			$('#btn-next').show();
			$('#btn-selesai').hide();
			var url_save='<?php echo $url_action; ?>/prev';
			
			var hsl2 = $.ajax({
				type: "POST",
				url:url_save,
				data: "current_step="+current_step,
				async: false
			}).responseText;
			
			//$("#media-notice").html(hsl2);
			var obj2 = JSON.parse(hsl2);
			
			if (obj2.success==true){
				//alert("<?php echo $url_current;?>");
				window.location="<?php echo $url_current;?>/"+obj2.id;
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
/*function errorForm(msj_obj){
	 if (jQuery.isEmptyObject(msj_obj)==false)
	 {
		
		 var errors=msj_obj;
		for (var key in errors){
			$("#"+key).addClass("error");
			$("#err_"+key).html(errors[key]);
			$("#err_"+key).addClass("lbl_error");
			$("#err_"+key).show();
		}
	 }
}*/
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
             <div class="f1-step <?php echo $step==1?"active":"";?> <?php echo $step>1?"activated":"";?>" title="Biodata"> 
                <div class="f1-step-icon"><i class="fa fa-user-plus"></i></div>
                <p>Biodata</p>
            </div>
             <div class="f1-step <?php echo $step==2?"active":"";?> <?php echo $step>2?"activated":"";?>" title="Pendidikan"> 
                <div class="f1-step-icon"><i class="fa fa-graduation-cap"></i></div>
                <p>Pendidikan</p>
            </div>
            <div class="f1-step <?php echo $step==3?"active":"";?> <?php echo $step>3?"activated":"";?>" title="Riwayat Pekerjaan"> 
                <div class="f1-step-icon"><i class="fa fa-institution"></i></div>
                <p>Riwayat Pekerjaan</p>
            </div>
            <div class="f1-step <?php echo $step==4?"active":"";?> <?php echo $step>4?"activated":"";?>" title="Jabatan di CNI"> 
                <div class="f1-step-icon"><i class="fa fa-sitemap"></i></div>
                <p>Jabatan di CNI</p>
            </div>
             <div class="f1-step <?php echo $step==5?"active":"";?> <?php echo $step>5?"activated":"";?>" title="Upload"> 
                <div class="f1-step-icon"><i class="fa fa-file-image-o"></i></div>
                <p>Upload Foto</p>
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
       <iframe name="media-upload" id="media-upload" style="display:none"></iframe>
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
			if($punya_akses==true){
				if($step==1){// jika step==1
				?>
				<button type="button" class="btn btn-primary btn-xs" style="min-width:90px;" id="btn-next" ><span id="label-next"><?php echo $label_tombol_next;?></span> <i class="fa fa-arrow-right"></i></button>
				<?php 
				}else{//jika step <>1
					$lbl_btn_next=$step==6?"Selesai":"Next";
					?>
					<button type="button" class="btn btn-primary btn-xs" style="min-width:90px;" id="btn-prev" >
					<i class="fa fa-arrow-left"></i> Previous</button>
					<button type="button" class="btn btn-primary btn-xs" style="min-width:90px;" id="btn-next" >
					<span id="label-next"><?php echo $lbl_btn_next;?></span> <i class="fa fa-arrow-right"></i></button>
				
					 <?php
					
				 
				
				}
				
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
			}// $punya akses
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

        
