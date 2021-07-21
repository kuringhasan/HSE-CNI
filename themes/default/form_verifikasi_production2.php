<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $theme_path;?>js/accounting.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/plugins/ajax-typeahead/js/bootstrap-typeahead.js"></script>
<script>
//var $j = jQuery.noConflict();
$j(document).ready(function () {
	
	$("#frm_tanggal").datepicker({
		 format: 'dd/mm/yyyy',
		 autoclose: true
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
		 $j('.row-error').remove();
		for (var key in errors){
			$("#"+key).addClass("error");
			alert(key);
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
<?php // echo "<pre>";print_r($detail);echo "</pre>";?>
<div class="responsive-form" >
<form action="" method="post" name="form_input_data"  id="form_input_data">
	
  <div class="row-form">
        <span class="label">Tanggal Produksi</span>
         <?php $tanggal=isset($_POST['frm_tanggal'])?$_POST['frm_tanggal']:$detail->tgl;?>
        <input type="text" class="input" name="frm_tanggal" id="frm_tanggal" size="12" value="<?php echo $tanggal;?>"/> 
   </div>
   <div class="row-form">
        <span class="label">Week</span>
         <?php $frm_week=isset($_POST['frm_week'])?$_POST['frm_week']:$detail->week;?>
        <input type="text" class="input" name="frm_week" id="frm_week" size="2" value="<?php echo $frm_week;?>"/> 
   </div>
    <div class="row-form"><span class="label" >Kontraktor</span>
        <select name="kontraktor" id="kontraktor"  class="input" style=";">
          <?php
                echo '<option value="">--Kontraktor--</option>';
                $List=$list_kontraktor;
                while($data = each($List)) {
                    $kontraktor=isset($_POST['kontraktor'])?$_POST['kontraktor']:$detail->partner_id;
                   ?>
          <option value="<?php echo $data['key'];?>"  <?php echo $data['key']==$kontraktor?"selected":""; ?> > <?php echo $data['value'];?></option>
          <?php
               }
                ?>
          </select>
    </div>
     <div class="row-form">
        <span class="label">Quantity</span>
         <?php $frm_qty=isset($_POST['frm_qty'])?$_POST['frm_qty']:$detail->qty;?>
        <input type="text" class="input" name="frm_qty" id="frm_qty" size="10" value="<?php echo $frm_qty;?>"/> 
   </div>
    <div class="row-form"><span class="label" >Tanggal Input</span><?php echo $detail->created;?>
    </div>
    <div class="row-form" style="text-align:center;">
    Pastikan data data tersebut di atas sudah benar. Bila sudah, silahkan klik verifikasi
    </div>
   <div class="row-form checkbox" style="text-align:center;">  
		
           
           <label><input type="checkbox"  name="verifikasi" id="verifikasi" value=""/> Verifikasi</label>
   </div>              
</form>
 </div>

