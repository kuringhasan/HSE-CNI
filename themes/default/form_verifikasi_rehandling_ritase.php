<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $theme_path;?>js/accounting.js"></script>
<script type="text/javascript" src="<?php echo $theme_path;?>adminlte-2.4.4/plugins/ajax-typeahead/js/bootstrap-typeahead.js"></script>
<script>
var $m = jQuery.noConflict();
$m(document).ready(function () {
	
	
	
	
});

function comboAjax(url_cmb,parentkode,idTarget,nilai,other_parameters,idloader, idframe)
	{
		//alert(url_cmb+'?nilai='+nilai+'&parentkode='+parentkode+'&'+other_parameters);
		$m("#"+idTarget).hide();
		$m("#"+idloader).show();
		$m.ajax({
			   type:'POST',
			   dataType:'html',
			   url:url_cmb,
			   data:'nilai='+nilai+'&parentkode='+parentkode+'&'+other_parameters,
			   success:function(msg){
				  var obj=JSON.parse(msg);
				   $m("#"+idloader).hide();
				   if (obj.kosong==false)
				   {	
					  $m("#"+idTarget).fadeIn();
					  $m("#"+idTarget).empty().append(obj.html);
				   }else{
					  // $("#"+idframe).fadeOut();
					   $m("#"+idTarget).fadeIn();
					  $m("#"+idTarget).empty().append(obj.html);
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
			if(key=='konfirmasi_ritase'){
				$("#"+key).parent().closest('div').after( " <div class=\"row-form row-error\"><span class=\"lbl_error\">"+errors[key]+"</span></div>" );
			}else{
				$("#"+key).parent().closest('div').after( " <div class=\"row-form row-error\"><span class=\"label\" ></span><span class=\"lbl_error\">"+errors[key]+"</span></div>" );
			}
			
		}
	 }
}	
</script>
<style>
.tbl tr td{
	font-size:13px;
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
<form action="" method="post" name="form_verifikasi_ritase"  id="form_verifikasi_ritase">
	 <div class="row-form"><span class="label" >Kontraktor</span>: <?php echo $detail->contractor_name;?>
      
          
           <input type="hidden" class="input" name="rehandling_id" id="rehandling_id" size="3" value="<?php echo $detail->rehandling_ore_id;?>"/> 
    </div>
  <div class="row-form">
        <span class="label">Tanggal</span>: <?php echo $detail->tgl;?>
        
   </div>
   <div class="row-form">
            <span class="label" >Shift </span>: <?php echo $detail->shift;?>
       
        </div>
     
        <div class="row-form">
				  <span class="label">Tujuan Barge  </span>: <?php echo $detail->barge_name;?>
				
		</div>
      <div class="row-form">
        <span class="label">Dump Truck</span>: <?php echo $detail->no_dump_truck;?>
        
    </div>
    <div class="row-form">
        <span class="label" >Asal </span>: <?php echo $detail->eto_efo;?>
     

     </div>   
     <div class="row-form" id="row_lokasi_dome">
          <span class="label">Lokasi Dome  </span>: <?php echo $detail->location_name;?>
        
       </div>
      <div class="row-form" id="row_dome_id">
          <span class="label">Asal Dome  </span>: <?php echo $detail->dome_asal_name;?>
        
       </div>
      
     <div class="row-form">
        <span class="label">Ritase</span>:  <?php echo $detail->ritase;?> rit
   </div>          
   <div class="row-form" style="text-align:center;border:1px solid #CCC;padding-bottom:10px;padding-top:10px">
        <input type="checkbox" name="konfirmasi_ritase" id="konfirmasi_ritase" /> Pastikan data ritase di atas sudah benar.<br /><span style="color:#F36">Perlu diperhatikan, data yang sudah diverifikasi tidak dapat diubah. <br />
        Apabila ada data yang salah/tidak sesuai silahkan edit terlebih dahulu.</span>
        
   </div>
   
            
</form>
 </div>

