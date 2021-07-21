<link type="text/css" href="<?php echo $theme_path;?>css/form_responsive.css" rel="stylesheet" />

<script>
var $m = jQuery.noConflict();
$m(document).ready(function () {
	comboAjax('<?php echo $url_comboAjax;?>/list_lokasi_dome','<?php echo $detail->location->eto_efo;?>','lokasi_dome','<?php echo $detail->location_id;?>','','loaderLokasiDome');	 
	$m("#asal").change(function(){		
		var parentkode=	$m(this).val();
		comboAjax('<?php echo $url_comboAjax;?>/list_lokasi_dome',parentkode,'lokasi_dome','','','loaderLokasiDome');
		$m("#row_lokasi_dome").show();	
		$m("#row_dome_id").show();
		
	}); 
});
function hitung(dta,jenis){
	var ritase_charge=0;
	var ritase_loading=0;
	if(jenis=="ritase_charge"){
		ritase_charge=dta.value;
		ritase_loading=$("#ritase_loading").val();
	}
	if(jenis=="ritase_loading"){
		ritase_charge=$("#ritase_charge").val();
		ritase_loading=dta.value;
	}
	$("#ritase_tersisa_real").val((ritase_charge-ritase_loading));
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
    function setTextField(ddl) {
        document.getElementById('tipe_text').value = ddl.options[ddl.selectedIndex].text;
    }
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
</script>
<style>
.responsive-form .label{
	min-width:195px;
	width:auto;
}
.responsive-form #frmBulanLahir{
	margin-left:5px;
}
.responsive-form #frmTahunLahir{
	margin-left:5px;
}
@media screen and (max-width: 500px) {
	.responsive-form #frmBulanLahir{
		margin-left:0px;
	}
	.responsive-form #frmTahunLahir{
		margin-left:0px;
	}
}
@media screen and (max-width: 320px) {
	.responsive-form #frmBulanLahir{
		margin-left:0px;
	}
	.responsive-form #frmTahunLahir{
		margin-left:0px;
	}
}
</style>
<?php
//echo "<pre>";print_r($detail);echo "</pre>";
?>
<div class="responsive-form" >
 <form id="form_input_data"   method="post" >
 	<div class="row-form">
                    <span class="label" >Kontraktor <small class="wajib">*</small></span>
                  
                  <?php  echo $list_kontraktor['ListContractor']['InputForm'];?>

                </div>
    <div class="row-form">
        <span class="label">Nama Dome</span>
        <?php 
			$nama=$detail->name;
		?>
        <input type="text" class="input" name="name" id="name" placeholder="Nama Dome"  size="35" value="<?php echo $nama;?>"/>
        
    </div>
   <div class="row-form">
        <span class="label" >EFO/ETO <small class="wajib">*</small></span>
     
      <select  class="input" name="asal" id="asal"  style="width:auto"   >
         <option value="">--</option>
           
            <option value="EFO"  <?php echo "EFO"==$detail->location->eto_efo?"selected":""; ?> >EFO</option>
            <option value="ETO"  <?php echo "ETO"==$detail->location->eto_efo?"selected":""; ?> >ETO</option>
            
        </select>

     </div>   
     <div class="row-form" id="row_lokasi_dome">
          <span class="label">Lokasi Dome  </span>
          <select name="lokasi_dome" id="lokasi_dome" class="input">
			 <?php
            echo '<option value="">--Lokasi Dome--</option>';
            
                
            ?>
            </select>
           <img src="<?php echo $theme_path;?>images/loader.gif" style="display:none; height:16px; vertical-align:middle; border:0px;"  alt=""  id="loaderLokasiDome"/>
       </div>
     
     
                <div class="row-form">
                    <span class="label" >Status <small class="wajib">*</small></span>

                  <select  class="input" name="status" id="status"  style="width:auto"   >
                    <option value="">-- Status --</option>
                    <option value="ready"  <?php echo "ready"==$detail->status?"selected":""; ?> >Ready</option>
                    <option value="open"  <?php echo "open"==$detail->status?"selected":""; ?> >Open</option>
                    <option value="pending"  <?php echo "ready"==$detail->status?"selected":""; ?> >Pending</option>
                   	<option value="closed"  <?php echo "closed"==$detail->status?"selected":""; ?> >Closed</option>
                    <option value="shipping"  <?php echo "shipping"==$detail->status?"selected":""; ?> >Shipping</option>
                    <option value="selesai"  <?php echo "selesai"==$detail->status?"selected":""; ?> >Finish</option>
                    </select>

                </div>
     <div class="row-form">
        <span class="label">Kapasitas</span>    
        <input type="text" class="input" name="capacity" id="capacity" placeholder="Kapasitas"  size="10" value="<?php echo $detail->ritase_estimation;?>"/>&nbsp;rit
    </div>
    <div class="row-form">
        <span class="label">Ritase Charge</span>    
        <input type="text" class="input" name="ritase_charge" id="ritase_charge" placeholder="Charge"  size="10" value="<?php echo $detail->ritase_charge;?>" onchange="hitung(this,'ritase_charge');"/> &nbsp;rit
    </div>
    <div class="row-form">
        <span class="label">Ritase Loading/Barging</span>    
        <input type="text" class="input" name="ritase_loading" id="ritase_loading" placeholder="Loading"  size="10" value="<?php echo $detail->ritase_loading;?>" onchange="hitung(this,'ritase_loading');"/>&nbsp; rit
    </div>
     <div class="row-form">
        <span class="label">Sisa</span>    
        <input type="text" class="input" name="ritase_tersisa_real" id="ritase_tersisa_real" placeholder="Sisa"  size="10" value="<?php echo $detail->ritase_tersisa_real;?>"/>&nbsp; rit
    </div>
  
</form>
</div>
