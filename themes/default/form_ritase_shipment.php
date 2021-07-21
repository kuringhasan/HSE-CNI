<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $theme_path;?>js/accounting.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/plugins/ajax-typeahead/js/bootstrap-typeahead.js"></script>
<script>
//var $j = jQuery.noConflict();
$j(document).ready(function () {
	
	
	$("#start_time").datetimepicker({
		 format: 'dd/mm/yyyy hh:ii',
		 minuteStep: 1,
		 autoclose: true
	});
	$("#end_time").datetimepicker({
		 format: 'dd/mm/yyyy hh:ii',
		 minuteStep: 1,
		 autoclose: true
	});
	<?php
	if($detail->contractor_id<>""){
		//$dome_id=trim()
	?>
	comboAjax('<?php echo $url_comboAjax;?>/list_domes',' <?php echo $detail->contractor_id;?>','dome_id','<?php echo $detail->dome_id;?>','status=shipping','loader_dome');	
	<?php
	}
	?>
	$("#contractor_id").change(function(){		
		var parentkode=	$(this).val();	
		//alert('<?php echo $url_comboAjax;?>/list_domes');
		comboAjax('<?php echo $url_comboAjax;?>/list_domes',parentkode,'dome_id','','status=shipping','loader_dome');	
	}); 
	
	
});
function loadcheck(url_check,element_for_update,data_serialize)
{
//alert(data_serialize);
		 $j.ajax({
            url : url_check,
			data:data_serialize,
            type : 'POST',
            success: function(msg){
				//$("#media-test").html(msg);
				var obj2 = JSON.parse(msg);
			
				if(element_for_update=="eartag"){
				//	alert(msg);
					$(".row-no_eartag-baru").remove();
                	$(".label-eartag-baru").removeClass("lbl_error");
					$("#loaderCekEartag").hide();
					
					var html='';
					if(obj2.data.length>0)
					{
						
						$("#no_eartag").parent().closest('div').after( " <div class=\"row-form row-no_eartag-baru\"><span class=\"label\" ></span><span class=\"label-eartag-baru\"><i>"+obj2.message+"</i><br />"+obj2.html+"</span></div>" );
						$(".label-eartag-baru").addClass("lbl_error");
						
					}else{
						$("#no_eartag").parent().closest('div').after( " <div class=\"row-form row-no_eartag-baru\"><span class=\"label\" ></span><span class=\"label-eartag-baru\"><i>No Ertag bisa digunakan</i></span></div>" );
					}
				}
                 //$("#loader_form").fadeOut();
				 
            }
        });
      
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
function errorForm(msj_obj){
	 if (jQuery.isEmptyObject(msj_obj)==false)
	 {
		 var errors=msj_obj;
		 $('.row-error').remove();
		for (var key in errors){
			$("#"+key).addClass("error");
			if(key=='verifikasi'){
				$("#"+key).parent().closest('div').after( " <div class=\"row-form row-error\"><span class=\"lbl_error\">"+errors[key]+"</span></div>" );
			}else{
				$("#"+key).parent().closest('div').after( " <div class=\"row-form row-error\"><span class=\"label\" ></span><span class=\"lbl_error\">"+errors[key]+"</span></div>" );
			}
			
		}
	 }
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
<style>
.tbl tr td{
	font-size:13px;
}
.label{
	display:inline-block;
	min-width:160px;
	color:#000;
	text-align:left;
	font-size:14px;
	padding-left:0px;
	font-weight:normal;
    margin-bottom:0px;
}
input, select{
	font-weight:normal;
	margin-bottom:0px;
}
.form-group{
	margin:2px 2px 2px 2px;
	 position: relative;
}
.label_typeahead{
	width:300px;
	height:auto;
	white-space:pre-line;
	background-color:#06F
	margin:0px 0px 0px 0px;
}
.wajib{
	color:#F00;
}
.catatan{
	display:inline-block;
	font-size:0.7em;
}
.error{
	border:1px solid #F99;
	background-color:#FFC;
}
.lbl_error{
	color:#F00;
	padding-left:154px;
	margin-top:-5px;
	font-size:11px;
	display:block;
	font-style:italic;
	margin-bottom:3px;
}
.cat-tps, .cat-jaringan{
	display:none;
}
@media screen and (max-width: 500px) {
	.label{
		width:100%;
	}
	.lbl_error{
		padding-left:0px;
		
	}
}
</style>
<?php  //echo "<pre>";print_r($detail);echo "</pre>";?>
<div class="responsive-form" >
<form action="" method="post" name="form_input_ritase"  id="form_input_ritase">

  
    <div class="row-form"><span class="label" >Kontraktor</span>
        <select name="contractor_id" id="contractor_id"  class="input" style=";">
          <?php
                echo '<option value="">--Kontraktor--</option>';
                $List=$list_contractor;
                while($data = each($List)) {
                    $contractor_id=isset($_POST['contractor_id'])?$_POST['contractor_id']:$detail->contractor_id;
                   ?>
          <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$contractor_id?"selected":""; ?> > <?php echo $data['value'];?></option>
          <?php
               }
                ?>
          </select>
    </div>
   <div class="row-form"><span class="label" >Asal Dome</span>
        <select name="dome_id" id="dome_id"  class="input" style=";">
         
          </select>
          <img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:16px; vertical-align:middle; border:0px;"  alt=""  id="loader_dome"/>
    </div>
     <div class="row-form">
        <span class="label">Dome Distance</span>
         <?php $dome_distance=isset($_POST['dome_distance'])?$_POST['dome_distance']: number_format($detail->dome_distance,2,",",".");?>
        <input type="text" class="input" name="dome_distance" id="dome_distance" size="6" value="<?php echo $dome_distance;?>" onchange="ubah_angka(this);" />  &nbsp;m
   </div>  	
 
   <div class="row-form"><span class="label" >Shift</span>
        <select name="shift" id="shift"  class="input" style=";">
          <?php
                echo '<option value="">--shift--</option>';
                $List=$list_shift;
                while($data = each($List)) {
                    $shift=isset($_POST['shift'])?$_POST['shift']:$detail->shift;
                   ?>
          <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$shift?"selected":""; ?> > <?php echo $data['value'];?></option>
          <?php
               }
                ?>
          </select>
    </div>
      <div class="row-form">
				 <span class="label" >Waktu</span>
				<input type="text" class="input" name="start_time" id="start_time" size="13" value="<?php echo $detail->start_time2;?>" style="margin-right:3px;width:auto"/> <span style="display:inline-block;float:left;margin-left:4px;margin-right:4px;"> s.d. </span>
				
				<input type="text" class="input" name="end_time" id="end_time" size="13" value="<?php echo $detail->end_time2;?>"/>
				</div>
     <div class="row-form">
        <span class="label">Jumlah Ritase</span>
         <?php $ritase=isset($_POST['ritase'])?$_POST['ritase']:number_format($detail->ritase,2,",",".");?>
        <input type="text" class="input" name="ritase" id="ritase" size="5" value="<?php echo $ritase;?>" onchange="ubah_angka(this);" /> 
   </div>
     <div class="row-form">
        <span class="label">Intermediate  Draugh Survey</span>
         <?php $intermediate_draugh_survey=isset($_POST['intermediate_draugh_survey'])?$_POST['intermediate_draugh_survey']:number_format($detail->intermediate_draugh_survey,2,",",".");?>
        <input type="text" class="input" name="intermediate_draugh_survey" id="intermediate_draugh_survey" size="10" value="<?php echo $intermediate_draugh_survey;?>" onchange="ubah_angka(this);" /> 
   </div>
  
   
</form>
 </div>

